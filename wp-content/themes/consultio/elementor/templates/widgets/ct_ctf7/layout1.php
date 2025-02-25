<?php
$default_settings = [
    'ctf7_id' => '',
    'title' => '',
    'description' => '',
    'style_l1' => 'style1',
    'image_left' => '',
    'image_right' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if(class_exists('WPCF7') && !empty($ctf7_id)) : ?>
    <div class="ct-contact-form-layout1 <?php echo esc_attr($style_l1.' '.$ct_animate); ?>">
        <?php if($style_l1 == 'style11') : ?>
            <?php if(!empty($image_left['url'])) : ?>
                <div class="img-left bg-image" style="background-image: url(<?php echo esc_url($image_left['url']); ?>);"></div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="ct-contact-form-inner">
        	<?php if(!empty($title) || !empty($description)) : ?>
    	    	<div class="ct-contact-meta">
    	    		<h3><?php echo esc_attr($title); ?></h3>
                    <?php if(!empty($description)) : ?>
    	    		 <p><?php echo ct_print_html($description); ?></p>
                    <?php endif; ?>
    	    	</div>
    	    <?php endif; ?>
            <div class="ct-contact-form">
                <?php echo do_shortcode('[contact-form-7 id="'.esc_attr( $ctf7_id ).'"]'); ?>
            </div>
        </div>
        <?php if($style_l1 == 'style11') : ?>
            <?php if(!empty($image_right['url'])) : ?>
                <div class="img-right bg-image" style="background-image: url(<?php echo esc_url($image_right['url']); ?>);"></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
