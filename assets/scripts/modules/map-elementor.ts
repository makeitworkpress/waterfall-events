import { map } from './map';
import { isElementorPreview } from '../utils';

/**
 * Re-initializes the map inside the Elementor editor preview, where widgets are
 * rendered through AJAX and therefore miss the regular DOMContentLoaded boot.
 */
export const mapElementor = {
    initialize(): void {
        if (typeof jQuery === 'undefined') {
            return;
        }

        jQuery(window).on('elementor/frontend/init', () => {
            if (typeof elementorFrontend === 'undefined') {
                return;
            }

            elementorFrontend.hooks.addAction('frontend/element_ready/wfe-map.default', () => {
                // This should only be applied within the Elementor preview, not the real front-end
                if (!isElementorPreview()) {
                    return;
                }

                map.initialize();
            });
        });
    },
};
