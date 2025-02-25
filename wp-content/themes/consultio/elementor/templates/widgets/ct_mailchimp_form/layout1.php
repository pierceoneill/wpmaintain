<?php
$default_settings = [
    'button_bg_color' => '',
    'button_bg_gradient' => '',
    'gradient_type' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
if(class_exists('MC4WP_Container')) : ?>
    <div id="<?php echo esc_attr($html_id); ?>" class="ct-mailchimp ct-mailchimp1 <?php echo esc_attr($settings['style']); ?>">
    	<?php if(!empty($button_bg_color) && !empty($button_bg_gradient) && $gradient_type == 'horizontal') : ?>
	    	<div class="ct-inline-css"  data-css="
	            #<?php echo esc_attr($html_id) ?>.ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields:before {
	                background-image: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($button_bg_color); ?>), to(<?php echo esc_attr($button_bg_gradient); ?>));
					background-image: -webkit-linear-gradient(left, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -moz-linear-gradient(left, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -ms-linear-gradient(left, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -o-linear-gradient(left, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: linear-gradient(left, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($button_bg_color); ?>', endColorStr='<?php echo esc_attr($button_bg_gradient); ?>', gradientType='1');
					background-color: transparent;
	            }">
		    </div>
		<?php endif; ?>

		<?php if(!empty($button_bg_color) && !empty($button_bg_gradient) && $gradient_type == 'vertical') : ?>
	    	<div class="ct-inline-css"  data-css="
	            #<?php echo esc_attr($html_id) ?>.ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields:before {
	                background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($button_bg_color); ?>), to(<?php echo esc_attr($button_bg_gradient); ?>));
					background-image: -webkit-linear-gradient(top, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -moz-linear-gradient(top, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -ms-linear-gradient(top, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: -o-linear-gradient(top, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					background-image: linear-gradient(top, <?php echo esc_attr($button_bg_color); ?>, <?php echo esc_attr($button_bg_gradient); ?>);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($button_bg_color); ?>', endColorStr='<?php echo esc_attr($button_bg_gradient); ?>');
					background-color: transparent;
	            }">
		    </div>
	    <?php endif; ?>

	    <?php echo do_shortcode('[mc4wp_form]'); ?>
    </div>
<?php endif; ?>
