/**
 * Shapes of the configuration objects that the PHP components print into the footer.
 *
 * The map component prints `wfeMap{id}` (see Views\Components\Map::echo_config_JS) and the
 * calendar component prints `wfeCalendar{id}` (see Views\Components\Calendar::echo_config_JS).
 * Keep these definitions in sync with those methods.
 */

/** A single date row, as built by Views\Components\Dates. All values are pre-formatted strings. */
export interface EventDate {
    endDate: string;
    endTime: string;
    startDate: string;
    startTime: string;
    title: string;
}

/** Content shown inside the info window of a map marker. */
export interface MarkerInfoWindow {
    buttonLabel: string;
    buttonLink: string;
    categories: string[];
    city: string;
    country: string;
    dates: EventDate[];
    description: string;
    eventLink: string;
    locationName: string;
    number: string;
    postalCode: string;
    street: string;
    tags: string[];
    title: string;
}

/** A single event location plotted on the map. Categories and tags hold term IDs. */
export interface MapMarker {
    categories: number[];
    country: string;
    icon: string;
    infoWindow: MarkerInfoWindow;
    lat: number;
    lng: number;
    tags: number[];
}

/**
 * The map configuration. Note that `cluster`, `fit`, `clusterGridSize` and `clusterIconPath`
 * arrive as strings, because they originate from Elementor switcher and slider controls.
 */
export interface MapConfig {
    center: google.maps.LatLngLiteral;
    cluster: boolean | string;
    clusterGridSize: string;
    clusterIconPath: string;
    fit: boolean | string;
    markers: MapMarker[];
    styles: google.maps.MapTypeStyle[];
    zoom: number;
}

/** The names of the marker properties that can be filtered on. */
export type MarkerFilter = 'categories' | 'country' | 'tags';

/** A single event as understood by FullCalendar. */
export interface CalendarEvent {
    end: string;
    id: number;
    start: string;
    title: string;
    url: string;
}

/** The calendar configuration. */
export interface CalendarConfig {
    events: CalendarEvent[];
    initialView: string;
    locale: string;
}
