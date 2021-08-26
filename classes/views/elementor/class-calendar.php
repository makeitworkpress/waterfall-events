<?php
/**
 * Displays an in between section with a document, for use in the articles itself
 */
namespace Waterfall_Events\Views\Elementor;
use Elementor as Elementor;
use Elementor\Controls_Manager as Controls_Manager;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Calendar extends Elementor\Widget_Base {

	/**
	 * Loads custom front-end script for Elementor
	 *
	 * @return array Script handles
	 */		
	// public function get_script_depends() {
  //   return ['wfe-fullcalendar', 'wfe-fullcalendar-locales', 'wfe-scripts'];
	// }    

	/**
	 * Retrieves the name for the widget
	 *
	 * @return string Widget name
	 */
	public function get_name() {
		return 'wfe-events';
	}

	/**
	 * Set the custom title for the widget
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( 'Events Calendar', 'wfe' );
    }
    
    /**
	 * Name for the icon used
	 *
	 * @return string Widget icon
     */
	public function get_icon() {
		return 'eicon-archive-posts';
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
     * Settings Tab
     */

    // General Settings
    $this->start_controls_section( 
      'section_display',
      [
        'label' => esc_html__( 'Display', 'wfe' ),
      ]
		);

		$this->add_control(
			'view',
			[
				'label'     	  => __( 'Initial Display Style', 'wfe' ),
				'description'   => __( 'Determines the initial display style of the events calendar.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'month',
				'options'  	    => [
          'month' => __('Month', 'wfe'),
          'week'  => __('Week', 'wfe'),
          'day'   => __('Day', 'wfe'),
          'list'  => __('List', 'wfe')
        ]
			]
		);
    
    $this->end_controls_section();

    $this->start_controls_section( 
      'section_event_style',
      [
        'label' => esc_html__( 'Header Styling', 'wfe' ),
        'tab'   => Controls_Manager::TAB_STYLE
      ]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar-title' => 'color: {{VALUE}};',
				],
			]
		); 
    
		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar-title' => 'color: {{VALUE}};',
				],
			]
		); 
    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __('Title Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-toolbar-title',
			]
		);

    // PROCESS:

		$this->add_group_control(
      \Elementor\Group_Control_Background::get_type(),
			[
        'name'      => 'event_background',
        'label' 		=> __( 'Event Background', 'wfe' ),
        'types'     => ['classic', 'gradient'],
				'selector'  => '{{WRAPPER}} .wfe-events .molecule-post'	
			]
		);      

		$this->add_control(
			'event_height',
			[
				'label' 		=> __( 'Event Minimum Height', 'wfe' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range'     => ['px' => ['min' => 0, 'max' => 1000]],
				'selector' 	=> [
					'{{WRAPPER}} .wfe-events .molecule-post' => 'min-height: {{SIZE}}{{UNIT}};',
				]		
			]
		);
    
		$this->add_control(
			'event_padding',
			[
				'label' 		=> __( 'Event Padding', 'wfe' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wfe-events .molecule-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]		
			]
		);    
    
		$this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
			[
        'name'      => 'event_border',
        'label' 		=> __( 'Event Border', 'wfe' ),
				'selector'  => '{{WRAPPER}} .wfe-events .molecule-post'	
			]
		);    

		$this->add_control(
			'event_border_radius',
			[
				'label' 		  => __( 'Event Border Radius', 'wfe' ),
				'type' 			  => Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wfe-events .molecule-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]		
			]
		);

    $this->end_controls_section();
    
    // Image
    $this->start_controls_section( 
      'section_image_style',
      [
        'label' => esc_html__( 'Events', 'wfe' ),
        'tab'   => Controls_Manager::TAB_STYLE
      ]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     	  => __( 'Title Tag', 'wfe' ),
				'description'   => __( 'HTML Title Tag used for event titles.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'h3',
				'options'  	    => [
          'h1'    => __('H1', 'wfe'),
          'h2'    => __('H2', 'wfe'),
          'h3'    => __('H3', 'wfe'),
          'h4'    => __('H4', 'wfe'),
          'h5'    => __('H5', 'wfe'),
          'h6'    => __('H6', 'wfe')
        ]
			]
		);
    
		$this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Excerpt Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wfe-events .entry-content' => 'color: {{VALUE}};',
				],
        'separator' 	  => 'before'
			]
		);    

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'excerpt_typography',
				'label'     => __('Excerpt Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .wfe-events .entry-content',
			]
		);

		$this->add_control(
			'details_color',
			[
				'label'     => __( 'Details Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wfe-events .entry-footer' => 'color: {{VALUE}};',
				],
        'separator' 	  => 'before'
			]
		);
    
		$this->add_control(
			'details_link_color',
			[
				'label'     => __( 'Details Link Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wfe-events .entry-footer a' => 'color: {{VALUE}};',
				],
			]
		);    
    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'details_typography',
				'label'     => __('Details Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .wfe-events .entry-footer',
			]
		);     
    
    $this->end_controls_section();   

  }
	
	/**
	 * Renders the output of the widget
	 */
	protected function render() {
		
		$settings = $this->get_settings();

    $calendar   = new \Waterfall_Events\Views\Components\Events([
      'display' => $settings['view']
    ]);
    $calendar->render();
    
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