<?php
/**
 * This class controls how data moves in and out from events
 * While technically not really a controller, it is named as such
 */
namespace Waterfall_Events\Controllers;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Events extends \Waterfall_Events\Base {

    /**
     * Checks if we are already syncing. Prevents infinite loops
     */
    private $syncing;

    /**
     * Registers our hooks. Is automatically performed upon initialization, as we extend the WFE_Base class
     */
    protected function register() {
        $this->actions = [
            ['rest_api_init', 'register_event_sync_meta'],
            ['save_post', 'sync_save_events', 15, 2]
        ];
    }

    /**
     * Registers our synchronisation targets in the rest api,
     * so they are recognized by gutenberg and we can modify the values in JavaScript accordingly
     * 
     * @param WP_REST_Server $wp_rest_server The WP Rest Server object
     * @return WP_REST_Server $wp_rest_server The WP Rest Server object
     */
    public function register_event_sync_meta( $wp_rest_server ) {

        if( ! is_multisite() ) {
            return $wp_rest_server;
        }

        $current    = get_current_blog_id();
        $sites      = get_sites(['site__not_in' => [$current]]);
    
        if( $sites ) {

            foreach( $sites as $site ) {           

                register_post_meta('events', 'wfe_event_sync_target_' . $site->blog_id, [
                    'show_in_rest'  => true,
                    'single'        => true,
                    'type'          => 'string',
                ]); 

            }
        
        }

        return $wp_rest_server;

    }

    /**
     * Saves the starting timestamp for an event, so it can be sorted
     * 
     * @param Int $id The post ID for the post saved.
     */
    public function save_event_sort_date( $id ) {

        if( ! $id ) {
            return;
        }

        $event_type = get_post_meta($id, 'wfe_type', true);

        if( $event_type === 'multiday' ) {
            $event_days = get_post_meta($id, 'wfe_multiday_date', true);  
            
            if( isset($event_days[0]['date']) && $event_days[0]['date'] ) {
                $start_date_time = [
                    'date' => $event_days[0]['date'],
                    'time' => $event_days[0]['starttime']
                ];
            }
        } else {
            $start_date_time = [
                'date' => get_post_meta($id, 'wfe_startdate', true),
                'time' => get_post_meta($id, 'wfe_starttime', true)
            ];
        }  

        if( isset($start_date_time) && $start_date_time['date'] ) {

            $sort_date  = (int) get_post_meta($id, 'wfe_sort_date', true);
            $start_date_timestamp = intval($start_date_time['date']);

            if( $start_date_time['time'] && preg_match('/\d{2}:\d{2}/', $start_date_time['time']) ) {
                $start_date_time_hours_minutes = explode(':', $start_date_time['time']);
                $start_date_timestamp += ($start_date_time_hours_minutes[0] * 3600) + ($start_date_time_hours_minutes[1] * 60);
            }
        
            if( $sort_date !== $start_date_timestamp ) {
                update_post_meta( $id, 'wfe_sort_date', $start_date_timestamp );
            }

        }

    }

    /**
     * Syncs an event to other events in the multisite network
     * Saves the data of an event for dates with multiple dates
     * 
     * @param Int $id The post ID for the post saved.
     * @param WP_Post $post The post object of the post saved.
     */
    public function sync_save_events( $id, $post ) {

        /**
         * Permissions
         */
        // Do not save on autosaves
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return; 
        }                

        // Check permissions
        if( ! current_user_can( 'edit_posts', $id ) || ! current_user_can( 'edit_pages', $id ) ) {
            return;
        }

        // Should be the events post type
        if( $post->post_type != 'events' ) {
            return;
        } 
        
        /**
         * Saving a starting post date for multiday events
         * This is used for event sorting
         */
        $this->save_event_sort_date($id);

        /**
         * The syncing part
         */

        // The installation should be a multisite
        if( ! is_multisite() ) {
            return;
        }

        // Fix infite loop if sites are synced to each other; if we're syncing we can only do this action once.
        if( $this->syncing ) {
            return;
        }

        /**
         * Get the information at the existing event
         */

        // Featured and marker images (@todo Background image for Waterfall Meta)
        $featured_image     = $this->get_attachment_data( get_post_meta($id, '_thumbnail_id', true) );   
        $marker_image       = $this->get_attachment_data( rtrim( get_post_meta($id, 'wfe_location_icon', true), ',') );

        // Target and post-meta
        $meta               = get_post_custom($id);    

