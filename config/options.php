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
                        'description'   => __('The global currency for events, applied to all events by default', 'wfe'),
                        'id'            => 'events_currency',
                        'title'         => __('Global Currency for Events', 'wfe'),
                        'type'          => 'input'                        
                    ]           
                ]
            ]                                       
        ]                    
    ]
];