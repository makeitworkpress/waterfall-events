<?php
/**
 * Contains helper functions
 */
namespace Waterfall_Events;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Helper {

  /**
   * Determines if we're in the elementor editor
   * 
   * @return Boolean 
   */
  public static function is_elementor_editor() {

    if( (isset($_GET['action']) && $_GET['action'] == 'elementor') || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor_ajax') ) {
      return true;
    } else {
      return false;
    }

  }

}