<?php
/**
 * This class adapts the hooks in our single.php template in the Waterfall theme, so the data from the review is loaded accordingly.
 */
namespace Waterfall_Events\Views;
defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Single_Events extends \Waterfall_Events\Base {

    /**
     * Registers our associated custom fields, actions and filters
     */
    protected function register() {

        $this->defaults = [];

        $this->actions = [  
            ['components_content_before', 'renderSummary'],
            ['components_content_after', 'renderDetails'] 
        ];

        $this->filters = [];

    }

    /**
     * Render summary (description + registration button)
     */
    public function renderSummary() {
        
        global $post;

        $manual = get_post_meta($post->ID, 'wfe_disable_components', true);

        if( is_singular('events') && ! $manual ) {
            (new Components\Summary())->render();
        }
            
    }   

    /**
     * Render details
     */
    public function renderDetails() {

        global $post;

        $manual = get_post_meta($post->ID, 'wfe_disable_components', true);

        if( is_singular('events') && ! $manual ) {
            (new Components\Details())->render();
            (new Components\Organizers())->render();
            (new Components\Locations())->render();
        }

    }

}