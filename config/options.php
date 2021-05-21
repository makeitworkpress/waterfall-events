<?php
/**
 * Our options page additional configurations
 */
defined( 'ABSPATH' ) or die('Go eat veggies!');

$options = [
    'frame'     => 'options',
    'fields'    => [              
        'sections'      => [
            'events_general' => [
                'icon'      => 'calendar_view_day',
                'id'        => 'events_general_section',
                'title'     => __('Events', 'wfe'),
                'fields'    => [
                    [
                        'columns'       => 'half',
                        'description'   => __('The global currency for events, applied to all events by default', 'wfe'),
                        'id'            => 'events_currency',
                        'title'         => __('Global Currency for Events', 'wfe'),
                        'type'          => 'input'                        
                    ],
                    [
                        'columns'       => 'half',
                        'description'   => __('Full path to cluster icons. Icons in here should be named m1.png, m2.png, m3.png, m4.png, m5.png.', 'wfe'),
                        'id'            => 'events_cluster_icon_path',
                        'title'         => __('Cluster Icons Custom Path', 'wfe'),
                        'type'          => 'input',                        
                        'subtype'       => 'url'                    
                    ],                            
                ]
            ]                                       
        ]                    
    ]
];