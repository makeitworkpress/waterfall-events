import calendar from './calendar';

export default {
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