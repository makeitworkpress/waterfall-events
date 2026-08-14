import { getGlobalConfig } from '../utils';
import type { MapConfig, MapMarker, MarkerFilter, MarkerInfoWindow } from '../types';

/**
 * Renders the event maps, plots the event markers, clusters them and filters them
 * client-side on the already loaded marker set.
 */
export const map = {
    clusters: {} as Record<string, InstanceType<typeof MarkerClusterer>>,
    config: {} as Record<string, MapConfig>,
    maps: {} as Record<string, google.maps.Map>,
    markers: {} as Record<string, google.maps.Marker[]>,

    /**
     * Boots a map for every `.wfe-map` element that has a matching configuration.
     */
    initialize(): void {
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            return;
        }

        document.querySelectorAll<HTMLElement>('.wfe-map').forEach((el) => {
            const config = getGlobalConfig<MapConfig>(`wfeMap${el.id}`);

            if (!config) {
                return;
            }

            // Unique map configurations, moving the config inside the object
            this.config[el.id] = config;

            // Set-up main functionalities
            if (!this.createMap(el)) {
                return;
            }

            this.addMarkers(el, config.markers, Boolean(config.fit));
            this.addFilter(el, config.markers);
        });
    },

    /**
     * Listens to all filter fields within the map element.
     *
     * @param el      The map element.
     * @param markers The full, unfiltered marker set belonging to the map element.
     */
    addFilter(el: HTMLElement, markers: MapMarker[]): void {
        el.querySelectorAll('select').forEach((select) => {
            select.addEventListener('change', () => {
                this.filterMarkers(el, markers);
            });
        });
    },

    /**
     * Creates the map instance.
     *
     * @param el The map element.
     * @returns The map instance, or null when the element has no canvas to draw on.
     */
    createMap(el: HTMLElement): google.maps.Map | null {
        const canvas = el.querySelector<HTMLElement>('.wfe-map-canvas');

        if (!canvas) {
            return null;
        }

        const config = this.config[el.id];

        this.maps[el.id] = new google.maps.Map(canvas, {
            center: config.center,
            styles: config.styles,
            zoom: config.zoom,
        });

        return this.maps[el.id];
    },

    /**
     * Adds markers to the map and fits the bounds to those markers.
     *
     * @param el            The map element.
     * @param markers       The markers that need to be added.
     * @param fitBoundaries Whether to fit the map to the markers.
     */
    addMarkers(el: HTMLElement, markers: MapMarker[], fitBoundaries = false): void {
        if (markers.length < 1) {
            return;
        }

        const config = this.config[el.id];
        const instance = this.maps[el.id];
        const mapBounds = new google.maps.LatLngBounds();

        this.markers[el.id] = [];

        // Add the markers
        markers.forEach((marker) => {
            const markerPosition = new google.maps.LatLng(marker.lat, marker.lng);
            const mapsMarker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                icon: marker.icon,
                map: instance,
                position: markerPosition,
            });

            mapBounds.extend(markerPosition);

            // Initialize our infowindow
            const mapsInfoWindow = new google.maps.InfoWindow({
                content: this.getInfoWindowContent(marker),
            });

            // Adds event listener for opening up the infowindow
            mapsMarker.addListener('click', () => {
                mapsInfoWindow.open(instance, mapsMarker);
            });

            // Pushes the markers to our array of markers
            this.markers[el.id].push(mapsMarker);
        });

        // If the markercluster script exists, we cluster our markers
        if (typeof MarkerClusterer !== 'undefined' && config.cluster) {
            this.clusters[el.id] = new MarkerClusterer(instance, this.markers[el.id], {
                gridSize: config.clusterGridSize ? Number(config.clusterGridSize) : 60,
                imagePath: config.clusterIconPath
                    ? `${config.clusterIconPath}m`
                    : `${wfe.url}assets/img/m`,
            });
        }

        // Fit the bounds of the maps to the markers
        if (fitBoundaries) {
            instance.fitBounds(mapBounds);
        }

        // For single markers bounds can be tight and the map zooms in too much, so we cap it.
        const zoom = instance.getZoom();

        if (zoom !== undefined && zoom > 15) {
            instance.setZoom(15);
        }
    },

    /**
     * Updates the visible markers, based upon the active filters.
     *
     * @param el      The map element we are filtering for.
     * @param markers The full, unfiltered marker set.
     */
    filterMarkers(el: HTMLElement, markers: MapMarker[]): void {
        const values: Partial<Record<MarkerFilter, string>> = {};

        // Get the values for all filters
        el.querySelectorAll('select').forEach((field) => {
            if (field.value) {
                values[field.name as MarkerFilter] = field.value;
            }
        });

        // Clear all markers
        this.clearMarkers(el);

        // Filter our markers for all fields that have values
        let filtered = markers;

        (Object.keys(values) as MarkerFilter[]).forEach((filter) => {
            const value = values[filter];

            if (value === undefined) {
                return;
            }

            filtered = filtered.filter((marker) => this.matchesFilter(marker, filter, value));
        });

        // And add the filtered markers again; always fit the map when filtering
        this.addMarkers(el, filtered, true);
    },

    /**
     * Determines whether a marker matches a single filter value.
     *
     * Countries are compared as strings, whereas categories and tags hold term IDs and
     * therefore have to be compared numerically.
     *
     * @param marker The marker to test.
     * @param filter The filter that is applied.
     * @param value  The selected filter value.
     */
    matchesFilter(marker: MapMarker, filter: MarkerFilter, value: string): boolean {
        if (filter === 'country') {
            return marker.country.includes(value);
        }

        return marker[filter].includes(Number.parseInt(value, 10));
    },

    /**
     * Clears all markers and their cluster for a given map.
     *
     * @param el The current map element.
     */
    clearMarkers(el: HTMLElement): void {
        // Clear markercluster
        this.clusters[el.id]?.clearMarkers();

        // Reset markers
        (this.markers[el.id] ?? []).forEach((marker) => {
            marker.setMap(null);
        });

        this.markers[el.id] = [];
    },

    /**
     * Retrieves and formats the info window content for a marker.
     *
     * @param marker The marker configuration object.
     */
    getInfoWindowContent(marker: MapMarker): string {
        const infoWindow: MarkerInfoWindow = marker.infoWindow;

        return `<div class="wfe-map-info-window">
            <h3><a href="${infoWindow.eventLink}" title="${infoWindow.title}">${infoWindow.title}</a></h3>
            ${infoWindow.categories.length > 0 ? '<div class="wfe-map-info-window-meta"><i class="fas fa-certificate"></i>' + infoWindow.categories.join(', ') + '</div>' : ''}
            ${infoWindow.tags.length > 0 ? '<div class="wfe-map-info-window-meta"><i class="fas fa-tags"></i>' + infoWindow.tags.join(', ') + '</div>' : ''}
            ${infoWindow.description ? '<p class="wfe-map-info-window-description">' + infoWindow.description + '</p>' : ''}
            <ul class="wfe-map-info-window-dates">
                ${infoWindow.dates
                    .filter((date) => date.startDate)
                    .map(
                        (date) => `<li>
                        <i class="far fa-calendar"></i>
                        ${date.title ? '<b>' + date.title + ':</b>' : ''}
                        ${date.startDate}
                        ${date.startTime}
                        ${date.endDate || date.endTime ? ' - ' : ''}
                        ${date.endDate}
                        ${date.endTime}
                    </li>`,
                    )
                    .join('')}
            </ul>
            <div class="wfe-map-info-window-locality">
                <i class="fas fa-map-marker"></i>
                ${infoWindow.locationName ? '<b>' + infoWindow.locationName + '</b>' : ''}
                ${infoWindow.street} ${infoWindow.number} ${infoWindow.city} ${infoWindow.country ? '<span>' + infoWindow.country + '</span>' : ''}
            </div>
            ${infoWindow.buttonLink ? '<a class="wfe-registration-btn primary atom-button" href="' + infoWindow.buttonLink + '" target="_blank">' + infoWindow.buttonLabel + '</a>' : ''}
        </div>`;
    },
};
