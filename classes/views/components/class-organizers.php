<?php
/**
 * Displays the summary of a single review, including advantages and disadvantages
 */
namespace Waterfall_Events\Views\Components;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Organizers extends Component {

    protected function initialize() {

        // Parameters
        $this->params = wp_parse_args( $this->params, [
            'title'     => __('Organizers', 'wfe'),
            'post_id'   => 0
        ] );

    }

    /**
     * Formats the details
     */
    protected function format() {

        if( ! $this->params['post_id'] ) {
            global $post;
            $this->params['post_id'] = $post->ID;
        }
        
        // Organizers
        $organizers = wp_get_post_terms($this->params['post_id'], 'events_organizer', ['fields' => 'id=>name']);  
        if( ! $organizers ) {
            return;
        }

        $this->props['organizers']  = [];
        $this->props['title']       = $this->params['title'];

        foreach( $organizers as $term_id => $term_name ) {
            $meta = get_term_meta($term_id, 'wfe_organizer_meta', true);
            $this->props['organizers'][] = [
                'email'     => isset($meta['email']) && is_email($meta['email']) ? esc_html($meta['email']) : '',
                'link'      => esc_url( get_term_link($term_id) ),
                'name'      => esc_html($term_name),
                'phone'     => isset($meta['phone']) ? esc_html($meta['phone']) : '',
                'website'   => isset($meta['website']) ? esc_url($meta['website']) : ''
            ];
        }

    }

}