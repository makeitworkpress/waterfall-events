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

        // Hook configurations, just before the main theme does
        add_action('after_setup_theme', [$this, 'setup'], 5);

        /**
         * Some additional hooks
         */     

        // Remove the attachment review rules, killing our category archives
        add_filter( 'rewrite_rules_array', function($rules) {
            unset($rules['events/[^/]+/([^/]+)/?$']);
            return $rules;
        } );

    }

    /**
     * Loads our configurations and modules
     */
    public function setup() {

        // Loads our parent instance
        $this->parent = Waterfall::instance();  
     
        /**
         * Launch the various modules of our plugin
         */
        $modules = [
            'Waterfall_Events\Ajax', 
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

        // Load our general configurations
        require_once( WFE_PATH . '/config/general.php' );

        // Some configurations only load in certain contexts
        if( is_admin() ) {
            require_once( WFE_PATH . '/config/meta.php' );
            require_once( WFE_PATH . '/config/options.php' );

            $configurations['options']['eventMeta']     = $eventMeta;
            $configurations['options']['organizerMeta'] = $organizerMeta;
            $configurations['options']['locationMeta']  = $locationMeta;
            $configurations['options']['options']       = $options;

        }

        if( is_customize_preview() ) {

            // require_once( WFE_PATH . '/config/customizer.php' );
            // $configurations['options']['colorsPanel']       = $colorsPanel;
            // $configurations['options']['layoutPanel']       = $layoutPanel;
            // $configurations['options']['typographyPanel']   = $typographyPanel;

        }

        $configurations = apply_filters('waterfall_reviews_configurations', $configurations);

        // Only a set of predefined configurations can be added
        foreach( $configurations as $name => $values ) {

            if( ! in_array($name, ['register', 'options', 'enqueue', 'elementor']) ) {
                continue;
            } 

            $this->parent->config->add( $name, $values );

        }

    }

}