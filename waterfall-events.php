<?php
/*
Plugin Name:  Waterfall Events
Plugin URI:   https://www.makeitwork.press/wordpress-plugins/waterfall-events/
Description:  The Waterfall Events plugin upgradres your Waterfall WordPress theme with event capabilities.
Version:      0.0.2
Author:       Make it WorkPress
Author URI:   https://makeitwork.press/
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * First, we have to check if the waterfall template is being used
 * If it is not used, we return an error in the admin area, indicating that the theme should be present.
 */
$theme = wp_get_theme();

if( $theme->template != 'waterfall' ) {
    add_action( 'admin_notices', function() {
        echo '<div class="error"><p>' . __('The Waterfall theme is not present or activated. The Waterfall Events plugin requires the Waterfall theme to function.', 'wfe') . '</p></div>';      
    });     
    return;
}

/**
 * Registers the autoloading for theme classes
 */
spl_autoload_register( function($className) {
    
    $calledClass    = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($className) ) );
    
    // Plugin Classes
    $pluginClass    = str_replace( 'waterfall-events' . DIRECTORY_SEPARATOR, '', $calledClass);
    $pluginClass    = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $pluginClass . '.php';
    
    if( file_exists($pluginClass) ) {
        require_once( $pluginClass );
        return;
    } 
    
    // Theme classes
    $themeDir      = get_template_directory() . DIRECTORY_SEPARATOR;
    $themeClass    = $themeDir . 'classes' . DIRECTORY_SEPARATOR . $calledClass . '.php';

    if( file_exists($themeClass) ) {
        require_once( $themeClass );
        return;
    } 
    
    // Require Vendor (composer) classes
    $classNames     = explode(DIRECTORY_SEPARATOR, $calledClass);
    array_splice($classNames, 2, 0, 'src');

    $vendorClass    = $themeDir . 'vendor' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $classNames) . '.php';

    if( file_exists($vendorClass) ) {
        require_once( $vendorClass );    
    }
   
} );

/**
 * Boots our plugin
 */
add_action( 'plugins_loaded', function() {
    defined( 'WFE_PATH' ) or define( 'WFE_PATH', plugin_dir_path( __FILE__ ) );
    defined( 'WFE_URI' ) or define( 'WFE_URI', plugin_dir_url( __FILE__ ) );

    $plugin = Waterfall_Events\Plugin::instance();
} );