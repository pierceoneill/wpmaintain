<?php
$default_settings = [
    'image' => '',
    'image_overlay' => '',
    'color_overlay' => '',
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

if ( ! empty( $settings['image']['url'] ) ) { ?>
	<div class="ct-background-animate">
        <div class="ct-animate-overlay bg-image" style="background-image: url(<?php echo esc_url($settings['image_overlay']['url']); ?>); background-color: <?php echo esc_attr($color_overlay); ?>; "></div>
        <div class="ct-animate-inner" style="background-image: url(<?php echo esc_url($settings['image']['url']); ?>);"></div>   
    </div>
<?php } ?>