<?php
$default_settings = [
    'title' => '',
    'number' => '',
    'counter_icon' => '',
    'counter_number' => '',
    'counter_title' => '',
    'counter_suffix' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if ( ! empty( $settings['image']['url'] ) ) {
    $widget->add_render_attribute( 'image', 'src', $settings['image']['url'] );
    $widget->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $settings['image'] ) );
    $widget->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $settings['image'] ) );
}
$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );

$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-banner3 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
	<div class="ct-banner-imge">
    	<?php echo wp_kses_post($image_html); ?>
    	<?php if(!empty($number)) : ?>
    		<div class="ct-banner-number"><?php echo esc_attr($number); ?></div>
    	<?php endif; ?>
    	<div class="ct-banner-counter">
    		<?php if(!empty($counter_icon)): ?>
	    		<div class="counter-icon">
	                <?php
	                if($is_new):
	                    \Elementor\Icons_Manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] );
	                ?>
	                <?php
	                else:
	                    $widget->add_render_attribute( 'i', 'class', $settings['counter_icon'] );
	                    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
	                ?>
	                    <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
	                <?php endif; ?>
	    		</div>
    		<?php endif; ?>
    		<div class="counter-holder">
    			<?php if(!empty($counter_number)) : ?>
    				<div class="counter-number">
    					<span class="ct-counter-number-value" data-duration="2000" data-to-value="<?php echo esc_attr($counter_number); ?>" data-delimiter=",">1</span>
    					<span class="counter-suffix"><?php echo esc_attr($counter_suffix); ?></span>
    				</div>
    			<?php endif; ?>
    			<?php if(!empty($counter_title)) : ?>
    				<div class="counter-title">
    					<?php echo esc_attr($counter_title); ?>
    				</div>
    			<?php endif; ?>
    		</div>
    	</div>
    </div>
    <h3 class="ct-banner-title"><?php echo esc_attr($title); ?></h3>
</div>