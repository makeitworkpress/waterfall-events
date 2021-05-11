<?php
/**
 * Template for the summary component
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

if( ! $description && (! isset($register) || ! $register) ) {
    return;
} ?>
<div class="wfe-summary">
    <?php if( $description ) { ?>
        <p class="wfe-summary-description"><?php echo $description; ?></p>
    <?php } ?>
    <?php if( isset($register) ) { ?>
        <?php echo $register; ?>
    <?php } ?>    
</div>