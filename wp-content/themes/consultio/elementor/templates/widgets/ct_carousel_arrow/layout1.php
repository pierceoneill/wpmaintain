<?php
$default_settings = [
    'style' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings); ?>
<div class="ct-nav-carousel <?php echo esc_attr($style); ?>">
    <div class="nav-prev"><i class="fac fac-angle-left"></i></div>
    <div class="nav-next"><i class="fac fac-angle-right"></i></div>
</div>