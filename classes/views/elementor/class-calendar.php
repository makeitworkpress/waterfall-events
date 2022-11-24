<?php

/**
 * Displays an in between section with a document, for use in the articles itself
 */

namespace Waterfall_Events\Views\Elementor;

use Elementor as Elementor;
use Elementor\Controls_Manager as Controls_Manager;

defined('ABSPATH') or die('Go eat veggies!');

class Calendar extends Elementor\Widget_Base
{

	/**
	 * Loads custom front-end script for Elementor
	 *
	 * @return array Script handles
	 */
	public function get_script_depends()
	{
		return ['wfe-fullcalendar', 'wfe-fullcalendar-locales', 'wfe-scripts'];
	}

	/**
	 * Retrieves the name for the widget
	 *
	 * @return string Widget name
	 */
	public function get_name()
	{
		return 'wfe-calendar';
	}

	/**
	 * Set the custom title for the widget
	 *
	 * @return string Widget title
	 */
	public function get_title()
	{
		return __('Events Calendar', 'wfe');
	}

	/**
	 * Name for the icon used
	 *
	 * @return string Widget icon
	 */
	public function get_icon()
	{
		return 'eicon-archive-posts';
	}


	/**
	 * Name for the category used
	 *
	 * @return string Category name
	 */
	public function get_categories()
	{
		return ['waterfall-widgets'];
	}

