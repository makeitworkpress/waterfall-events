<?php
/**
 * This class adapts the hooks in our single.php template in the Waterfall theme, so the data from the event is loaded accordingly.
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
            ['components_content_before', 'render_summary'],
            ['components_content_after', 'render_details']
        ];

        $this->filters = [];

    }

    /**
     * Render summary (description + registration button)
     */
    public function render_summary() {

        if( ! $this->rendering_queried_event() ) {
            return;
        }

        (new Components\Summary())->render();

    }

    /**
     * Render details
     */
    public function render_details() {

        if( ! $this->rendering_queried_event() ) {
            return;
        }

        (new Components\Details())->render();
        (new Components\Organizers())->render();
        (new Components\Locations())->render();

    }

    /**
     * Determines whether we are rendering the content of the event that is actually being viewed.
     * 
     * The components_content_before and components_content_after hooks are fired by the content 
     * atom of WP-Components, for both the content and the excerpt type. On a single event that 
     * atom therefore also runs for every event rendered within the page, such as the related 
     * events, and our components would be repeated underneath each of those.
     * 
     * is_singular() cannot tell those apart, because it describes the main query and stays true 
     * for the whole request, while the global post is switched to the nested event. Comparing the 
     * global post to the object of the main query does distinguish them.
     * 
     * @return bool Whether our components should be rendered.
     */
    private function rendering_queried_event() {

        // Our components belong to a single event only
        if( ! is_singular('events') ) {
            return false;
        }

        $post = get_post();

        // Bail out for the nested loops, where the global post is not the event being viewed
        if( ! $post || (int) $post->ID !== (int) get_queried_object_id() ) {
            return false;
        }

        // Authors can disable the automatic output per event
        if( get_post_meta($post->ID, 'wfe_disable_components', true) ) {
            return false;
        }

        return true;

    }

}
