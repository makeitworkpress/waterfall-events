<?php
/**
 * Contains the class abstraction for our components
 */
namespace Waterfall_Events\Views\Components;
use ReflectionClass as ReflectionClass;

defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

abstract class Component {  

    /**
     * Contains the parameters for a component
     * @access protected
     */
    protected $params = [];

    /**
     * Contains the public properties, used in the template
     * @access public
     */
    public $props = [];

    /**
     * Contains the template
     * @access protected
     */
    protected $template = '';
    
    /**
     * Set up our parameters and component
     * 
     * @param array     $params     The parameters for our material grid
     * @param boolean   $format     If we want to query and format by default
     * @param boolean   $render     If we want to render by default
     */
    public function __construct( $params = [], $format = true, $render = false ) {  
        
        $this->params       = $params;

        $this->initialize();     

        // If format is true, we query and format
        if( $format ) {
            $this->format();
        }

        // Default template
        $class          = strtolower( (new ReflectionClass($this))->getShortName() );
        $this->template = apply_filters( 'wfe_components_template_' . $class, WFE_PATH . '/templates/components/' . $class  . '.php');
        $this->props    = apply_filters( 'wfe_components_props_' . $class, $this->props);

        // If render is true, we render by default
        if( $render ) {
            $this->render();
        }

    } 
    
    /**
     * This function initializes our components, and set its default parameters
     */
    abstract protected function initialize();      

    /**
     * This function should be present at children - it queries components data from the database and formates its for use, setting the properties
     */
    abstract protected function format();  
    

    /**
     * Renders a component
     * 
     * @param boolean   $render  If set to false, returns the given template
     */
    public function render( $render = true ) {

        if( ! $this->props ) {
            return;
        }

        if( ! file_exists($this->template) ) {
            return;
        }

        // Cast our object properties into the template variables, so they are accessible by the template file.
        foreach( $this->props as $key => $value ) {
            ${$key} = $value;
        }
        
        if( ! $render ) {
            ob_start();
        }
        
        require( $this->template );

        if( ! $render ) {
            return ob_get_clean();
        }

    }   

}