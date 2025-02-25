<?php
$default_settings = [
    'title' => '',
    'description' => '',
    'recommended' => '',
    'currency' => '',
    'price' => '',
    'price_constant' => '',
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
<div class="ct-pricing-layout5 <?php echo esc_attr($ct_animate); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <h5 class="pricing-title">
        <span>
            <?php echo esc_attr($title); ?>
        </span>
    </h5>
    <div class="pricing-price">
        <?php if(!empty($currency)) : ?>
            <cite><?php echo esc_attr($currency); ?></cite>
        <?php endif; ?>
        <?php echo esc_attr($price); ?>
        <?php if(!empty($price_constant)) : ?>
            <span><?php echo esc_attr($price_constant); ?></span>
        <?php endif; ?>
    </div>
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