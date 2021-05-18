<?php
/**
 * Our basic configurations, which inject our configurations inside the parent theme
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

$configurations = [
    'elementor' => [
        'Waterfall_Events\Views\Elementor\Map'
    ],
    'enqueue'   => [
        ['handle' => 'wfe-style', 'src' => WFE_URI . 'assets/css/waterfall-events.min.css'], 
        [
            'handle'    => 'wfe-scripts', 
            'localize'  => [
                'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                'debug'         => WP_DEBUG,
                'nonce'         => wp_create_nonce( 'we-love-good-events' ),                
            ], 
            'name'      => 'wfe',
            'src'       => WFE_URI . 'assets/js/waterfall-events.js', 
        ],
        ['handle' => 'wfe-admin-scripts', 'src' => WFE_URI . 'assets/js/wfe-admin.js',  'deps' => ['wp-editor'], 'context' => 'block-editor']
    ],
    'register' => [
        'postTypes' => [
            [
                'name'      => 'events',
                'plural'    => __( 'Events', 'wfe' ),
                'singular'  => __( 'Event', 'wfe' ),
                'args'      => [
                    'menu_icon'     => 'dashicons-calendar', 
                    'has_archive'   => true,
                    'hierarchical'  => true,
                    'show_in_rest'  => true,
                    'supports'      => ['author', 'comments', 'editor', 'excerpt', 'thumbnail', 'title', 'custom-fields'],
                    'rewrite'       => ['slug' => _x('events', 'Events Slug', 'wfe'), 'with_front' => false]
                ]
            ],
        ],
        'taxonomies' => [
            [
                'name'      => 'events_category',
                'object'    => 'events',
                'plural'    => __( 'Categories', 'wfe' ),
                'singular'  => __( 'Category', 'wfe' ),
                'args'      => [
                    'has_archive'       => true,
                    'hierarchical'      => true,
                    'rewrite'           => [
                        'hierarchical'  => true, 
                        'slug'          => _x('events/category', 'Events Category Slug', 'wfe'), 
                        'with_front'    => false
                    ],
                    'show_admin_column' => true,
                    'show_in_rest'      => true
                ]                
            ],
            [
                'name'      => 'events_tag',
                'object'    => 'events',
                'plural'    => __( 'Tags', 'wfe' ),
                'singular'  => __( 'Tag', 'wfe' ),
                'args'      => [
                    'has_archive'       => true,
                    'hierarchical'      => false,
                    'rewrite'           => [
                        'hierarchical'  => false, 
                        'slug'          => _x('events/tag', 'Events Tag Slug', 'wfe'), 
                        'with_front'    => false
                    ],
                    'show_admin_column' => true,
                    'show_in_rest'      => true
                ]                
            ],            
            [
                'name'      => 'events_location',
                'object'    => 'events',
                'plural'    => __( 'Locations', 'wfe' ),
                'singular'  => __( 'Location', 'wfe' ),
                'args'      => [
                    'has_archive'       => true,
                    'hierarchical'      => true,
                    'rewrite'           => [
                        'hierarchical'  => false,
                        'slug'          => _x('events/location', 'Events Location Slug', 'wfe'), 
                        'with_front'    => false
                    ],
                    'show_admin_column' => true,
                    'show_in_rest'      => true
                ]                
            ],
            [
                'name'      => 'events_organizer',
                'object'    => 'events',
                'plural'    => __( 'Organizers', 'wfe' ),
                'singular'  => __( 'Organizer', 'wfe' ),
                'args'      => [
                    'has_archive'       => true,
                    'hierarchical'      => true,
                    'rewrite'           => [
                        'hierarchical'  => true,
                        'slug'          => _x('events/organizer', 'Events Organizer Slug', 'wfe'), 
                        'with_front'    => false
                    ],
                    'show_admin_column' => true,
                    'show_in_rest'      => true
                ]                
            ]                                      
        ]  
    ]
];