	/**
	 * Registers the custom widget controls. 
	 */
	protected function register_controls()
	{

		/**
		 * Settings Tab
		 */

		// General Settings
		$this->start_controls_section(
			'section_display',
			[
				'label' => esc_html__('Display', 'wfe'),
			]
		);

		$this->add_control(
			'view',
			[
				'label'     	  => __('Initial Display Style', 'wfe'),
				'description'   => __('Determines the initial display style of the events calendar.', 'wfe'),
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

		$events_calendar_multisite_source = wf_get_data('options', ['events_calendar_multisite_source']);

		if( is_multisite() && $events_calendar_multisite_source ) {
			$this->add_control(
				'source',
				[
					'label'     	  	=> __('Source Multisite Events', 'wfe'),
					'description'   	=> __('Choose to load all events from a multisite network or the events from the current site.', 'wfe'),
					'type'      	  	=> Controls_Manager::SELECT,
					'default'   	  	=> 'local',
					'options'  	    	=> [
						'local' 	=> __('Events from this Site', 'wfe'),
						'network'  	=> __('Events from all Sites', 'wfe')
					]
				]
			);			
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_style',
			[
				'label' => esc_html__('Header Styling', 'wfe'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __('Title Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar-title' => 'color: {{VALUE}};',
				],
				'separator'	=> 'after'
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

		// Buttons
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => __('Button Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-toolbar .fc-button-primary:not(.fc-prev-button):not(.fc-next-button)',
				'separator'	=> 'after'
			]
		);

		$this->start_controls_tabs('button_style_tabs');

		$this->start_controls_tab(
			'button_style_normal_tab',
			[
				'label' => __('Normal', 'wfe'),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __('Button Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __('Button Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => __('Button Border Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover_tab',
			[
				'label' => __('Hover', 'wfe'),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => __('Button Hover Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .fc-toolbar .fc-button-primary.fc-button-active' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label'     => __('Button Hover Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .fc-toolbar .fc-button-primary.fc-button-active' => 'background-color: {{VALUE}};',
				],
				'separator' 	  => 'before'
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __('Button Hover Border Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar .fc-button-primary:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .fc-toolbar .fc-button-primary.fc-button-active' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/** 
		 * Calendar View
		 */
		$this->start_controls_section(
			'section_event_calendar_style',
			[
				'label' => esc_html__('Calendar Styling', 'wfe'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'table_head_typography',
				'label'     => __('Calendar Head Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-col-header',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'      => 'table_head_background',
				'label' 		=> __('Calendar Head Background', 'wfe'),
				'types'     => ['classic', 'gradient'],
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .fc-col-header'
			]
		);

		$this->add_control(
			'table_head_text_color',
			[
				'label'     => __('Calender Head Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-col-header a' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'table_head_border_color',
			[
				'label'     => __('Calender Head Border Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-col-header th' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'table_body_background_color',
			[
				'label'     => __('Table Cell Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-view-harness td' => 'background-color: {{VALUE}};',
				],
				'separator' 	  => 'before'
			]
		);

		$this->add_control(
			'table_body_border_color',
			[
				'label'     => __('Table Cell Border Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-view-harness td, {{WRAPPER}} .fc-scrollgrid' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'table_body_current_background_color',
			[
				'label'     => __('Current Day Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-view-harness .fc-day-today' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'table_body_text_color',
			[
				'label'     => __('Calendar Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-day-number, {{WRAPPER}} .fc-timegrid-axis-cushion, {{WRAPPER}} .fc-timegrid-slot-label-cushion' => 'color: {{VALUE}};',
				],
				'separator' 	  => 'before'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'table_body_typography',
				'label'     => __('Calendar General Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-daygrid-day-number, {{WRAPPER}} .fc-timegrid-axis-cushion, {{WRAPPER}} .fc-timegrid-slot-label-cushion'
			]
		);

		$this->end_controls_section();

		/**
		 * Event
		 */
		$this->start_controls_section(
			'section_event_calendar_event_style',
			[
				'label' => esc_html__('Calendar Event Styling', 'wfe'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'event_title_typography',
				'label'     => __('Event Title Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-event-title, {{WRAPPER}} .fc-list-event-title'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'event_time_typography',
				'label'     => __('Event Time Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-event-time, {{WRAPPER}} .fc-list-event-time',
				'separator' => 'after'
			]
		);

		$this->start_controls_tabs('event_style_tabs');

		$this->start_controls_tab(
			'event_style_normal_tab',
			[
				'label' => __('Normal', 'wfe'),
			]
		);

		$this->add_control(
			'event_text_color',
			[
				'label'     => __('Event Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event, {{WRAPPER}} .fc-event .fc-event-main' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_background_color',
			[
				'label'     => __('Event Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_dot_color',
			[
				'label'     => __('Event Dot Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-event-dot, {{WRAPPER}} .fc-list-event-dot' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'event_style_hover_tab',
			[
				'label' => __('Hover', 'wfe'),
			]
		);

		$this->add_control(
			'event_hover_text_color',
			[
				'label'     => __('Event Hover Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover, {{WRAPPER}} .fc-event:hover .fc-event-main' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_hover_background_color',
			[
				'label'     => __('Event Hover Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_hover_dot_color',
			[
				'label'     => __('Event Hover Dot Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover .fc-daygrid-event-dot, {{WRAPPER}} .fc-event:hover .fc-list-event-dot' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/** 
		 * List View
		 */
		$this->start_controls_section(
			'section_event_list_style',
			[
				'label' => esc_html__('List View Styling', 'wfe'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'event_list_header_typography',
				'label'     => __('Event List Header Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .fc-list-day-cushion'
			]
		);

		$this->add_control(
			'event_list_header_text_color',
			[
				'label'     => __('Event List Header Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-list-day-cushion a' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_list_header_background_color',
			[
				'label'     => __('Event List Header Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-list-day-cushion' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'event_list_title_typography',
				'label'     => __('Event List Title Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} td.fc-list-event-title'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'event_list_time_typography',
				'label'     => __('Event List Time Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} td.fc-list-event-time',
				'separator' => 'after'
			]
		);

		$this->start_controls_tabs('event_list_style_tabs');

		$this->start_controls_tab(
			'event_list_style_normal_tab',
			[
				'label' => __('Normal', 'wfe'),
			]
		);

		$this->add_control(
			'event_list_text_color',
			[
				'label'     => __('Event List Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event, {{WRAPPER}} .fc-event .fc-event-main' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_list_background_color',
			[
				'label'     => __('Event List Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_list_dot_color',
			[
				'label'     => __('Event List Dot Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-event-dot, {{WRAPPER}} .fc-list-event-dot' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'event_list_style_hover_tab',
			[
				'label' => __('Hover', 'wfe'),
			]
		);

		$this->add_control(
			'event_list_hover_text_color',
			[
				'label'     => __('Event List Hover Text Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover, {{WRAPPER}} .fc-event:hover .fc-event-main' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_list_hover_background_color',
			[
				'label'     => __('Event List Hover Background Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'event_list_hover_dot_color',
			[
				'label'     => __('Event List Hover Dot Color', 'wfe'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover .fc-daygrid-event-dot, {{WRAPPER}} .fc-event:hover .fc-list-event-dot' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Renders the output of the widget
	 */
	protected function render() {

		$settings = $this->get_settings();

		$calendar   = new \Waterfall_Events\Views\Components\Calendar([
			'display' 	=> $settings['view'],
			'source'	=> isset($settings['source']) ? $settings['source'] : 'local'
		]);
		$calendar->render();

		// Adds the script tags to expose our configurations to elementor
		if (\Waterfall_Events\Helper::is_elementor_editor()) {
			$calendar->echo_config_JS();
		}
	}

	/**
	 * Render output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
	}

	/**
	 * Render as plain content.
	 *
	 * Override the default render behavior, don't render sidebar content.
	 *
	 * @access public
	 */
	public function render_plain_content() {
	}
}
