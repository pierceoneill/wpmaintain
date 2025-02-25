<?php 
$default_settings = [
    'image' => '',
    'image_type' => '',
    'image_link' => '',
    'img_size' => '',
    'ct_animate' => '',
    'img_tilt' => 'no',
    'img_effect' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$size = 'full';
if(!empty($img_size)) {
    $size = $img_size;
} else {
    $size = 'full';
}
if ( ! empty( $image_link['url'] ) ) {
    $widget->add_render_attribute( 'image_link', 'href', $image_link['url'] );

    if ( $image_link['is_external'] ) {
        $widget->add_render_attribute( 'image_link', 'target', '_blank' );
    }

    if ( $image_link['nofollow'] ) {
        $widget->add_render_attribute( 'image_link', 'rel', 'nofollow' );
    }
}

if($img_tilt == 'yes') {
    wp_enqueue_script( 'tilt', get_template_directory_uri() . '/assets/js/tilt.js', array( 'jquery' ), 'all', true );
    wp_enqueue_script( 'ct-tilt', get_template_directory_uri() . '/elementor/js/ct-tilt.js', array( 'jquery' ), 'all', true );
}

?>
<div class="ct-image-single img-type-<?php echo esc_attr($settings['image_type']); ?> ct-<?php echo esc_attr($img_effect); ?> <?php if($img_tilt == 'yes') { echo 'img-hover-scale'; } ?> <?php echo esc_attr($ct_animate); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if ($image_type == 'img') { ?>
        <?php if ( ! empty( $image_link['url'] ) ) { ?><a <?php ct_print_html($widget->get_render_attribute_string( 'image_link' )); ?>><?php } ?>
            <?php if ( ! empty( $image['url'] ) ) { 
                $img  = consultio_get_image_by_size( array(
                    'attach_id'  => $image['id'],
                    'thumb_size' => $size,
                ) );
                $thumbnail    = $img['thumbnail'];
                echo wp_kses_post($thumbnail); 
            } ?>
        <?php if ( ! empty( $image_link['url'] ) ) { ?></a><?php } ?>
    <?php } else { ?>
        <div class="ct-image-bg bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);"></div>
    <?php } ?>
</div>