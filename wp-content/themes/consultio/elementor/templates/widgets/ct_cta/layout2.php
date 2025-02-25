<?php
$default_settings = [
    'layout' => '',
    'title' => '',
    'sub_title' => '',
    'btn_text' => '',
    'btn_link' => '',
    'btn_style' => '',
    'image' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
if ( ! empty( $btn_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $btn_link['url'] );

    if ( $btn_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $btn_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="ct-cta<?php echo esc_attr($layout); ?> <?php echo esc_attr($ct_animate); ?>">
    <?php if(!empty($image)) {
        $img = ct_get_image_by_size( array(
            'attach_id'  => $image['id'],
            'thumb_size' => 'full',
        ));
        $thumbnail = $img['thumbnail']; ?>
        <div class="item--image"><?php echo wp_kses_post($thumbnail); ?></div>
    <?php } ?>
    <div class="item--holder">
        <?php if(!empty($sub_title)) : ?>
            <div class="item--subtitle"><?php echo esc_attr($sub_title); ?></div>
        <?php endif; ?>
        <?php if(!empty($title)) : ?>
            <h4 class="item--title"><?php echo esc_attr($title); ?></h4>
        <?php endif; ?>
        <?php if(!empty($btn_text)) : ?>
            <div class="item--button">
                <a class="btn <?php echo esc_attr($btn_style); ?>" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>>
                    <?php echo esc_attr($btn_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
