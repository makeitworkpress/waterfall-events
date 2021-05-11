<?php
/**
 * Displays the details for an event
 */
namespace Waterfall_Events\Views\Components;
use WP_Post as WP_Post;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Details extends Component {

    protected function initialize() {

        // Parameters
        $this->params = wp_parse_args( $this->params, [
            'categories'        => true,
            'categories_title'  => __('Event Category', 'wfe'),
            'dates'             => true,
            'dates_title'       => __('Event Date', 'wfe'),  
            'post_id'           => 0, // POST ID                  
            'price'             => true,
            'price_title'       => __('Event Price', 'wfe'),
            'register'          => true,
            'register_label'    => __('Register', 'wfe'),            
            'register_title'    => __('Event Registration', 'wfe'),            
            'tags'              => true,
            'tags_title'        => __('Event Tags', 'wfe'),
            'title'             => __('Details', 'wfe'),
            'website'           => true,
            'website_title'     => __('Event Website', 'wfe')
        ] );

    }

    /**
     * Formats the details
     */
    protected function format() {

        if( $this->params['post_id'] ) {
            $id = $this->params['post_id'];
        } else {
            global $post;
            $id = $post->ID;
        }
        $taxonomies;

        /**
         * Default titles
         */
        foreach( ['dates_title', 'price_title', 'register_title', 'title', 'website_title'] as $prop ) {
            $this->props[$prop ]   = $this->params[$prop];  
        }

        /**
         * Dates
         */
        if( $this->params['dates'] ) {
            $this->props['dates'] = (new Dates())->render(false);
        }

        /**
         * Price
         */
        if( $this->params['price'] ) {
            $this->props['price'] = (new Price())->render(false);
        } 
        
        /**
         * Register
         */
        if( $this->params['register'] ) {
            $registration       = get_post_meta($id, 'wfe_registration', true);
            $registration_label = get_post_meta($id, 'wfe_registration_label', true);

            if( $registration ) {
                $this->props['register'] = wpc_atom('button', [
                    'attributes'    => ['class' => 'wfe-registration-btn primary', 'href' => get_post_meta($id, 'wfe_registration', true), 'target' => '_blank'], 
                    'label'         => $registration_label ? $registration_label : $this->params['register_label']
                ], false);
            }
        }          

        /**
         * Related terms to the event
         */
        if( $this->params['categories'] ) {
            $taxonomies['events_category'] = [
                'after'     => '', 
                'before'    => 
                '<h3>' . $this->params['categories_title'] . '</h3><i class="fa fa-certificate"></i>', 
                'icon'      => false, 
                'schema'    => '', 
                'seperator' => ', '
            ]; 
        }

        if( $this->params['tags'] ) {
            $taxonomies['events_tag'] = [
                'after'     => '', 
                'before'    => 
                '<h3>' . $this->params['tags_title'] . '</h3><i class="fa fa-tags"></i>', 
                'icon'      => false, 
                'schema'    => '', 
                'seperator' => ', '
            ]; 
        }        

        if( isset($taxonomies) && $taxonomies ) {
            $this->props['terms'] = wpc_atom('termlist', ['taxonomies' => $taxonomies], false);
        }

        /**
         * The event website (external)
         */
        if( $this->params['website'] ) {
            $link                   = esc_url(get_post_meta($id, 'wfe_website', true));
            $this->props['website'] = $link ? '<a href="' . $link  . '" target="_blank" title="' . $this->params['website_title'] . '">' . $link . '</a>' : '';
        }
    
    }

}
