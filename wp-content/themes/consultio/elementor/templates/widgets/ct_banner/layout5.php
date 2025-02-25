<?php
$default_settings = [
    'title' => '',
    'video_link' => '',
    'video_image' => '',
    'icon_image' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if ( ! empty( $settings['image']['url'] ) ) {
    $widget->add_render_attribute( 'image', 'src', $settings['image']['url'] );
    $widget->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $settings['image'] ) );
    $widget->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $settings['image'] ) );
}
$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
?>
<div class="ct-banner5 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
	<div class="ct-banner-imge">
    	<?php echo wp_kses_post($image_html); ?>
        <h3 class="ct-banner-title"><?php echo esc_attr($title); ?></h3>
    </div>
    <?php if(!empty($video_link)) : ?>
    	<div class="ct-banner-video">
    		<?php if(!empty($video_image['url'])) : ?>
    			<div class="ct-banner-video-image bg-image" style="background-image: url(<?php echo esc_url($video_image['url']); ?>);"></div>
    		<?php endif; ?>
	        <a class="ct-video-button" href="<?php echo esc_url($video_link); ?>">
	            <i class="fac fac-play"></i>
	        </a>
	    </div>
	<?php endif; ?>
</div>