<?php
/**
 * Template for the details components
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); 
if( ! $dates && ! $price && ! $terms && ! $website && ! $register ) {
    return;
} ?>
<div class="wfe-details wfe-single-info <?php echo $class; ?>">
    <?php if( isset($title) ) { ?>
        <h2><?php echo $title; ?></h2>
    <?php } ?>
    <div class="wfe-details-items">
        <?php foreach(['dates', 'price', 'terms', 'website', 'register'] as $detail ) { ?>
            <?php if( isset(${$detail}) && ${$detail} ) { ?>
                <div class="wfe-details-item wfe-details-item-<?php echo $detail; ?>">
                    <?php if( $detail != 'terms' && isset(${$detail . '_title'}) ) { ?>
                        <h3><?php echo ${$detail . '_title'}; ?></h3>
                    <?php } ?>
                    <?php echo ${$detail}; ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>