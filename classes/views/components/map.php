<?php
/**
 * Displays all the events in a map
 */
namespace Waterfall_Events\Views\Components;
use WP_Post as WP_Post;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Map extends Component {

    protected function initialize() {

        $this->params = wp_parse_args( $this->params, [
            'center'    => ['lat' => 52.090736, 'lng' => 5.121420],  
            'cluster'   => false, // Whether to cluster markers or not
            'clusterip' => wf_get_data('options', 'events_cluster_icon_path'), // Path to the custom cluster icons including trailing slash; icons should be named m1.png, m2.png, m3.png, m4.png, m5.png respectively and have similar sizes
            'fit'       => false, // Fits map to markers by default       
            'filters'   => ['country' => __('All countries', 'wfe')], // categories, country, tags
            'id'        => uniqid(), // Unique ID for the map
            'markers'   => [], // Custom markers, keys: ['categories', country, dates => [['enddate', 'endtime', 'startdate', 'starttime', 'title']], icon, lat, lng, infowindow => [button_label, button_link, categories, description, event_link, tags, title], number, postalCoda, street, tags]
            'styles'    => '', // JSON styles (for example from snazzy maps), which allows custom map styling
            'zoom'      => 11
        ] );

    }
    
    /**
     * Formats the details
     */
    protected function format() {

        /**
         * Formats our markers
         */
        if( ! $this->params['markers'] ) {

            $events = get_posts(['fields' => 'ids', 'post_type' => 'events', 'posts_per_page' => -1]);
            
            if( $events ) {

                foreach($events as $id) {

                    $categories = wp_get_post_terms($id, 'events_category', ['fields' => 'id=>name']);
                    $dates      = new Dates(['id' => $id]);
                    $location   = (array) get_post_meta($id, 'wfe_location', true) + ['icon' => get_post_meta($id, 'wfe_location_icon', true), 'name' => ''];
                    $locations  = [];
                    $tags       = wp_get_post_terms($id, 'events_tag', ['fields' => 'id=>name']);

                    // Reformat location and icon data if we have an events_location term
                    if( ! $location['lat'] && ! $location['lng'] ) {
                        $location_terms = wp_get_post_terms($id, 'events_location', ['fields' => 'id=>name']);
                        if( is_array($location_terms) && $location_terms ) {
                            foreach($location_terms as $term_id => $term_name) {
                                $term_meta              = get_term_meta($term_id, 'wfe_location_meta', true);
                                $locations[$term_id]    = [
                                    'icon' => isset($term_meta['location_icon']) ? $term_meta['location_icon']: '',
                                    'name' => esc_html($term_name)
                                ];
                                foreach (['city', 'country', 'number', 'lat', 'lng', 'postal_code', 'street'] as $field ) {
                                    $locations[$term_id][$field] = isset($term_meta['location'][$field]) ? $term_meta['location'][$field] : '';
                                }
                            }
                        } else {
                            $locations[] = $location;
                        } 
                    } else {
                        $locations[] = $location; 
                    }
                    
                    // The array of data for the markers, for each location
                    foreach( $locations as $key => $location ) {

                        // Events should have coordinates
                        if( ! $location['lat'] || ! $location['lng'] ) {
                            continue;
                        }

                        $button         = get_post_meta($id, 'wfe_registration_label', true);
                        $description    = get_post_meta($id, 'wfe_description', true);

                        $this->params['markers'][] = [
                            'categories'    => array_keys($categories),
                            'country'       => $location['country'],
                            'icon'          => $location['icon'] ? esc_url(wp_get_attachment_url( rtrim($location['icon'], ',')) ) : '',
                            'infoWindow'    => [
                                'buttonLabel'   => $button ? $button : __('Register', 'wfe'),
                                'buttonLink'    => get_post_meta($id, 'wfe_registration', true),
                                'categories'    => array_values($categories),
                                'city'          => esc_html($location['city']),
                                'country'       => esc_html($location['country']),
                                'dates'         => $dates->props['dates'],
                                'description'   => $description ? $description : get_the_excerpt($id),
                                'eventLink'     => esc_url( get_permalink($id) ),
                                'locationName'  => esc_html($location['name']),
                                'number'        => esc_html($location['number']),
                                'postalCode'    => esc_html($location['postal_code']),
                                'street'        => esc_html($location['street']),                                
                                'tags'          => array_values($tags),
                                'title'         => get_the_title($id)
                            ],
                            'lat'           => floatval($location['lat']),
                            'lng'           => floatval($location['lng']),
                            'tags'          => array_keys($tags)                        
                        ];

                    }

                }
            }
        }

        if( ! $this->params['markers'] ) {
            return;
        }

        // And we can go on
        $this->props['id'] = $this->params['id'];

        /**
         * Prepares our filters
         */
        foreach( $this->params['filters'] as $filter => $label ) {
            
            if( $filter == 'categories' || $filter == 'tags' ) {
                $this->props['filters'][$filter] = [
                    'label'     => $label, 
                    'values'    => get_terms(['fields' => 'id=>name', 'hide_empty' => false, 'taxonomy' => $filter == 'tags' ? 'events_tag' : 'events_category'])
                ];   
            }            
            
            if( $filter == 'country' ) {
                $countries = [];
                foreach( $this->params['markers'] as $marker ) {
                    $countries[$marker['country']] = $marker['country'];
                }
                uasort($countries, function($a, $b) { return strcmp($a, $b); });
                $this->props['filters']['country']  = ['label' => $label, 'values' => $countries];
            }

        }

        /**
         * Enqueues the google map script from WP-Components (integrated into the parent theme)
         */
        if( ! wp_script_is('google-maps-js') && apply_filters('components_maps_script', true) ) {
            wp_enqueue_script('google-maps-js'); 
        }

        // Enqueues our markercluster script
        if( ! wp_script_is('wfe-markercluster') && $this->params['cluster'] ) {
            wp_enqueue_script('wfe-markercluster'); 
        }           

        /**
         * Adds the map settings to the footer as a script. This allows the general JS to pickup these settings and create a custom map
         */
        add_action('wp_footer', [$this, 'echoConfigJS']);

    }

    // Echo our custom variables
    public function echoConfigJS() {

        $styles = $this->params['styles'] ? $this->params['styles'] : "[]";

        echo '<script type="text/javascript">
            var wfeMap' . $this->props['id'] . '= { 
                center: ' . json_encode($this->params['center']) . ',
                cluster: "' . $this->params['cluster'] . '",
                clusterIconPath: "' . $this->params['clusterip'] . '",
                fit: ' . json_encode($this->params['fit']) . ',
                markers: ' . json_encode($this->params['markers']) . ', 
                styles: ' . $styles . ',
                zoom: ' . (int) $this->params['zoom'] . 
            '}
        </script>';
        
    }

}