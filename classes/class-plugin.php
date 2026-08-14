<?php
/**
 * Boots our plugin and makes it's internal data accessible
 */
namespace Waterfall_Events;
use Waterfall as Waterfall;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Plugin {
   
    /**
     * Determines whether a class has already been instanciated.
     * @access private
     */
    private static $instance = null;

    /**
     * The parent theme instance
     * @access private
     */
    private $parent = null;

    /**
     * The updater  instance
     * @access public
     */
    public $updater = null;        

    /**
     * Initial constructor
     */
    private function __construct() {}

    /**
     * Retrieve and return the single instance
     */
    public static function instance() {
        
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->launch();
        }

        return self::$instance;
        
    } 
    
    /**
     * Launches our plugin by loading and applying the configurations
     */
    private function launch() {

        // Load the language, before anything else
        load_plugin_textdomain( 'wfe', false, apply_filters('wfe_language_path', WFE_PATH . '/languages') );

        // Hook configurations, just before the main theme does
        add_action('after_setup_theme', [$this, 'setup'], 5);

        // Remove the attachment review rules, killing our event category archives
        add_filter( 'rewrite_rules_array', function($rules) {
            unset($rules['events/[^/]+/([^/]+)/?$']);
            return $rules;
        } );

        // Flushes our rewrite rules after activation or after an update that changed our rules
        add_action( 'wp_loaded', [$this, 'maybe_flush_rewrite_rules'] );

        /**
         * Adds our updater
         */
        $this->updater = \MakeitWorkPress\WP_Updater\Boot::instance();
        $this->updater->add(['type' => 'plugin', 'source' => 'https://github.com/makeitworkpress/waterfall-events']);

    }

    /**
     * Flushes the rewrite rules once, so our event permalinks work without visiting the 
     * permalink settings screen manually.
     * 
     * This is hooked onto wp_loaded rather than onto the activation hook, because our post type 
     * and taxonomies are only registered on init. Flushing any earlier would store a set of 
     * rules that misses our event rules entirely.
     * 
     * The stored version acts as the marker, which means this runs at most once per site per 
     * plugin version. That also covers a network wide activation, where the activation hook only 
     * runs for a single site, as well as updates that change one of our rewrite slugs.
     */
    public function maybe_flush_rewrite_rules() {

        if( get_option('wfe_rewrite_version') === WFE_VERSION ) {
            return;
        }

        /**
         * Our rules can only be generated once our post type is actually registered. If it is not, 
         * the parent theme did not process our configurations and we should try again on a later 
         * request rather than storing an incomplete set of rules.
         */
        if( ! post_type_exists('events') ) {
            return;
        }

        flush_rewrite_rules();

        update_option( 'wfe_rewrite_version', WFE_VERSION );

    }

    /**
     * Verifies whether the parent theme is actually loaded within the current request.
     * 
     * The theme's functions.php - and thus the Waterfall class and the theme autoloader that resolves it - 
     * is not included on every request in which this plugin is loaded. Known situations:
     * 
     * - Multisite: network activated plugins are collected by wp_get_active_network_plugins(), which - unlike 
     *   wp_get_active_and_valid_themes() - does not bail out while wp_installing() is true. Requests to 
     *   wp-admin/upgrade.php, wp-admin/network/upgrade.php and wp-admin/install.php therefore run our plugin 
     *   without any theme being loaded, while after_setup_theme is still fired.
     * - Recovery mode: wp_skip_paused_themes() removes a fatalling theme from the active themes, whereas this 
     *   plugin may very well not be paused and thus still be loaded.
     * - The include of the theme's functions.php failing at runtime, for example while the theme directory is 
     *   being replaced by an update or when an opcode cache serves a stale stat for a file that has moved.
     * - A stale or empty 'template' option, causing get_template_directory() to point at a directory without 
     *   a functions.php, while our own theme check (which reads the stylesheet headers) still passes.
     * 
     * In all of these cases the class is simply absent, and referencing it would throw a fatal error.
     * 
     * @return bool
     */
    private function has_parent_theme() {

        // Passing a string keeps this check unqualified by our namespace and still triggers the theme autoloader
        if( ! class_exists('Waterfall') || ! method_exists('Waterfall', 'instance') ) {
            return false;
        }

        return true;

    }

    /**
     * Informs the user that the parent theme is not available, without breaking the request
     */
    private function parent_theme_unavailable() {

        if( defined('WP_DEBUG') && WP_DEBUG ) {
            error_log( 'Waterfall Events: the Waterfall theme is not loaded in this request, so the plugin has been skipped.' );
        }

        add_action( 'admin_notices', function() {
            echo '<div class="error"><p>' . __('The Waterfall theme could not be loaded. The Waterfall Events plugin requires the Waterfall theme to function.', 'wfe') . '</p></div>';
        } );

    }

    /**
     * Loads our configurations and modules
     */
    public function setup() {

        /**
         * Bail out gracefully if our parent theme is not part of this request, rather than throwing a fatal error
         */
        if( ! $this->has_parent_theme() ) {
            $this->parent_theme_unavailable();
            return;
        }

        // Loads our parent instance
        $this->parent = Waterfall::instance();  

        // Our configurations are injected into the configuration handler of the parent theme, so it has to be present
        if( ! isset($this->parent->config) || ! method_exists($this->parent->config, 'add') ) {
            $this->parent_theme_unavailable();
            return;
        }
     
        /**
         * Launch the various modules of our plugin
         */
        $modules = [
            'Waterfall_Events\Ajax', 
            'Waterfall_Events\Controllers\Events', 
            'Waterfall_Events\Views\Archive_Events',
            'Waterfall_Events\Views\Single_Events'
        ];

        foreach( $modules as $module ) {
            if( class_exists($module) ) {
                new $module();
            }
        }     
        
        /**
         * Configurations (hook onto the parent theme)
         */

        // Load our general configurations, which populate $configurations
        $configurations = [];

        require_once( WFE_PATH . '/config/general.php' );

        // Some configurations only load in certain contexts
        if( is_admin() ) {
            require_once( WFE_PATH . '/config/meta.php' );
            require_once( WFE_PATH . '/config/options.php' );

            $configurations['options']['event_meta']     = $event_meta;
            $configurations['options']['organizer_meta'] = $organizer_meta;
            $configurations['options']['location_meta']  = $location_meta;
            $configurations['options']['options']        = $options;

        }

        // Add customizer settings
        if( is_customize_preview() ) {

            // require_once( WFE_PATH . '/config/customizer.php' );
            // $configurations['options']['colors_panel']       = $colors_panel;
            // $configurations['options']['layout_panel']       = $layout_panel;
            // $configurations['options']['typography_panel']   = $typography_panel;

        }

        $configurations = apply_filters('waterfall_events_configurations', $configurations);

        // Only a set of predefined configurations can be added
        foreach( (array) $configurations as $name => $values ) {

            if( ! in_array($name, ['register', 'options', 'enqueue', 'elementor']) ) {
                continue;
            } 

            $this->parent->config->add( $name, $values );

        }

    }

}