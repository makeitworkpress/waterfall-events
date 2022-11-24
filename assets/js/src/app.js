/**
 * All modules are bundled into one application
 */
import map from './modules/map';
import calendar from './modules/calendar';
import calendarElementor from './modules/calendar-elementor';
import mapElementor from './modules/map-elementor';

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