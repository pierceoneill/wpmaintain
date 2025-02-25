<?php
$default_settings = [
    'title' => '',
    'description' => '',
    'recommended' => '',
    'price' => '',
    'time' => '',
    'button_text' => '',
    'button_link' => '',
    'content_list' => '',
    'item_highlight' => '',
    'ct_animate' => '',
    'ct_animate_delay' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
if ( ! empty( $button_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $button_link['url'] );

    if ( $button_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $button_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="ct-pricing-layout4 <?php echo esc_attr($ct_animate); if($item_highlight == 'yes') { echo 'highlight'; } ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <div class="pricing-title">
        <span>
            <?php echo esc_attr($title); ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="5" height="17" viewBox="0 0 5 17"><g><path class="cls-1" d="M5.276-.181L0.316,16.683h4.96V-0.181Z"/></g></svg>
        </span>
    </div>
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
    <div class="pricing-price"><?php echo esc_attr($price); ?><span><?php echo esc_attr($time); ?></span></div>
    <?php if(isset($settings['content_list']) && !empty($settings['content_list']) && count($settings['content_list'])): ?>
        <ul class="pricing-feature">
            <?php
                foreach ($settings['content_list'] as $key => $ct_list): ?>
                <li class="<?php if($ct_list['active'] == 'yes') { echo 'active'; } ?>"><?php echo ct_print_html($ct_list['content'])?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if(!empty($button_text)) : ?>
        <div class="pricing-button">
            <a class="btn <?php if($style == 'style1') { echo 'btn-secondary'; } else { echo 'btn-preset3'; } ?>" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($button_text); ?></a>
        </div>
    <?php endif; ?>
</div>