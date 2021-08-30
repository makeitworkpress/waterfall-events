<?php
/**
 * Displays an in between section with a document, for use in the articles itself
 */
namespace Waterfall_Events\Views\Elementor;
use Elementor as Elementor;
use Elementor\Controls_Manager as Controls_Manager;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Map extends Elementor\Widget_Base {
	
	/**
	 * Loads custom front-end script for Elementor
	 *
	 * @return array Script handles
	 */		
	public function get_script_depends() {
    return ['google-maps-js', 'wfe-markercluster', 'wfe-scripts'];
	}   	

	/**
	 * Retrieves the name for the widget
	 *
	 * @return string Widget name
	 */
	public function get_name() {
		return 'wfe-map';
	}

	/**
	 * Set the custom title for the widget
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( 'Events Map', 'wfe' );
    }
    
    /**
	 * Name for the icon used
	 *
	 * @return string Widget icon
     */
	public function get_icon() {
		return 'eicon-google-maps';
	}

    
    /**
	 * Name for the category used
	 *
	 * @return string Category name
     */	
	public function get_categories() {
		return ['waterfall-widgets'];
	}	
    
	/**
	 * Registers the custom widget controls. 
	 */
	protected function _register_controls() {

        /**
         * Elements
         */
        $this->start_controls_section( 
            'section_elements',
            [
                'label' => esc_html__( 'Map Settings', 'wfe' ),
            ]
		);
		
		$this->add_control(
			'latitude',
			[
				'label'     	=> __( 'Latitude', 'wfe' ),
				'description'   => __( 'The default latitude for the center of map. You can use google maps to determine the desired latitude.', 'wfe' ),
				'type'      	=> Controls_Manager::TEXT,
				'default'   	=> 52.090736,
			]
		);
		
		$this->add_control(
			'longitude',
			[
				'label'     	=> __( 'Longitude', 'wfe' ),
				'description'   => __( 'The default longitude for the center of map. You can use google maps to determine the desired longitude.', 'wfe' ),
				'type'      	=> Controls_Manager::TEXT,
				'default'   	=> 5.121420,
			]
		); 
		
		$this->add_control(
			'fit',
			[
				'label'     	=> __( 'Fit Map to Markers', 'wfe' ),
				'description'   => __( 'Fits the map to existing markers, ignoring other settings.', 'wfe' ),
				'type'      	=> Controls_Manager::SWITCHER,
				'default'   	=> '',
				'label_on'  	=> __( 'Yes', 'wfe' ),
				'label_off' 	=> __( 'No', 'wfe' ),
				'separator' 	=> 'before'
			]
		);
		
		$this->add_control(
			'cluster',
			[
				'label'     	=> __( 'Cluster Map Markers', 'wfe' ),
				'description'   => __( 'Clusters map markers. Useful if you have many markers close to each other.', 'wfe' ),
				'type'      	=> Controls_Manager::SWITCHER,
				'default'   	=> '',
				'label_on'  	=> __( 'Yes', 'wfe' ),
				'label_off' 	=> __( 'No', 'wfe' ),
				'separator' 	=> 'before'
			]
		);	
		
		$this->add_control(
			'clustersize',
			[
				'label'     	=> __( 'Grid Size', 'wfe' ),
				'description'   => __( 'The grid size for clusters. The larger the size, the sooner markers will cluster.', 'wfe' ),
				'type'      	=> Controls_Manager::SLIDER,
				'default'   	=> ['size' => 60],
				'size_units'	=> [''],
				'range'   		=> ['min' => 0, 'max' => 100, 'step' => 1],
				'condition' => [
					'cluster' => 'yes'
				],				
			]
		); 		
		
		$this->add_control(
			'zoom',
			[
				'label'     	=> __( 'Zoom Level', 'wfe' ),
				'description'   => __( 'The default zoom level of the map', 'wfe' ),
				'type'      	=> Controls_Manager::SELECT,
				'default'   	=> 11,
				'options'   	=> range(0, 19)
			]
		); 	
	
        $this->end_controls_section();

        $this->start_controls_section( 
            'section_general',
            [
                'label' => esc_html__( 'Filters', 'wfe' ),
            ]
		);

		foreach( ['country' => __('Countries', 'wfe'), 'categories' => __('Categories', 'wfe'), 'tags' => __('Tags', 'wfe')] as $key => $label ) {
		
			$this->add_control(
				$key,
				[
					'label'     	=> sprintf( __( '%s Filter', 'wfe' ), $label ),
					'type'      	=> Controls_Manager::SWITCHER,
					'default'   	=> '',
					'label_on'  	=> __( 'Yes', 'wfe' ),
					'label_off' 	=> __( 'No', 'wfe' ),
					'separator' 	=> 'before'
				]
			);
			
			$this->add_control(
				$key . '_label',
				[
					'label'     	=> sprintf( __( '%s Filter Text', 'wfe' ), $label ),
					'description'   => sprintf( __( 'The text if you want to select all %s', 'wfe' ), $label ),
					'type'      	=> Controls_Manager::TEXT,
					'default'   	=> sprintf( __( 'All %s', 'wfe' ), $label ),
					'condition' => [
						$key . '[value]!' => ''
					]					
				]
			); 			

		}
		
		$this->end_controls_section();

        $this->start_controls_section( 
            'section_style',
            [
                'label' => esc_html__( 'Styling', 'wfe' ),
				'tab'   => Controls_Manager::TAB_STYLE
            ]
		);

		$this->add_control(
			'height',
			[
				'label' 		=> __( 'Map Height', 'wfe' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range' => ['px' => ['min' => 0, 'max' => 1000]],
				'selectors' 	=> [
					'{{WRAPPER}} .wfe-map-canvas' => 'height: {{SIZE}}{{UNIT}};',
				]		
			]
		);		

		$this->add_control(
			'border_radius',
			[
				'label' 		=> __( 'Map Border Radius', 'wfe' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wfe-map-canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]		
			]
		);

		$this->add_control(
			'styles',
			[
				'label'     	=> __( 'Custom Map Styling', 'wfe' ),
				'description'   => __( 'Optional map styling, for example generated by Snazzy Maps', 'wfe' ),
				'type'      	=> Controls_Manager::CODE,
				'default'   	=> '',
			]
		);		

		$this->end_controls_section();

    }
	
	/**
	 * Renders the output of the widget
	 */
	protected function render() {
		
		$filters	= [];
		$settings 	= $this->get_settings();

		// Set-up our filters
		foreach( ['country', 'categories', 'tags'] as $filter ) {
			if( ! $settings[$filter] ) {
				continue;
			}
			$filters[$filter] = $settings[$filter . '_label'];
		}
		
		// Render our map
		$map = new \Waterfall_Events\Views\Components\Map( [
			'center' 		=> ['lat' => (float) $settings['latitude'], 'lng' => (float) $settings['longitude']],
			'cluster'		=> $settings['cluster'],
			'clustersize'	=> $settings['clustersize']['size'],
			'fit'			=> $settings['fit'],
			'filters'  		=> $filters,
			'styles'		=> $settings['styles'],
			'zoom'			=> $settings['zoom']
		] );
		$map->render();

		// Adds the script tags to expose our configurations to elementor
		if( \Waterfall_Events\Helper::is_elementor_editor() ) {
			$map->echo_config_JS();
		}
    
  }

	/**
	 * Render output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {}
		
	/**
	 * Render as plain content.
	 *
	 * Override the default render behavior, don't render sidebar content.
	 *
	 * @access public
	 */
	public function render_plain_content() {}    

}        