<?php
$default_settings = [
    'form_id' => '',
    'title' => '',
    'description' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if(class_exists('WPCF7') && !empty($form_id)) : ?>
    <div class="ct-get-quote1 bg-image <?php echo esc_attr($ct_animate); ?>">
    	<?php if(!empty($title) || !empty($description)) : ?>
	    	<div class="ct-quote-meta">
	    		<h4><?php echo esc_attr($title); ?></h4>
	    		<p><?php echo ct_print_html($description); ?><i></i></p>
	    	</div>
	    <?php endif; ?>
        <div class="ct-quote-form">
            <?php echo do_shortcode('[contact-form-7 id="'.esc_attr( $form_id ).'"]'); ?>
        </div>
    </div>
<?php endif; ?>
