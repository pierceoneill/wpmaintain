<?php
$default_settings = [
    'title' => '',
    'link' => '',
    'type' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
if ( ! empty( $link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $link['url'] );

    if ( $link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['image']['url'] ) ) {
    $widget->add_render_attribute( 'image', 'src', $settings['image']['url'] );
    $widget->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $settings['image'] ) );
    $widget->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $settings['image'] ) );
	$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ); ?>
	<div class="ct-award1 image-light-box <?php echo esc_attr($ct_animate); ?>">
        <?php if($type == 'type_img') : ?>
	       <div class="ct-award-image"><?php echo wp_kses_post($image_html); ?></div>
        <?php endif; ?>
        <?php if($type == 'type_bg') : ?>
           <div class="ct-award-bg"><span class="bg-image" style="background-image: url(<?php echo esc_url($settings['image']['url']); ?>);"></span></div>
        <?php endif; ?>
	    <div class="ct-award-title"><?php echo esc_attr($title); ?></div>
        <a class="ct-award-link ct-light-box" href="<?php echo esc_url($settings['image']['url']); ?>"></a>
	</div>
<?php } ?>