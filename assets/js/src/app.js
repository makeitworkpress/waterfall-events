/**
 * All modules are bundled into one application
 */
import map from './modules/map';
import mapElementor from './modules/map-elementor';

const App = {
    components: {
        map, mapElementor
    },     
    initialize: function() {
        for( let key in this.components ) {
            this.components[key].initialize();
        }
    }
};

// Boot our application after the document is ready
document.addEventListener("DOMContentLoaded", () => App.initialize() );