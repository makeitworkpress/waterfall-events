<?php
/**
 * Displays the details for an event
 */
namespace Waterfall_Events\Views\Components;
use WP_Post as WP_Post;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Price extends Component {

    protected function initialize() {

        $this->params = wp_parse_args( $this->params, ['post_id' => null] );

    }

    
    /**
     * Formats the details
     */
    protected function format() {

        if( ! $this->params['post_id'] ) {
            global $post;
            $this->params['post_id'] = $post->ID;
        }

        $currency                   = get_post_meta($this->params['post_id'], 'wfe_currency', true);

        $this->props['currency']    = $currency ? $currency : wf_get_data('options', 'events_currency');
        $this->props['price']       = get_post_meta($this->params['post_id'], 'wfe_cost', true);

    }

}