<?php
/**
 * Displays all the events in a calendar view (using FullCalendar.io)
 * 
 * @todo Add a filter functionality
 */
namespace Waterfall_Events\Views\Components;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Calendar extends Component {

  /**
   * Initialize the component
   */  
  protected function initialize() {

      $this->params = wp_parse_args( $this->params, [
        'display' => 'month',   // month, week, day or list
        'locale'  => '',        // Add a custom language to load locales (use locales supported by fullcalendar, e.g. fr, nl)
        'id'      => uniqid(),  // Unique ID for the map
      ] );

  }
  
  /**
   * Formats the details
   */
  protected function format() {

    // Simple Props
    $this->props['id'] = $this->params['id'];
    $this->props['locale'] = $this->params['locale'] ? $this->params['locale'] : substr( get_locale(), 0, 2);

    switch($this->params['display']) {
      case 'month';
        $this->props['view'] = 'dayGridMonth';
        break;
      case 'week';
        $this->props['view'] = 'timeGridWeek';
        break;
      case 'day';
        $this->props['view'] = 'timeGridDay';
        break;        
      case 'list';
        $this->props['view'] = 'listWeek';
        break;                
    }

    /**
     * Retrieves all events through a monstreous query
     * This will work alright, unless you have thousands of events
     * 
     * @todo Optimize, retrieve events per month
     */
    $events = get_posts([
      'fields'          => 'ids',
      'posts_per_page'  => -1,
      'post_status'     => 'publish',
      'post_type'       => 'events'
    ]);

    // Format our events
    if( $events && is_array($events) ) {
      foreach( $events as $event_id ) {

        $type   = get_post_meta( $event_id, 'wfe_type', true );
        $title  = get_the_title( $event_id );
        $link   = esc_url( get_the_permalink($event_id) );

        if( $type === 'multiday') {

          $days = get_post_meta( $event_id, 'wfe_multiday_date', true );

          if( $days && is_array($days) ) {
            foreach( $days as $day ) {
              $this->props['events'][] = [
                'id'    => $event_id,
                'title' => $day['title'] ? $day['title'] : $title,
                'start' => $this->format_date($day['date'], $day['starttime']),
                'end'   => $this->format_date($day['date'], $day['endtime']),
                'url'   => $link
              ];            
            }
          }

        } else {

          $start_date = get_post_meta( $event_id, 'wfe_startdate', true );
          $start_time = get_post_meta( $event_id, 'wfe_starttime', true );
          $end_date = get_post_meta( $event_id, 'wfe_enddate', true );
          $end_time = get_post_meta( $event_id, 'wfe_endtime', true );

          $this->props['events'][] = [
            'id'    => $event_id,
            'title' => $title,
            'start' => $this->format_date($start_date, $start_time),
            'end'   => $this->format_date($end_date, $end_time),
            'url'   => $link
          ]; 
        }


      }
    }    

    if( ! wp_script_is('wfe-fullcalendar') ) {
      wp_enqueue_script('wfe-fullcalendar'); 
    }  
    
    if( ! wp_script_is('wfe-fullcalendar-locales') && $this->props['locale'] !== 'en' ) {
      wp_enqueue_script('wfe-fullcalendar-locales');
    }      

    /**
     * Adds the map settings to the footer as a script. This allows the general JS to pickup these settings and create a custom calendar
     */
    add_action('wp_footer', [$this, 'echo_config_JS']);      

  }

  /** 
   * Echo our custom variables
   */
  public function echo_config_JS() {

    echo '<script type="text/javascript" id="wfe-calendar-script-' . $this->props['id'] . '">
        var wfeCalendar' . $this->props['id'] . ' = {
          locale: "' . $this->props['locale'] . '",
          initialView: "' . $this->props['view'] . '",
          events: ' . json_encode($this->props['events']) . '
        };
    </script>';
    
  } 
  
  /**
   * Formats our date to a format FullCalendar understands
   * 
   * @param string $stamp The timestamp
   * @param string $time  The time, formatted 00:00 (no milliseconds)
   * 
   * @return string $date The formatted date
   */
  private function format_date( $stamp, $time ) {

    $date = '';

    if( ! $stamp ) {
      return $date;
    }

    $date = date('Y-m-d', $stamp);  

    if( $time ) {
      $date .= 'T' . $time . ':00'; 
    }

    return $date;

  }

}