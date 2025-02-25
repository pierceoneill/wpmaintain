<?php 
$default_settings = [
    'label_menu' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<div class="ct-header-menu-popup"><span><?php if(!empty($label_menu)) { echo esc_attr($label_menu); } else { echo esc_html__('Menu', 'consultio'); } ?></span></div>