<?php
/**
 * Template for the dates components
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); 

if( $price === '' ) {
    return;
} ?>
<div class="wfe-price">
    <?php if($currency) { ?>
        <span class="wfe-price-currency"><?php echo $currency; ?></span>
    <?php } ?>
    <span class="wfe-price-value"><?php echo $price; ?></span>
</div>