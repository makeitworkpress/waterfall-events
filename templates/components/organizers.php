<?php
/**
 * Template for the dates components
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); 

if( ! $organizers ) {
    return;
} ?>
<div class="wfe-organizers wfe-single-info wfe-card">
    <h2><?php echo $title; ?></h2>
    <ul class="wfe-organizers-list">
        <?php foreach( $organizers as $organizer ) { ?>
            <li>
                <h3><a href="<?php echo $organizer['link']; ?>" title="<?php echo $organizer['name']; ?>"><?php echo $organizer['name']; ?></a></h3>
                <?php foreach(['email' => ['envelope-o', 'mailto:'], 'phone' => ['phone', 'tel:'], 'website' => ['link', '']] as $meta => $attr ) { ?>
                    <?php if($organizer[$meta]) { ?>
                        <div class="wfe-organizers-meta">
                            <i class="fa fa-<?php echo $attr[0]; ?>"></i>
                            <a href="<?php echo $attr[1] . $organizer[$meta]; ?>" target="_blank"><?php echo $organizer[$meta]; ?></a>
                        </div>
                    <?php } ?>
                <?php } ?>                    
            </li>
        <?php } ?>
    </ul>
</div>