<?php
/**
 * Displays the details for an event
 */
namespace Waterfall_Events\Views\Components;
use WP_Post as WP_Post;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Dates extends Component {

    protected function initialize() {

        $this->params = wp_parse_args( $this->params, [
            'id'        => 0, 
            'separator' => '-',
            'summarize' => false, // Summarize multiday events
        ] );

    }

    
    /**
     * Formats the details
     */
    protected function format() {

        if( ! $this->params['id'] ) {
            global $post;
            $this->params['id'] = $post->ID;
        }

        // Separator and class
        $this->props['class'] = $this->params['summarize'] ? 'wfe-dates-summarize' : 'wfe-dates-default';
        $this->props['separator'] = $this->params['separator'];

        // Retrieve dates
        $format = get_option('date_format_custom') ? get_option('date_format_custom') : get_option('date_format');

        switch( get_post_meta($this->params['id'], 'wfe_type', true) ) {
            case 'multiday':
                $dates = (array) maybe_unserialize( get_post_meta($this->params['id'], 'wfe_multiday_date', true) );
                foreach( $dates as $date ) {
                    $this->props['dates'][] = [
                        'endDate'   => '', 
                        'endTime'   => $date['endtime'], 
                        'startDate' => $date['date'] ? wp_date($format, $date['date']) : '',                      
                        'startTime' => $date['starttime'],
                        'title'     => $date['title']
                    ];
                }

                // Summarizes a multiday event
                $date_length = count($dates);
                if( $this->params['summarize'] && $date_length > 1 ) {
                    $this->props['dates'] = [
                        $this->props['dates'][0],
                        $this->props['dates'][($date_length - 1)]
                    ];
                }
                break;
            default:
                $end_date = get_post_meta($this->params['id'], 'wfe_enddate', true);
                $start_date = get_post_meta($this->params['id'], 'wfe_startdate', true);
                $this->props['dates'] = [
                    [
                        'endDate'   => $end_date ? wp_date($format, $end_date) : '',
                        'endTime'   => get_post_meta($this->params['id'], 'wfe_endtime', true),                       
                        'startDate' => $start_date ? wp_date($format, $start_date) : '',
                        'startTime' => get_post_meta($this->params['id'], 'wfe_starttime', true),
                        'title'     => ''
                    ]
                ];
        }

    }

}