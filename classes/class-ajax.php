<?php
/**
 * This class is a container class helding several ajax powered functions,
 * such as functions for displaying charts and filtering reviews
 */
namespace Waterfall_Events;
use WP_Query as WP_Query;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Ajax extends Base {

    /**
     * Registers our hooks. Is automatically performed upon initialization, as we extend the WFE_Base class
     */
    protected function register() {

        $this->actions = [
            // ['wp_ajax_getData', 'getData'],
            // ['wp_ajax_nopriv_getData', 'getData'],
        ];

    }

    /**
     * Retrieves the chart data for a certain meta key
     */
    // public function getData() {

    // }

}