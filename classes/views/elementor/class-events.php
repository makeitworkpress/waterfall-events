<?php
/**
 * Displays an in between section with a document, for use in the articles itself
 */
namespace Waterfall_Events\Views\Elementor;
use Elementor as Elementor;
use Elementor\Controls_Manager as Controls_Manager;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class Events extends Elementor\Widget_Base {

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
		return __( 'Events List', 'wfe' );
    }
    
    /**
	 * Name for the icon used
	 *
	 * @return string Widget icon
     */
	public function get_icon() {
		return 'eicon-post-list';
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
				'label'     	  => __( 'Display Style', 'wfe' ),
				'description'   => __( 'Determines the display style of the events element.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'list',
				'options'  	    => [
          'list' => __('List', 'wfe'),
          'grid' => __('Grid', 'wfe'),
        ]
			]
		);
    
		$this->add_control(
			'columns',
			[
				'label'     	  => __( 'Columns', 'wfe' ),
				'description'   => __( 'By how many columns events are displayed.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'full',
				'options'  	    => wf_get_column_options()
			]
		);
    
		$this->add_control(
			'gap',
			[
				'label'     	  => __( 'Columns Gap', 'wfe' ),
				'description'   => __( 'The size of the gap between columns.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'default',
				'options'  	    => wf_get_grid_gaps()
			]
		);     
    
		$this->add_control(
			'none',
			[
				'label'     	  => __( 'No Events Text', 'wfe' ),
				'description'   => __( 'Optional text that is shown when no events are found.', 'wfe' ),
				'type'      	  => Controls_Manager::TEXT
			]
		);

    $this->end_controls_section();
    
    $this->start_controls_section( 
      'section_elements',
      [
        'label' => esc_html__( 'Elements', 'wfe' ),
      ]
		);    
    
		$this->add_control(
			'excerpt',
			[
				'label'     	  => __( 'Show Excerpt', 'wfe' ),
				'description'   => __( 'Show event excerpt in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
        'default'   	  => 'yes',
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);    
    
		$this->add_control(
			'location',
			[
				'label'     	  => __( 'Show Location', 'wfe' ),
				'description'   => __( 'Show event location in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);

		$this->add_control(
			'date',
			[
				'label'     	  => __( 'Show Event Date', 'wfe' ),
				'description'   => __( 'Show event date in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
        'default'   	  => 'yes',
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);    
    
		$this->add_control(
			'price',
			[
				'label'     	  => __( 'Show Price', 'wfe' ),
				'description'   => __( 'Show event price in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);    
		
		$this->add_control(
			'categories',
			[
				'label'     	  => __( 'Show Categories', 'wfe' ),
				'description'   => __( 'Show categories in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);
    
		$this->add_control(
			'tags',
			[
				'label'     	  => __( 'Show Tags', 'wfe' ),
				'description'   => __( 'Show tags in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);
    
		$this->add_control(
			'register',
			[
				'label'     	  => __( 'Show Registration Button', 'wfe' ),
				'description'   => __( 'Show the registration button in each event.', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);    
	
    $this->end_controls_section();

    // Query section
    $this->start_controls_section( 
      'section_query',
      [
          'label' => esc_html__( 'Query', 'wfe' ),
      ]
		);

    // @todo Add sort by event date or 
		$this->add_control(
			'posts_per_page',
			[
				'label'     	  => __( 'Number of Events', 'wfe' ),
				'description'   => __( 'Number of events to show per page.', 'wfe' ),
				'type'      	  => Controls_Manager::NUMBER,
				'default'   	  => 10,
				'min'  	        => 1,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'     	  => __( 'Pagination', 'wfe' ),
				'description'   => __( 'Enable pagination', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);    
		
		$this->end_controls_section();

    $this->start_controls_section( 
      'section_event_style',
      [
        'label' => esc_html__( 'Event Styling', 'wfe' ),
        'tab'   => Controls_Manager::TAB_STYLE
      ]
		);

    // Event
		$this->add_control(
			'appear',
			[
				'label'     	  => __( 'Event Appear', 'wfe' ),
				'description'   => __( 'The direction of the appearance animation when an event becomes visible.', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'bottom',
				'options'  	    => [
          'bottom'    => __('Bottom', 'wfe'),
          'top'       => __('Top', 'wfe'),
          'left'      => __('Left', 'wfe'),
          'right'     => __('Right', 'wfe'),
          ''          => __('None', 'wfe'),
        ]
			]
		);

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
        'label' => esc_html__( 'Image', 'wfe' ),
        'tab'   => Controls_Manager::TAB_STYLE
      ]
		);
    
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image',       // Usage: `{name}_size`, in this case `image_size`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'thumbnail',
			]
		);  
    
		$this->add_control(
			'image_float',
			[
				'label'     	  => __( 'Image Float', 'wfe' ),
				'description'   => __( 'The alignment of the image', 'wfe' ),
				'type'      	  => Controls_Manager::SELECT,
				'default'   	  => 'left',
				'options'  	    => [
          'left'    => __('Left', 'wfe'),
          'right'   => __('Right', 'wfe'),
          'center'  => __('Center', 'wfe'),
          'none'    => __('None', 'wfe'),
        ]
			]
		);    

		$this->add_control(
			'image_enlarge',
			[
				'label'     	  => __( 'Enlarge Image', 'wfe' ),
				'description'   => __( 'Enlarges the image upon hover', 'wfe' ),
				'type'      	  => Controls_Manager::SWITCHER,
				'label_on'  	  => __( 'Yes', 'wfe' ),
				'label_off' 	  => __( 'No', 'wfe' ),
				'separator' 	  => 'before'
			]
		);      

		$this->add_control(
			'image_border_radius',
			[
				'label' 		  => __( 'Image Border Radius', 'wfe' ),
				'type' 			  => Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wfe-events .entry-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]		
			]
		);    

    $this->end_controls_section();   

    // Content
    $this->start_controls_section( 
      'section_content_style',
      [
        'label' => esc_html__( 'Content', 'wfe' ),
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
			'title_color',
			[
				'label'     => __( 'Title Color', 'plugin-domain' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wfe-events .entry-title a' => 'color: {{VALUE}};',
				],
			]
		);    
    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __('Title Typography', 'wfe'),
				'selector'  => '{{WRAPPER}} .wfe-events .entry-title',
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

    $events   = new \Waterfall_Events\Views\Components\Events([
      'appear'        => $settings['appear'],      
      'categories'    => $settings['categories'],   
      'columns'       => $settings['columns'],     
      'date'          => $settings['date'],       
      'excerpt'       => $settings['excerpt'],       
      'gap'           => $settings['gap'],       
      'image_enlarge' => $settings['image_enlarge'],       
      'image_float'   => $settings['image_float'],      
      'image_size'    => $settings['image_size'], 
      'location'      => $settings['location'],        
      'none'          => $settings['none'],          
      'pagination'    => $settings['pagination'],
      'price'         => $settings['price'],
      'query'         => [
        'posts_per_page'  => $settings['posts_per_page'],
        'post_status'     => 'publish',
        'post_type'       => 'events'
      ],        
      'register'      => $settings['register'], 
      'tags'          => $settings['tags'],
      'title_tag'     => $settings['title_tag'],
      'view'          => $settings['view']
    ]);

    $events->render();
    
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