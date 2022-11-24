<?php
/**
 * This class adapts the hooks in our index.php template in the Waterfall theme, so the data from the review is loaded accordingly.
 */
namespace Waterfall_Events\Views;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Archive_Events extends \Waterfall_Events\Base {

    /**
     * Register our hooks and actions
     */
    protected function register() {

        // $this->defaults = [
        //     'reviews_archive_content_charts'            => false 
        // ];

        // $this->actions = [
        //     ['waterfall_before_archive_posts', 'before'],
        //     ['waterfall_after_archive_posts', 'after'],
        // ];

        // $this->filters = [
        //     ['waterfall_blog_schema_post_types', 'blogSchema'],
        //     ['waterfall_archive_posts_args', 'events']
        // ];
        
    }

}