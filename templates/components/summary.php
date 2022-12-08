<?php
/**
 * Template for the summary component
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

if( ! $description && (! isset($register) || ! $register) && (! isset($dates) || ! $dates) ) {
    return;
} ?>
<div class="wfe-summary">
    <?php if( $description ) { ?>
        <p class="wfe-summary-description"><?php echo $description; ?></p>
    <?php } ?>
    <div class="wfe-summary-meta">
        <?php if( isset($register) ) { ?>
            <?php echo $register; ?>
        <?php } ?>    
        <?php if( isset($dates) ) { ?>
            <?php $dates->render(); ?>
        <?php } ?>         
    </div>
</div>