/**
 * Entry point for the Waterfall Events front-end.
 *
 * All modules are bundled together and booted once the document is ready.
 */
import { ready } from './utils';
import { calendar } from './modules/calendar';
import { calendarElementor } from './modules/calendar-elementor';
import { map } from './modules/map';
import { mapElementor } from './modules/map-elementor';

const App = {
    components: { calendar, calendarElementor, map, mapElementor },
    initialize(): void {
        Object.values(this.components).forEach((component) => component.initialize());
    },
};

// Boot our application after the document is ready
ready(() => App.initialize());
