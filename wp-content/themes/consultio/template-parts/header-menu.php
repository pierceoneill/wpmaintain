<?php
/**
 * Template part for displaying the primary menu of the site
 */

$sub_menu_displayed = consultio_get_opt( 'sub_menu_displayed', 'sub-hover' );
$h_custom_menu = consultio_get_page_opt('h_custom_menu');
$icon_has_children = consultio_get_opt('icon_has_children');

if ( has_nav_menu( 'primary' ) ) {
    $attr_menu = array(
        'theme_location' => 'primary',
        'container'  => '',
        'menu_id'    => '',
        'menu_class' => 'ct-main-menu '.$sub_menu_displayed. ' children-'.$icon_has_children.' clearfix',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
    );
    if(isset($h_custom_menu) && !empty($h_custom_menu)) {
        $attr_menu['menu'] = $h_custom_menu;
    }
    wp_nav_menu( $attr_menu );
} else { ?>
    <ul class="ct-main-menu">
        <?php wp_list_pages( array(
            'depth'        => 0,
            'show_date'    => '',
            'date_format'  => get_option( 'date_format' ),
            'child_of'     => 0,
            'exclude'      => '',
            'title_li'     => '',
            'echo'         => 1,
            'authors'      => '',
            'sort_column'  => 'menu_order, post_title',
            'link_before'  => '',
            'link_after'   => '',
            'item_spacing' => 'preserve',
            'walker'       => '',
        ) ); ?>
    </ul>
<?php }