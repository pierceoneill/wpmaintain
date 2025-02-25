<?php
$default_settings = [
    'title' => '',
    'description' => '',
    'price' => '',
    'time' => '',
    'button_text' => '',
    'button_link' => '',
    'content_list' => '',
    'style' => 'style1',
    'item_highlight' => '',
    'ct_animate' => '',
    'ct_animate_delay' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
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
<div class="ct-pricing-layout6 <?php echo esc_attr($ct_animate); if($item_highlight == 'yes') { echo 'highlight'; } ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
	<h3 class="pricing-title"><?php echo esc_attr($title); ?></h3>
    <div class="pricing-meta">
        <span class="pricing-currency"><?php echo esc_attr($currency); ?></span>
        <span class="pricing-price"><?php echo esc_attr($price); ?></span>
        <span class="pricing-time"><?php echo esc_attr($time); ?></span>
    </div>
    <div class="pricing-description"><?php echo esc_attr($description); ?></div>
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
            <a class="btn" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($button_text); ?></a>
        </div>
    <?php endif; ?>
</div>