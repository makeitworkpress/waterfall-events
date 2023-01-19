(function () {
    'use strict';

    var map = {
        config: {}, 
        clusters: {},
        markers: {},
        maps: {},
        initialize() {

            if( typeof(google) == 'undefined' || typeof(this.maps) == 'undefined' ) {
                return;
            }

            document.querySelectorAll('.wfe-map').forEach( (el) => {
                
                if( typeof(window['wfeMap' + el.id]) === 'undefined' ) {
                    return;
                }
                
                // Unique map configurations, moving the config inside the object
                this.config[el.id] = window['wfeMap' + el.id];

                // Set-up main functionalities
                this.createMap(el);
                this.addMarkers(el, this.config[el.id].markers, this.config[el.id].fit);
                this.addFilter(el, this.config[el.id].markers);

            });

        },

        /**
         * Creates the filter
         * @param {*} el The map element
         * @param {Array} markers The marker configurations related to the map element
         */
        addFilter(el, markers) {    
            el.querySelectorAll('select').forEach( (select) => {
                select.addEventListener('change', () => {
                    this.filterMarkers(el, markers);
                });
            });
        },

        /**
         * Creates the map
         * @param {*} el The map element
         */
        createMap(el) {

            this.maps[el.id] = new google.maps.Map( el.querySelector('.wfe-map-canvas'), {
                    center: this.config[el.id].center,
                    styles: this.config[el.id].styles,
                    zoom: this.config[el.id].zoom
                } 
            );
        },

        /**
         * Adds markers to the map and fit the bounds to the map
         * 
         * @param {Element Node} el The map element
         * @param {Object} markers The markers that need to be added
         * @param {Boolean} fitBoundaries Whether to fit the map or not
         * @returns {void}  
         */
        addMarkers(el, markers, fitBoundaries = false) {

            if( markers.length < 1 ) {
                return;
            }

            const mapBounds = new google.maps.LatLngBounds();
            this.markers[el.id] = [];

            // Add the markers
            markers.forEach( (marker) => {

                const markerPosition = new google.maps.LatLng(parseFloat(marker.lat), parseFloat(marker.lng));
                const mapsMarker = new google.maps.Marker({
                    animation: google.maps.Animation.DROP,
                    icon: marker.icon,
                    map: this.maps[el.id],
                    position: markerPosition
                });
                mapBounds.extend(markerPosition);

                // Initialize our infowindow
                const mapsInfoWindow = new google.maps.InfoWindow({
                    content: this.getInfoWindowContent(marker)
                });

                // Adds event listener for opening up the infowindow
                mapsMarker.addListener('click', () => {
                    mapsInfoWindow.open(this.maps[el.id], mapsMarker);   
                });

                // Pushes the markers to our array of markers
                this.markers[el.id].push(mapsMarker);

            } );

            // If the markercluster script exists, we cluster our markers
            if( typeof(MarkerClusterer) !== 'undefined' && this.config[el.id].cluster ) {
                console.log(this.config[el.id]);
                this.clusters[el.id] = new MarkerClusterer(
                    this.maps[el.id], 
                    this.markers[el.id],
                    { 
                        gridSize: this.config[el.id].clusterGridSize ? +this.config[el.id].clusterGridSize : 60,
                        imagePath: this.config[el.id].clusterIconPath ? this.config[el.id].clusterIconPath + 'm' : wfe.url + 'assets/img/m' 
                    } 
                );
            }

            // Fit the bounds of the maps to the markers
            if( fitBoundaries ) {
                this.maps[el.id].fitBounds(mapBounds);
            }

            // For single markers, bounds can be tight and the map is zoomed in too much. We want to decrease the zoom.
            const zoom = this.maps[el.id].getZoom();
            if( zoom > 15 ) {
                this.maps[el.id].setZoom(15);
            }

        },

        /**
         * Updates the markers, based upon filtering
         * 
         * @param {Node Element} el The parent element we're filtering for
         * @param {Array} markers The map markers
         */
        filterMarkers(el, markers) {

            let values = {};

            //  Get the values for all filters
            el.querySelectorAll('select').forEach( (field) => {
                if( field.value ) {
                    values[field.name] = field.value;
                }
            });

            // Clear all markers
            this.clearMarkers(el);        
            
            // Filter our markers for all fields that have values
            for( let filter in values ) {
                markers = markers.filter( (marker) => {
                    values[filter] = filter !== 'country' ? parseInt(values[filter]) : values[filter]; // .includes is type sensitive
                    return marker[filter].includes(values[filter]);
                });
            }

            // And add the filtered markers again; and always fit when filtering
            this.addMarkers(el, markers, true); 

        },

        /**
         * Clears all markers
         * 
         * @param {Node Element} el The current map element
         */ 
        clearMarkers(el) {

            // Clear markercluster
            if( typeof(this.clusters[el.id]) !== 'undefined' ) {
                this.clusters[el.id].clearMarkers();   
            }

            // Reset markers
            this.markers[el.id].forEach( (marker) => {
                marker.setMap(null);
            });
            this.markers[el.id] = [];

        },

        /**
         * Retrieves and formats the infowindow content for a marker
         * 
         * @param {object} marker The marker configuration object 
         */
        getInfoWindowContent(marker) {
            const infoWindow    = marker.infoWindow;
            const windowContent = `<div class="wfe-map-info-window">
            <h3><a href="${infoWindow.eventLink}" title="${infoWindow.title}">${infoWindow.title}</a></h3>
            ${infoWindow.categories.length > 0 ? '<div class="wfe-map-info-window-meta"><i class="fas fa-certificate"></i>' + infoWindow.categories.join(', ') + '</div>' : ''}
            ${infoWindow.tags.length > 0 ? '<div class="wfe-map-info-window-meta"><i class="fas fa-tags"></i>' + infoWindow.tags.join(', ') + '</div>' : ''}
            ${infoWindow.description ? '<p class="wfe-map-info-window-description">' + infoWindow.description + '</p>' : ''}
            <ul class="wfe-map-info-window-dates">
                ${infoWindow.dates.map( (date) => {

                    if( ! date.startDate ) {
                        return;
                    }

                    date.startDate ? new Date(parseInt(date.startDate) * 1000) : false;
                    let endDate = date.endDate ? new Date(parseInt(date.endDate) * 1000) : false;

                    return `<li>
                        <i class="far fa-calendar"></i>
                        ${date.title ? '<b>' + date.title + ':</b>': '' }
                        ${date.startDate}
                        ${date.startTime}
                        ${endDate || date.endTime ? ' - ' : ''}
                        ${date.endDate}
                        ${date.endTime}
                    </li>`;
                }).join('')}
            </ul>            
            <div class="wfe-map-info-window-locality">
                <i class="fas fa-map-marker"></i>
                ${infoWindow.locationName ? '<b>' + infoWindow.locationName + '</b>' : ''}
                ${infoWindow.street} ${infoWindow.number} ${infoWindow.city} ${infoWindow.country ? '<span>' + infoWindow.country + '</span>' : ''}
            </div>
            ${infoWindow.buttonLink ? '<a class="wfe-registration-btn primary atom-button" href="' + infoWindow.buttonLink + '" target="_blank">' + infoWindow.buttonLabel + '</a>' : ''}
        </div>`;

            return windowContent;
        }

    };

    var calendar = {
      calendars: {},  
      config: {}, 
      // Initialize our module
      initialize() {

        if( typeof(FullCalendar) == 'undefined' ) {
          return;
        }

        document.querySelectorAll('.wfe-calendar').forEach( (el) => {
                
          if( typeof(window['wfeCalendar' + el.id]) === 'undefined' ) {
              return;
          }
          
          // Unique map configurations, moving the config inside the object
          this.config[el.id] = window['wfeCalendar' + el.id];

          // Set-up main functionalities
          this.calendars[el.id] = this.createCalendar(el);

        });   

      },

      /**
       * Creates the calendar
       * @param {*} el The calendar element
       */
      createCalendar(el) {

        const calendar = new FullCalendar.Calendar(el, {
          headerToolbar: {
            start: 'prev next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          locale: this.config[el.id].locale ? this.config[el.id].locale : 'en',
          initialView: this.config[el.id].initialView ? this.config[el.id].initialView : 'dayGridMonth',
          events: this.config[el.id].events
        });

        calendar.render();

        return calendar;

      }

    };

    var calendarElementor = {
        initialize() {

            jQuery(window).on( 'elementor/frontend/init', () => {

                elementorFrontend.hooks.addAction( 'frontend/element_ready/wfe-calendar.default', ($element) => {

                    // This only should be applied on the Elementor front-end, not the real front-end
                    const urlParams = new URLSearchParams(window.location.search);
                    const preview = urlParams.get('elementor-preview');

                    if( ! preview ) {
                        return;
                    }

                    calendar.initialize();

                });

            });

        }
    };

    var mapElementor = {
        markers: {},
        maps: {},
        initialize() {

            jQuery(window).on( 'elementor/frontend/init', () => {

                elementorFrontend.hooks.addAction( 'frontend/element_ready/wfe-map.default', ($element) => {

                    // This only should be applied on the Elementor front-end, not the real front-end
                    const urlParams = new URLSearchParams(window.location.search);
                    const preview = urlParams.get('elementor-preview');

                    if( ! preview ) {
                        return;
                    }

                    map.initialize();

                });

            });

        }

    };

    /**
     * All modules are bundled into one application
     */

    const App = {
        components: {
            calendar, calendarElementor, map, mapElementor
        },     
        initialize: function() {
            for( let key in this.components ) {
                this.components[key].initialize();
            }
        }
    };

    // Boot our application after the document is ready
    document.addEventListener("DOMContentLoaded", () => App.initialize() );

}());
