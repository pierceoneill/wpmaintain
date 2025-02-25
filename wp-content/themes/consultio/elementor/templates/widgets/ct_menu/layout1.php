<?php
$default_settings = [
    'menu' => '',
    'style_l1' => '',
    'item_item_menu' => '',
    'line_item' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
$h_custom_main_menu = consultio_get_page_opt('h_custom_menu');
if(!empty($h_custom_main_menu)) {
    $menu = $h_custom_main_menu;
}

if(!empty($menu)) { ?>
    <div id="<?php echo esc_attr($html_id); ?>" class="ct-nav-menu ct-nav-menu1 <?php echo esc_attr($style); ?>">
        <?php wp_nav_menu(array(
            'menu_id'    => '',
            'menu_class' => 'ct-main-menu clearfix',
            'walker'     => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
            'link_before'     => '<span class="ct-menu-item">',
            'link_after'      => '</span>',
            'menu'        => wp_get_nav_menu_object($menu))
        ); ?>
    </div>
<?php } ?>