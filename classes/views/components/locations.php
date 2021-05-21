<?php
/**
 * Displays the summary of a single review, including advantages and disadvantages
 */
namespace Waterfall_Events\Views\Components;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Locations extends Component {

    protected function initialize() {

        $this->params = wp_parse_args( $this->params, ['post_id' => 0, 'title' => __('Locations', 'wfe')] );

    }

    /**
     * Formats the details
     */
    protected function format() {

        if( ! $this->params['post_id'] ) {
            global $post;
            $this->params['post_id'] = $post->ID;
        }

        $icon = get_post_meta($this->params['post_id'], 'wfe_location_icon', true);
        
        // Retrieve the locations
        $defaults   = [
            'email'     => '', 
            'icon'      => $icon ? esc_url(wp_get_attachment_url( rtrim($icon, ','))) : '',
            'link'      => '', 
            'name'      => '', 
            'phone'     => '', 
            'website'   => ''
        ];
        $location   = (array) get_post_meta($this->params['post_id'], 'wfe_location', true) + $defaults;
        $this->props['locations']   = [];
        $this->props['title']       = $this->params['title'];

        if( ! $location['lat'] && ! $location['lng'] ) {
            $location_terms = wp_get_post_terms($this->params['post_id'], 'events_location', ['fields' => 'id=>name']);
            if( is_array($location_terms) && $location_terms ) {
                foreach($location_terms as $term_id => $term_name) {
                    $term_meta = get_term_meta($term_id, 'wfe_location_meta', true);

                    $this->props['locations'][$term_id]    = [
                        'email'     => isset($term_meta['email']) && is_email($term_meta['email']) ? $term_meta['email']: '',
                        'icon'      => isset($term_meta['location_icon']) && $term_meta['location_icon'] ? esc_url(wp_get_attachment_url( rtrim($term_meta['location_icon'], ','))) : '',
                        'link'      => esc_url( get_term_link($term_id) ),
                        'name'      => esc_html($term_name),
                        'phone'     => isset($term_meta['phone']) ? esc_html($term_meta['phone']) : '', 
                        'website'   => isset($term_meta['website']) ? esc_url($term_meta['website']) : ''
                    ];
                    foreach (['city', 'country', 'number', 'lat', 'lng', 'postal_code', 'street'] as $field ) {
                        $this->props['locations'][$term_id][$field] = isset($term_meta['location'][$field]) ? $term_meta['location'][$field] : '';
                    }
                }
            }   
        } else {
            $this->props['locations'][] = $location;
        }

        $markers = [];
        foreach( $this->props['locations'] as $location ) {
            $markers[] = ['lat' => $location['lat'], 'lng' => $location['lng'], 'icon' => $location['icon'] ];
        }
        
        if( $markers && $markers[0]['lat'] && $markers[0]['lng']  ) {
            $this->props['map'] = wpc_atom('map', [
                'center'    => ['lat' => $markers[0]['lat'], 'lng' => $markers[0]['lng']],
                'id'        => 'wpcDefaultMap', 
                'markers'   => $markers, 
                'zoom'      => 12
            ], false); 
        }

    }

}