        // Get our sites
        $current_blog_id    = get_current_blog_id();
        $sites              = get_sites(['site__not_in' => [$current_blog_id]]);
    
        if( ! $sites ) {
            return;
        }
        
        /**
         * Move to our sites and sync them
         */
        foreach( $sites as $site ) {  

            $sync = get_post_meta($id, 'wfe_event_sync_' . $site->blog_id, true);

            if( ! $sync ) {
                continue;
            }

            // We are syncing
            $this->syncing = true;

            // Check if there is already a connection setup
            $targetID = get_post_meta($id, 'wfe_event_sync_target_' . $site->blog_id, true);

            /** 
             * Update the settings at our destination blog
             */
            switch_to_blog($site->blog_id);

            $syncedID  = wp_insert_post([
                'ID'            => $targetID,
                'post_content'  => $post->post_content,
                'post_excerpt'  => $post->post_excerpt,
                'post_parent'   => $post->post_parent,
                'post_status'   => $post->post_status,
                'post_title'    => $post->post_title,
                'post_type'     => $post->post_type
            ]);

            // wp_die( var_dump($meta) );

            // Update our metadata
            foreach( $meta as $key => $values) {
                
                // Skip site synchronization settings  and empty them out, otherwise, update the meta
                if( mb_strpos($key, 'wfe_event_sync_') === false ) {
                    update_post_meta( $syncedID, $key, $values[0] );
                } elseif( mb_strpos($key, 'wfe_event_sync_') !== false ) {
                    update_post_meta( $syncedID, $key, '' );
                }

            }   
            
            // Update our upstream synchronization (synchronisation is one way for now)
            // update_post_meta( $syncedID, 'wfe_event_sync_' . $current_blog_id, false );
            // update_post_meta( $syncedID, 'wfe_event_sync_target_' . $current_blog_id, '' );

            // Check if we have a featured image, and check it's existence or upload it
            if( $featured_image ) {
                $new_featured_id = $this->upload_attachment($featured_image);
                if( $new_featured_id ) {
                    set_post_thumbnail($syncedID, $new_featured_id);
                }
            }            

            // Check if we have a marker image, and check it's existence or upload it
            if( $marker_image ) {
                $new_marker_image = $this->upload_attachment($marker_image);
                if( $new_marker_image ) {
                    update_post_meta($syncedID, 'wfe_location_icon', $new_marker_image);
                }
            }

            /**
             * And update our existing event accordingly
             */
            switch_to_blog($current_blog_id);

            update_post_meta($id, 'wfe_event_sync_target_' . $site->blog_id, $syncedID);

        }

        // We have done syncing, we can safely reset it now to allow new saves.
        $this->syncing = false;

    }

    /**
     * Retrieves relevant attachment data, such as filesize, url and path.
     *  
     * @param Int $attachment_id The ID for the attachment
     * @return Boolean|Array $data The attachment data 
     */
    private function get_attachment_data( $attachment_id = 0 ) {

        $data       = false;

        if( ! is_numeric($attachment_id) ) {
            return $data;
        }        

        $attachment = get_post($attachment_id);

        if( ! $attachment ) {
            return $data;
        }

        $file = get_attached_file($attachment_id);

        $data = [
            'name'  => $attachment->post_name,
            'path'  => $file,
            'size'  => filesize($file),
            'url'   => $attachment->guid
        ];

        return $data;

    }

    /**
     * Uploads a file from a path and returns the ID of the new attachment, or returns the ID of the attachment already exists
     * 
     * @param String $attachment The attachment data of the attachment you want to upload, containing at least url, name, size keys
     * @return Int $id The id of the newly uploaded attachment
     */
    private function upload_attachment( $attachment ) {

        $existing_attachment    = get_page_by_path( $attachment['name'], OBJECT, 'attachment');
        $id                     = 0;

        // Attachment with same name exists, now let's check the size
        if( $existing_attachment ) {
            $id     = $existing_attachment->ID;

            // If the sizes and names are similar, the file is most likely the same
            if( filesize(get_attached_file($id)) == $attachment['size'] ) {
                return $id;
            }
        } 

        if( ! function_exists('media_sideload_image') ) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');            
        }

        $upload = media_sideload_image($attachment['url'], 0, null, 'id');
        if( ! is_wp_error($upload) && is_numeric($upload) ) {
            $id = $upload;
        }

        return $id;

    }

}