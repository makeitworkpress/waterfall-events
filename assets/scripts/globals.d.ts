/**
 * Ambient type declarations for the globals that WordPress, the Waterfall theme and
 * companion libraries expose at runtime.
 *
 * None of these are part of the bundle - they are enqueued separately - so we only
 * describe their shape here instead of importing them.
 */
export {};

/** Data localized onto the page through `wp_localize_script`, under the `wfe` name. */
interface WfeLocalized {
    ajaxUrl: string;
    debug: boolean;
    nonce: string;
    url: string;
}

/**
 * Minimal surface of the FullCalendar 5 global bundle that we rely on.
 * @see assets/vendor/fullcalendar/main.min.js
 */
interface FullCalendarOptions {
    events: unknown[];
    headerToolbar: {
        center: string;
        end: string;
        start: string;
    };
    initialView: string;
    locale: string;
}

interface FullCalendarInstance {
    destroy(): void;
    render(): void;
}

interface FullCalendarStatic {
    Calendar: new (el: HTMLElement, options: FullCalendarOptions) => FullCalendarInstance;
}

/**
 * Minimal surface of MarkerClustererPlus.
 * @see assets/vendor/markercluster/index.min.js
 */
interface MarkerClustererOptions {
    gridSize?: number;
    imagePath?: string;
    maxZoom?: number;
}

interface MarkerClustererInstance {
    clearMarkers(): void;
    repaint(): void;
}

declare global {
    const wfe: WfeLocalized;

    const FullCalendar: FullCalendarStatic;

    const MarkerClusterer: new (
        map: google.maps.Map,
        markers: google.maps.Marker[],
        options?: MarkerClustererOptions,
    ) => MarkerClustererInstance;

    /** WordPress data / apiFetch helpers, available inside the block editor. */
    const wp: any;

    /** jQuery is provided by WordPress and used for the Elementor and Select2 bridges. */
    const jQuery: any;

    /** Registered by Elementor on the front-end and inside the editor preview. */
    const elementorFrontend: {
        hooks: {
            addAction(name: string, callback: (element?: unknown) => void): void;
        };
    };
}
