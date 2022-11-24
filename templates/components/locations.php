<?php
/**
 * Template for the dates components
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' ); 

if( ! $locations ) {
    return;
} ?>
<div class="wfe-locations wfe-single-info wfe-card">
    <h2><?php echo $title; ?></h2>
    <ul class="wfe-locations-list">
        <?php foreach( $locations as $location ) { ?>
            <li>
                <?php if($location['name']) { ?>
                    <h3>
                        <?php if($location['link']) { ?><a href="<?php echo $location['link']; ?>" title="<?php echo $location['name']; ?>"><?php } ?>
                            <?php echo $location['name']; ?>
                        <?php if($location['link']) { ?></a><?php } ?>
                    </h3>
                <?php } ?>
                <address class="wfe-location-address">
                    <?php if($location['street'] || $location['city'] || $location['country']) { ?>
                        <span class="wfe-location-meta">
                            <i class="fa fa-map-marker"></i>
                            <span class="wfe-location-address">
                                <?php if( $location['street'] ) { ?>
                                    <span class="wfe-location-street"><?php echo $location['street'] . ' ' . $location['number']; ?></span>
                                <?php } ?>
                                <?php foreach(['postal_code', 'city', 'country'] as $attr ) { ?> 
                                    <?php if( $location[$attr] ) { ?>
                                        <span class="wfe-location-<?php echo $attr; ?>">
                                            <?php echo $location[$attr]; ?>
                                        </span>
                                    <?php } ?>
                                <?php } ?>  
                            </span>                         
                        </span>
                    <?php } ?>
                    <?php foreach(['email' => ['envelope-o', 'mailto:'], 'phone' => ['phone', 'tel:'], 'website' => ['link', '']] as $meta => $attr ) { ?>
                        <?php if($location[$meta]) { ?>
                            <span class="wfe-location-meta">
                                <i class="fa fa-<?php echo $attr[0]; ?>"></i>
                                <a href="<?php echo $attr[1] . $location[$meta]; ?>" target="_blank"><?php echo $location[$meta]; ?></a>
                            </span>
                        <?php } ?>
                    <?php } ?>   
                </address>                
            </li>
        <?php } ?>
    </ul>
    <?php if( isset($map) ) { echo $map; } ?>
</div>