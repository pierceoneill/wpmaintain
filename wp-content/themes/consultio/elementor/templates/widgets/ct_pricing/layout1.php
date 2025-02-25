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
    'style' => 'style1',
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
<div class="ct-pricing-layout1 <?php echo esc_attr($style.' '.$ct_animate); ?> <?php if(!empty($recommended)) { echo 'recommended'; } ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if(!empty($recommended)) : ?>
        <div class="pricing-recommend"><?php echo esc_attr($recommended); ?></div>
    <?php endif; ?>
	<div class="pricing-meta">
        <h3 class="pricing-title"><?php echo esc_attr($title); ?></h3>
        <div class="pricing-description"><?php echo esc_attr($description); ?></div>
    </div>
    <div class="pricing-price"><?php echo esc_attr($price); ?><span><?php echo esc_attr($time); ?></span></div>
    <div class="pricing-holder">
        <?php if(isset($settings['content_list']) && !empty($settings['content_list']) && count($settings['content_list'])): ?>
            <ul class="pricing-feature">
                <?php
                    foreach ($settings['content_list'] as $key => $ct_list): ?>
                    <li class="<?php if($ct_list['active'] == 'yes') { echo 'active'; } ?>"><i class="fac fac-dot-circle"></i><?php echo ct_print_html($ct_list['content'])?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if(!empty($button_text)) : ?>
            <div class="pricing-button">
                <a class="btn <?php if($style == 'style1') { echo 'btn-secondary'; } else { echo 'btn-preset3'; } ?>" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($button_text); ?></a>
            </div>
        <?php endif; ?>
        
    </div>
</div>