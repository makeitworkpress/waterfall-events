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
                        'description'   => __('Full path to cluster icons for use in event maps. Icons in here should be named m1.png, m2.png, m3.png, m4.png, m5.png.', 'wfe'),
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

if( is_super_admin() && is_multisite() ) {
    $options['fields']['sections']['events_general']['fields'][] = [
        'columns'       => 'half',
        'id'            => 'events_enable_syncing',
        'title'         => __('Enable Multisite Events Syncing', 'wfe'),                    
        'description'   => __('Allows to sync events throughout sites, adding additional options in the "Advanced" tab when editing an event.', 'wfe'),
        'type'          => 'checkbox',  
        'single'        => true,                      
        'style'         => 'switcher switcher-enable',
        'options'       => [
            'enable'        => ['label' => __('Enable Events Syncing', 'wfe')]
        ]                        
    ];  
    $options['fields']['sections']['events_general']['fields'][] = [
        'columns'       => 'half',
        'id'            => 'events_calendar_multisite_source',
        'title'         => __('Enable Multisite Events Calendar', 'wfe'),                    
        'description'   => __('Allows to load all events from the whole multisite network in the events calendar.', 'wfe'),
        'type'          => 'checkbox',  
        'single'        => true,                      
        'style'         => 'switcher switcher-enable',
        'options'       => [
            'enable'        => ['label' => __('Enable Multisite Events in Calendar', 'wfe')]
        ]                        
    ];    
}