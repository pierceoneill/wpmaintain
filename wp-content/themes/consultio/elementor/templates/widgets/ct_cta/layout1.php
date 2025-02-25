<?php
$default_settings = [
    'layout' => '',
    'title' => '',
    'desc' => '',
    'btn_text' => '',
    'btn_link' => '',
    'btn_icon' => '',
    'btn_style' => '',
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
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-cta<?php echo esc_attr($layout); ?> <?php echo esc_attr($ct_animate); ?>">
    <?php if(!empty($title)) : ?>
        <div class="item--holder">
            <span class="item--title"><?php echo esc_attr($title); ?></span>
            <span class="item--desc"><?php echo esc_attr($desc); ?></span>
        </div>
    <?php endif; ?>
    <?php if(!empty($btn_text)) : ?>
        <div class="item--button">
            <a class="btn <?php echo esc_attr($btn_style); ?>" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>>
                <?php if ( $btn_icon ) : ?>
                    <?php if($is_new):
                        \Elementor\Icons_Manager::render_icon( $btn_icon, [ 'aria-hidden' => 'true' ] );
                        else: ?>
                        <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
                    <?php endif; ?>
                <?php endif; ?>
                <?php echo esc_attr($btn_text); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
