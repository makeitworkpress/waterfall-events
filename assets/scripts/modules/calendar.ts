import { getGlobalConfig } from '../utils';
import type { CalendarConfig } from '../types';

/**
 * Renders a FullCalendar instance for every calendar element on the page.
 */
export const calendar = {
    calendars: {} as Record<string, InstanceType<typeof FullCalendar.Calendar>>,
    config: {} as Record<string, CalendarConfig>,

    /**
     * Boots a calendar for every `.wfe-calendar` element that has a matching configuration.
     */
    initialize(): void {
        if (typeof FullCalendar === 'undefined') {
            return;
        }

        document.querySelectorAll<HTMLElement>('.wfe-calendar').forEach((el) => {
            const config = getGlobalConfig<CalendarConfig>(`wfeCalendar${el.id}`);

            if (!config) {
                return;
            }

            // Unique calendar configurations, moving the config inside the object
            this.config[el.id] = config;

            // Set-up main functionalities
            this.calendars[el.id] = this.createCalendar(el, config);
        });
    },

    /**
     * Creates and renders the calendar.
     *
     * @param el     The calendar element.
     * @param config The configuration belonging to the calendar element.
     */
    createCalendar(el: HTMLElement, config: CalendarConfig): InstanceType<typeof FullCalendar.Calendar> {
        const instance = new FullCalendar.Calendar(el, {
            headerToolbar: {
                start: 'prev next today',
                center: 'title',
                end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            },
            locale: config.locale ? config.locale : 'en',
            initialView: config.initialView ? config.initialView : 'dayGridMonth',
            events: config.events,
        });

        instance.render();

        return instance;
    },
};
