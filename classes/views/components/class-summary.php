<?php
/**
 * Displays the summary of a single review, including advantages and disadvantages
 */
namespace Waterfall_Events\Views\Components;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Summary extends Component {

    protected function initialize() {

        // Parameters
        $this->params = wp_parse_args( $this->params, [
            'dates'     => true,
            'post_id'   => 0
        ] );

    }

    /**
     * Formats the details
     */
    protected function format() {

        if( ! $this->params['post_id'] ) {
            global $post;
            $this->params['post_id'] = $post->ID;
        }
        
        // Description
        $this->props['description'] = get_post_meta($this->params['post_id'], 'wfe_description', true);

        // Registration part
        $registration               = get_post_meta($this->params['post_id'], 'wfe_registration', true);
        $registration_label         = get_post_meta($this->params['post_id'], 'wfe_registration_label', true);   
        
        if( $registration ) {
            $this->props['register'] = wpc_atom('button', [
                'attributes'    => ['class' => 'wfe-registration-btn primary', 'href' => get_post_meta($this->params['post_id'], 'wfe_registration', true), 'target' => '_blank'], 
                'label'         => $registration_label ? $registration_label : $this->params['register_label']
            ], false);
        }  
        
        if( $this->params['dates'] ) {
            $this->props['dates'] = new Dates(['summarize' => true]);
        }

    }

}