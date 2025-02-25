<?php
$default_settings = [
    'title' => '',
    'price' => '',
    'time' => '',
    'button_text' => '',
    'button_link' => '',
    'content_list' => '',
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
<div class="ct-pricing-layout3 <?php echo esc_attr($ct_animate); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
	<div class="pricing-meta">
        <h3 class="pricing-title"><?php echo esc_attr($title); ?></h3>
    </div>
    <div class="pricing-price"><?php echo ct_print_html($price); ?><span><?php echo esc_attr($time); ?></span></div>
    <div class="pricing-holder">
        <?php if(isset($settings['content_list']) && !empty($settings['content_list']) && count($settings['content_list'])): ?>
            <ul class="pricing-feature">
                <?php
                    foreach ($settings['content_list'] as $key => $ct_list): ?>
                    <li class="<?php if($ct_list['active'] == 'yes') { echo 'active'; } ?>"><i class="fac fac-check"></i><?php echo ct_print_html($ct_list['content'])?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if(!empty($button_text)) : ?>
            <div class="pricing-button">
                <a class="btn" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($button_text); ?><i class="fac fac-angle-right"></i></a>
            </div>
        <?php endif; ?>
        
    </div>
</div>