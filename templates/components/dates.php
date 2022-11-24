<?php
/**
 * Template for the dates components
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); 

if( ! $dates[0]['startDate'] ) {
    return;
} ?>
<ul class="wfe-dates">
    <?php foreach( $dates as $date ) { ?>
        <li>
            <i class="fa fa-calendar"></i>
            <div class="wfe-dates-date">
                <?php if($date['title']) { ?>
                    <b class="wfe-dates-title"><?php echo $date['title']; ?>:</b>
                <?php }?>
                <?php if($date['startDate']) { ?>
                    <span class="wfe-dates-date"><?php echo $date['startDate']; ?></span>
                <?php } ?>
                <?php if($date['startTime']) { ?>
                    <span class="wfe-dates-time"><?php echo $date['startTime']; ?></span>
                <?php } ?>            
                <?php if($date['endDate'] || $date['endTime']) { ?> 
                    <span class="wfe-dates-seperator"><?php echo $separator; ?></span>
                <?php } ?> 
                <?php if($date['endDate']) { ?>
                    <span class="wfe-dates-date"><?php echo $date['endDate']; ?></span>
                <?php } ?>  
                <?php if($date['endTime']) { ?>
                    <span class="wfe-dates-time"><?php echo $date['endTime']; ?></span>
                <?php } ?>
            <div>                          
        </li>
    <?php } ?>
</ul>