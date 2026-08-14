import { calendar } from './calendar';
import { isElementorPreview } from '../utils';

/**
 * Re-initializes the calendar inside the Elementor editor preview, where widgets are
 * rendered through AJAX and therefore miss the regular DOMContentLoaded boot.
 */
export const calendarElementor = {
    initialize(): void {
        if (typeof jQuery === 'undefined') {
            return;
        }

        jQuery(window).on('elementor/frontend/init', () => {
            if (typeof elementorFrontend === 'undefined') {
                return;
            }

            elementorFrontend.hooks.addAction(
                'frontend/element_ready/wfe-calendar.default',
                () => {
                    // This should only be applied within the Elementor preview, not the real front-end
                    if (!isElementorPreview()) {
                        return;
                    }

                    calendar.initialize();
                },
            );
        });
    },
};
