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
            'separator' => '-'
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

        // Separator
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
                break;
            default:
                $endDate = get_post_meta($this->params['id'], 'wfe_enddate', true);
                $startDate = get_post_meta($this->params['id'], 'wfe_startdate', true);
                $this->props['dates'] = [
                    [
                        'endDate'   => $endDate ? wp_date($format, $endDate) : '',
                        'endTime'   => get_post_meta($this->params['id'], 'wfe_endtime', true),                       
                        'startDate' => $startDate ? wp_date($format, $startDate) : '',
                        'startTime' => get_post_meta($this->params['id'], 'wfe_starttime', true),
                        'title'     => ''
                    ]
                ];
        }

    }

}