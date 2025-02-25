<?php
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$widget->add_render_attribute( 'description', 'class', 'item--description' );
$widget->add_inline_editing_attributes( 'title', 'none' );
$widget->add_inline_editing_attributes( 'description' );
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
if ( ! empty( $settings['btn_link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['btn_link']['url'] );

    if ( $settings['btn_link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['btn_link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="ct-text-box ct-text-box-layout1 <?php echo esc_attr($settings['ct_animate']); ?>">
    <div class="ct-text-box-inner">
        <div class="item--sub-title"><?php echo esc_attr($settings['sub_title']); ?></div>
        <h3 class="item--title">
            <?php echo esc_html($settings['title']); ?>
        </h3>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'description' )); ?>><?php echo ct_print_html($settings['description']); ?></div>
        <?php if ( ! empty( $settings['btn_text'] ) ) { ?>
            <div class="item--button">
                <a class="btn" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($settings['btn_text']); ?></a>
            </div>
        <?php } ?>
    </div>
</div>