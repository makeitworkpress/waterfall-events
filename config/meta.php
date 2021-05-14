<?php
/**
 * Our metabox configurations
 */
defined( 'ABSPATH' ) or die('Go eat veggies!');

// Custom meta for the events post type
$eventMeta  = [
    'frame'     => 'meta',
    'fields'    => [
        'context'   => 'normal',
        'id'        => 'wfe_events_meta',
        'priority'  => 'high',
        'screen'    => ['events'],
        'single'    => true,
        'title'     => __('Event Settings', 'wfe'),
        'type'      => 'post',
        'sections'  => [
            'general' => [
                'icon'      => 'settings',
                'id'        => 'general',
                'title'     => __('General Settings', 'wfe'),
                'fields'    => [
                    [
                        'columns'       => 'fourth',
                        'description'   => __('The type of event.', 'wfe.'),                        
                        'id'            => 'wfe_type',
                        'title'         => __('Type', 'wfe'),
                        'options'       => [
                            'normal'    => __('Normal', 'wfe'),
                            'multiday'  => __('Multi-day', 'wfe')
                        ],
                        'placeholder'   => __('Select a type', 'wfe'),
                        'type'          => 'select'
                    ],
                    [
                        'columns'       => 'fourth',
                        'id'            => 'wfe_description',
                        'title'         => __('Description', 'wfe'),
                        'description'   => __('Short description of the event.', 'wfe.'),
                        'rows'          => 3,
                        'type'          => 'textarea'
                    ],                      
                    [
                        'columns'       => 'fourth',
                        'id'            => 'wfe_registration',
                        'title'         => __('Registration Link', 'wfe'),
                        'description'   => __('The registration link for the event.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'url'
                    ],
                    [
                        'columns'       => 'fourth',
                        'id'            => 'wfe_registration_label',
                        'title'         => __('Registration Label', 'wfe'),
                        'description'   => __('The text displayed in the registration button, defaults to "Register".', 'wfe.'),
                        'type'          => 'input'
                    ],
                    [
                        'columns'       => 'fourth',
                        'alt-format'    => 'j F Y',
                        'description'   => __('The event starting date.', 'wfe.'),
                        'dependency'    => ['source' => 'wfe_type', 'equation' => '=', 'value' => 'normal'],
                        'id'            => 'wfe_startdate',
                        'title'         => __('Starting Date', 'wfe'),
                        'type'          => 'datepicker'
                    ],
                    [
                        'class'         => 'medium-text',
                        'columns'       => 'fourth',
                        'description'   => __('The event starting time.', 'wfe.'),
                        'dependency'    => ['source' => 'wfe_type', 'equation' => '=', 'value' => 'normal'],
                        'id'            => 'wfe_starttime',
                        'title'         => __('Starting Time', 'wfe'),
                        'type'          => 'input',
                        'subtype'       => 'time'
                    ],  
                    [
                        'columns'       => 'fourth',
                        'alt-format'    => 'j F Y',
                        'description'   => __('The event ending date.', 'wfe.'),
                        'dependency'    => ['source' => 'wfe_type', 'equation' => '=', 'value' => 'normal'],
                        'id'            => 'wfe_enddate',
                        'title'         => __('Ending Date', 'wfe'),
                        'type'          => 'datepicker'
                    ],
                    [
                        'class'         => 'medium-text',
                        'columns'       => 'fourth',
                        'description'   => __('The event ending time.', 'wfe.'),
                        'dependency'    => ['source' => 'wfe_type', 'equation' => '=', 'value' => 'normal'],
                        'id'            => 'wfe_endtime',
                        'title'         => __('Ending Time', 'wfe'),
                        'type'          => 'input',
                        'subtype'       => 'time'
                    ],                                                               
                    [
                        'description'   => __('Set-up multiple days and variable times for each day.', 'wfe.'),
                        'dependency'    => ['source' => 'wfe_type', 'equation' => '=', 'value' => 'multiday'],
                        'id'            => 'wfe_multiday_date',
                        'title'         => __('Multiday Event', 'wfe'),
                        'type'          => 'repeatable',
                        'fields'        => [
                            [
                                'class'         => 'regular-text',
                                'columns'       => 'fourth',
                                'description'   => __('Add an optional event title for this day here.', 'wfe.'),
                                'id'            => 'title',
                                'title'         => __('Day Title', 'wfe'),
                                'type'          => 'input'
                            ],                             
                            [
                                'columns'       => 'fourth',
                                'alt-format'    => 'j F Y',
                                'description'   => __('The date for this day', 'wfe.'),
                                'id'            => 'date',
                                'title'         => __('Date', 'wfe'),
                                'type'          => 'datepicker'
                            ],
                            [
                                'class'         => 'medium-text',
                                'columns'       => 'fourth',
                                'description'   => __('The starging time for this day.', 'wfe.'),
                                'id'            => 'starttime',
                                'title'         => __('Starting Time', 'wfe'),
                                'type'          => 'input',
                                'subtype'       => 'time'
                            ],  
                            [
                                'class'         => 'medium-text',
                                'columns'       => 'fourth',
                                'description'   => __('The ending time for this day.', 'wfe.'),
                                'id'            => 'endtime',
                                'title'         => __('Ending Time', 'wfe'),
                                'type'          => 'input',
                                'subtype'       => 'time'
                            ]  
                        ]
                    ], 
                    [
                        'id'            => 'heading_other',
                        'title'         => __('Other details', 'wfe'),
                        'type'          => 'heading'
                    ],                        
                    [
                        'class'         => 'small-text',
                        'columns'       => 'fourth',
                        'description'   => __('The currency for the event cost.', 'wfe.'),
                        'id'            => 'wfe_currency',
                        'title'         => __('Currency', 'wfe'),
                        'type'          => 'input'
                    ],
                    [
                        'columns'       => 'fourth',
                        'description'   => __('The cost for the event.', 'wfe.'),
                        'id'            => 'wfe_cost',
                        'title'         => __('Event Cost', 'wfe'),
                        'type'          => 'input',
                        'subtype'       => 'number'
                    ],
                    [
                        'columns'       => 'half',
                        'id'            => 'wfe_website',
                        'title'         => __('Event Website', 'wfe'),
                        'description'   => __('The external website for the event.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'url'
                    ]                    
                ]                 
            ],
            'location' => [
                'icon'      => 'place',
                'id'        => 'location',
                'title'     => __('Custom Location', 'wfe'),
                'fields'    => [
                    [
                        'columns'       => 'three-fourth',
                        'description'   => __('The custom event location, which can be used instead of assigning an event to a location.', 'wfe.'),
                        'id'            => 'wfe_location',
                        'title'         => __('Custom Location', 'wfe'),
                        'type'          => 'location'
                    ], 
                    [
                        'columns'       => 'fourth',
                        'description'   => __('A custom map marker for this event. Replaces the default red marker used by Google Maps.', 'wfe.'),
                        'id'            => 'wfe_location_icon',
                        'multiple'      => false,
                        'title'         => __('Custom Map Marker', 'wfe'),
                        'type'          => 'media'
                    ],
                ]
            ],
            'advanced' => [
                'icon'      => 'loop',
                'id'        => 'advanced',
                'title'     => __('Advanced', 'wfe'),
                'fields'    => [
                    [
                        'id'            => 'wfe_disable_components',
                        'description'   => __('This will disable the display of all event details so you can manually determine the look of the event.', 'wfe'),
                        'single'        => true,
                        'style'         => 'switcher switcher-disable',
                        'title'         => __('Disable Event Components', 'wfe'),
                        'type'          => 'checkbox',
                        'options'       => [
                            'disable' => ['label' => __('Disable Event Components', 'wfe')]
                        ]
                    ]                                                                                                 
                ]
            ]
        ]   
    ]
];

// If we have a multisite, additional advanced settings are added to the events post type
if( is_multisite() ) {
    $eventMeta['fields']['sections']['advanced']['fields'][] = [];

    $current    = get_current_blog_id();
    $sites      = get_sites(['site__not_in' => [$current]]);

    if( $sites ) {

        foreach( $sites as $site ) {
            $eventMeta['fields']['sections']['advanced']['fields'][] = [
                'id'            => 'wfe_event_sync_heading_' . $site->blog_id,
                'title'         => sprintf( __('Event Synchronization for %s ', 'wfe'), $site->blogname ),
                'type'          => 'heading',
            ];        
            $eventMeta['fields']['sections']['advanced']['fields'][] = [
                'columns'       => 'half',
                'id'            => 'wfe_event_sync_' . $site->blog_id,
                'description'   => __('This will synchronizing this event to another event at the given site. It will not synchronize taxonomies, such as Event Categories', 'wfe'),
                'single'        => true,
                'style'         => 'switcher switcher-enable',
                'title'         => __('Synchronize Event', 'wfe'),
                'type'          => 'checkbox',
                'options'       => [
                    'disable' => ['label' => sprintf( __('Synchronize to %s', 'wfe'), $site->blogname )]
                ]
            ];

            switch_to_blog($site->blog_id);

            $options = wp_cache_get( md5($site->blog_id . '_wfe_event_sync') );

            if( ! $options ) {
                $options = [];
                $posts   = get_posts(['posts_per_page' => -1, 'post_type' => 'events', 'post_status' => ['publish', 'future', 'draft', 'pending', 'private']]);

                if( $posts ) {
                    foreach( $posts as $post ) {
                        $options[$post->ID] = $post->post_title;
                    }
                }

                wp_cache_set( md5($site->blog_id . '_wfe_event_sync'), $options, '', 3600);
            }

            $eventMeta['fields']['sections']['advanced']['fields'][] = [
                'columns'       => 'half',
                'id'            => 'wfe_event_sync_target_' . $site->blog_id,
                'description'   => __('This will sync the event from this site to the target. If you leave this empty, it will automatically setup a new event at the given site', 'wfe'),
                'title'         => __('Target Event', 'wfe'),
                'placeholder'   => __('Select event', 'wfe'),
                'type'          => 'select',
                'options'       => $options
            ];
        }

        switch_to_blog($current);

    }

}

/**
 * Custom meta fields for th organizer taxonomy
 */
$organizerMeta  = [
    'frame'     => 'meta',
    'fields'    => [
        'id'        => 'wfe_organizer_meta',
        'taxonomy'  => 'events_organizer',
        'title'     => __('Additional Organizer Settings', 'wfe'),
        'type'      => 'term',
        'sections'  => [
            'general' => [
                'id'        => 'general',
                'tabs'      => false,
                'title'     => __('Details', 'wfe'),
                'fields'    => [                    
                    [
                        'columns'       => 'third',
                        'id'            => 'email',
                        'title'         => __('Organizer Email', 'wfe'),
                        'description'   => __('The organizers email.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'email'
                    ],                      
                    [
                        'columns'       => 'third',
                        'id'            => 'phone',
                        'title'         => __('Organizer Phone', 'wfe'),
                        'description'   => __('The organizers phone.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'tel'
                    ],                      
                    [
                        'columns'       => 'third',
                        'id'            => 'website',
                        'title'         => __('Organizer Website', 'wfe'),
                        'description'   => __('The organizer website.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'url'
                    ]
                ]
            ]
        ]
    ]
];

/**
 * Custom meta fields for the location taxonomy
 */
$locationMeta  = [
    'frame'     => 'meta',
    'fields'    => [
        'id'        => 'wfe_location_meta',
        'taxonomy'  => 'events_location',
        'title'     => __('Additional Location Settings', 'wfe'),
        'type'      => 'term',
        'sections'  => [
            'general' => [
                'id'        => 'general',
                'tabs'      => false,
                'title'     => __('Details', 'wfe'),
                'fields'    => [ 
                    [
                        'id'            => 'location',
                        'title'         => __('Location', 'wfe'),
                        'description'   => __('The address for this location. Search for a location to automatically get the right details.', 'wfe.'),
                        'type'          => 'location'
                    ],                                                          
                    [
                        'columns'       => 'third',
                        'id'            => 'email',
                        'title'         => __('Location Email', 'wfe'),
                        'description'   => __('The locations email.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'email'
                    ],                      
                    [
                        'columns'       => 'third',
                        'id'            => 'phone',
                        'title'         => __('Location Phone', 'wfe'),
                        'description'   => __('The locations phone.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'tel'
                    ],                      
                    [
                        'columns'       => 'third',
                        'id'            => 'website',
                        'title'         => __('Location Website', 'wfe'),
                        'description'   => __('The locations website.', 'wfe.'),
                        'type'          => 'input',
                        'subtype'       => 'url'
                    ],
                    [
                        'description'   => __('A custom map marker for this location. Replaces the default red marker used by Google Maps.', 'wfe.'),
                        'id'            => 'location_icon',
                        'multiple'      => false,
                        'title'         => __('Custom Map Marker', 'wfe'),
                        'type'          => 'media'
                    ],                      
                ]
            ]
        ]
    ]
];