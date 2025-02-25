<?php
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}

$widget->add_render_attribute( 'description_text', 'class', 'item--description' );

$widget->add_inline_editing_attributes( 'title_text', 'none' );
$widget->add_inline_editing_attributes( 'description_text' );
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
if ( ! empty( $settings['link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['link']['url'] );

    if ( $settings['link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
$html_id = ct_get_element_id($settings);
?>
<div id="<?php echo esc_attr($html_id); ?>" class="ct-fancy-box ct-fancy-box-layout5 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if(!empty($settings['el_color1']) && !empty($settings['el_color2'])) : ?>
        <div class="ct-inline-css"  data-css="
            #<?php echo esc_attr($html_id) ?>:before, #<?php echo esc_attr($html_id) ?> .item--link:before, #<?php echo esc_attr($html_id) ?> .item--icon i, #<?php echo esc_attr($html_id) ?> .item--list i {
                background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($settings['el_color1']); ?>), to(<?php echo esc_attr($settings['el_color2']); ?>));
                background-image: -webkit-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -moz-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -ms-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -o-linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: linear-gradient(top, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($settings['el_color1']); ?>', endColorStr='<?php echo esc_attr($settings['el_color2']); ?>');
                background-color: transparent;
            }">
        </div>
    <?php endif; ?>

    <div class="ct-fancy-box-inner">
        <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
            <div class="item--icon">
                <?php if($is_new):
                    \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                    else: ?>
                    <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
            <div class="item--icon">
                <?php $img_icon  = consultio_get_image_by_size( array(
                        'attach_id'  => $settings['icon_image']['id'],
                        'thumb_size' => 'full',
                    ) );
                    $thumbnail_icon    = $img_icon['thumbnail'];
                echo wp_kses_post($thumbnail_icon); ?>
            </div>
        <?php endif; ?>
        <div class="item--holder">
            <h3 class="item--title">
                <?php echo esc_html($settings['title_text']); ?>
            </h3>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'description_text' )); ?>><?php echo ct_print_html($settings['description_text']); ?></div>
            <?php if(isset($settings['list']) && !empty($settings['list']) && count($settings['list'])): ?>
                <ul class="item--list">
                    <?php foreach ($settings['list'] as $key => $ct_list): ?>
                        <li><i class="fac fac-arrow-right"></i><?php echo esc_html($ct_list['content'])?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ( ! empty( $settings['link']['url'] ) ) { ?>
                <a class="item--link" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>>+</a>
            <?php } ?>
        </div>
    </div>
</div>