<?php
/**
 * Displays all the events in a list or grid
 * 
 * @todo Add a filter functionality
 */
namespace Waterfall_Events\Views\Components;
use WP_Query as WP_Query;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Events extends Component {

    protected function initialize() {

        $this->params = wp_parse_args( $this->params, [
          'appear'        => 'bottom',    // How a post appears, bottom, left, right, top or false
          'categories'    => false,       // Show the event category
          'columns'       => 'full',      // Accepts full, half, third, fourth or fifth
          'date'          => true,        // Shows the event date
          'excerpt'       => false,       // Show an excerpt or not
          'gap'           => 'default',   // Gap between items, accepts default, none, tiny, small, medium, large, huge
          'height'        => '',          // The minimum height of the element in pixel
          'image_enlarge' => false,       // Enlarge the featured image
          'image_float'   => 'left',      // Default float of image (left, right, center or none)
          'image_size'    => 'thumbnail', // Size of the image used
          'location'      => true,        // Show the location of the event in the list
          'none'          => '',          // Optional text that is shown when no events are found
          'pagination'    => false,       // Shows the pagination
          'price'         => false,       // Show the price of an event
          'query'         => null,        // Accepts a WP_Query object or an array of query_args
          'register'      => false,       // Show the registration button for an event
          'schema'        => true,        // If enabled, adds microdata
          'sort'          => '',          // Sort by post_date, event_date or title
          'tags'          => false,       // Show the event tag
          'title_tag'     => 'h3',        // The tag used for the title
          'view'          => 'list'       // List or grid display
        ] );

    }
    
    /**
     * Formats the details
     */
    protected function format() {

      $no_schema = wf_get_data('options', 'scheme_post_types_disable');

      // Default query arguments
      if( ! $this->params['query'] ) {
        $this->params['query'] = [
          'posts_per_page'  => get_option('posts_per_page'),
          'post_status'     => 'publish',
          'post_type'       => 'events',
        ];  
      }

      if( $this->params['sort'] && ! isset($this->params['query']['orderby']) ) {
        switch($this->params['sort']) {
          case 'post_date':
            $this->params['query']['orderby'] = 'date';
            break;
          case 'event_date':
            $this->params['query']['order'] = 'ASC';
            $this->params['query']['meta_key'] = 'wfe_sort_date';
            $this->params['query']['orderby'] = 'meta_value_num';
            break;
          case 'title':
            $this->params['query']['order'] = 'ASC';
            $this->params['query']['orderby'] = 'title';
            break;
        }
      }

      $this->props = [
        'attributes'        => [
          'class'           => 'wfe-events',
        ],
        'grid_gap'        => $this->params['gap'],
        'none'            => $this->params['none'] ? $this->params['none'] : __('No events are found', 'wfe'),
        'pagination'      => $this->params['pagination'] ? ['type' => 'numbers'] : false,
        'post_properties' => [
          'attributes'      => [
            'itemtype'        => is_array($no_schema) && in_array($type, $no_schema) ? '' : 'http://schema.org/Event', 
            'style'           => [
              'min-height'    => $this->params['height'] ? $this->layout['height'] . 'px' : ''
            ]
          ],            
          'content_atoms'   => $this->params['excerpt'] ? ['content' => ['atom' => 'content', 'properties' => ['type' => 'excerpt']]] : [], 
          'grid'            => $this->params['columns'],   
          'header_atoms'    => [ 
              'title'         => [
                  'atom'        => 'title', 
                  'properties'  => [
                    'attributes'  => ['itemprop' => 'name', 'class' => 'entry-title'], 
                    'link'        => 'post',
                    'tag'         => $this->params['title_tag']
                  ]
              ] 
          ],
          'image'           => [
            'attributes'      => [
              'class'           => 'entry-image'
            ],
            'enlarge'         => $this->params['image_enlarge'], 
            'float'           => $this->params['image_float'],                   
            'size'            => $this->params['image_size']
          ] 
        ],
        'query'           => $this->params['query'] instanceof WP_Query ? $this->params['query'] : '',
        'query_args'      => is_array($this->params['query']) ? $this->params['query'] : [],
        'schema'          => is_array($no_schema) && in_array($type, $no_schema) ? false : true,
        'view'            => $this->params['view']
      ];


      if( $this->params['location'] ) {
        $this->props['post_properties']['footer_atoms']['location'] = [
          'atom'        => 'callback',
          'properties'  => [
            'callback' => [$this, 'render_location']
          ]
        ];        
      }         

      // Event details
      if( $this->params['date'] || $this->params['price'] || $this->params['tags'] || $this->params['categories'] || $this->params['register'] ) {

        $this->props['post_properties']['footer_atoms']['details'] = [
          'atom'        => 'callback',
          'properties'  => [
            'callback' => [$this, 'render_details']
          ]
        ];
      }   

    }

    /**
     * Since we are using WP-Components, the render view is overwritten
     * 
     * @param boolean   $render  If set to false, returns the given template
     */
    public function render( $render = true ) {

      if( ! $this->props ) {
        return;
      }     

      if( $render ) {
        wpc_molecule('posts', $this->props);
      } else {
        return wpc_molecule('posts', $this->props, false);
      }

    }

    /**
     * Renders our event details
     */
    public function render_details() {

      global $post;

      if( ! isset($post->ID) ) {
        return;
      }

      $details = new Details([
        'categories'        =>  $this->params['categories'],
        'class'             => 'wfe-events-item-details',
        'dates'             => $this->params['date'],
        'post_id'           => $post->ID,               
        'price'             => $this->params['price'],
        'register'          => $this->params['register'],
        'tags'              => $this->params['tags'],
        'titles'            => false,
        'website'           => false      
      ]);

      $details->render();

    }

    /**
     * Renders the location for an event 
     * (the primary location or a custom location)
     */
    public function render_location() {

      global $post;

      if( ! isset($post->ID) ) {
        return;
      } 

      $location         = '';
      $meta_location    = get_post_meta($post->ID, 'wfe_location', true);

      if( ! $meta_location['city'] && $meta_location['country'] ) {
        $term_locations = wp_get_post_terms($post->ID, 'events_location', ['fields' => 'id=>name']);

        // We only grab the first location
        if( $term_locations && is_array($term_locations) ) {
          $location = implode(',', $term_locations);
        }
      } else {
        $location = $meta_location['city'] ? $meta_location['city'] : $meta_location['country'];
      }

      if( $location ) {
        echo '<div class="wfe-events-item-location"><i class="fa fa-map-marker"></i><span>' . $location . '</span></div>';
      }

    }

}