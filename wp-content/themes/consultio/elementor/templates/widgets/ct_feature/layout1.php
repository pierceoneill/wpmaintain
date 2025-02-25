<?php
$default_settings = [
    'title_text' => '',
    'description_text' => '',
    'style' => 'style1',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings); ?>
<div class="ct-feature-layout1 <?php echo esc_attr($style.' '.$ct_animate); ?>">
    <div class="item--holder">
        <h3 class="item--title"><i class="fac fac-angle-double-right"></i><?php echo esc_html($settings['title_text']); ?></h3>
        <div class="item--desc" ><?php echo ct_print_html($settings['description_text']); ?></div>
    </div>
</div>