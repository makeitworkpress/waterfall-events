import map from './map';

export default {
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