<?php
/**
 * Template for the map component
 * This component can show a map with one or more events 
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); ?>
<div class="wfe-map" id="<?php echo esc_attr($id);?>">
    <?php if( isset($filters) ) { ?>
        <form class="wfe-map-filters">
            <?php foreach( $filters as $key => $filter ) { ?>
                <select name="<?php echo $key; ?>">
                    <option value=""><?php echo $filter['label']; ?></option>
                    <?php foreach( $filter['values'] as $value => $option ) { ?>
                        <option value="<?php echo $value; ?>"><?php echo $option; ?></option>
                    <?php } ?> 
                </select>
            <?php } ?>    
        </form>
    <?php } ?>
    <div class="wfe-map-canvas"></div>
</div>