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
            // ['wp_ajax_getChartData', 'getChartData'],
            // ['wp_ajax_nopriv_getChartData', 'getChartData'],
            // ['wp_ajax_loadTables', 'loadTables'],
            // ['wp_ajax_nopriv_loadTables', 'loadTables'],            
            // ['wp_ajax_filterReviews', 'filterReviews'],
            // ['wp_ajax_nopriv_filterReviews', 'filterReviews']
        ];

    }

    /**
     * Retrieves the chart data for a certain meta key
     */
    public function getChartData() {
        wp_verify_nonce( 'we-love-good-events', $_POST['action'] );
        wp_send_json_success( $data );
    }

}