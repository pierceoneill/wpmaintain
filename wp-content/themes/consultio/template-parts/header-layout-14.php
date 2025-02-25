<?php
/**
 * Template part for displaying default header layout
 */
$h_topbar = consultio_get_opt( 'h_topbar', 'show' );
$header_full = consultio_get_opt( 'header_full', false );
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_link = consultio_get_opt( 'h_phone_link', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_time = consultio_get_opt( 'h_time', '' );
$h_time_label = consultio_get_opt( 'h_time_label', '' );
$custom_header = consultio_get_page_opt( 'custom_header', '0' );
$page_h_phone = consultio_get_page_opt( 'page_h_phone', '' );
$page_h_time = consultio_get_page_opt( 'page_h_time', '' );
if($custom_header == '1' && !empty($page_h_phone)) {
    $h_phone = $page_h_phone;
}
if($custom_header == '1' && !empty($page_h_time)) {
    $h_time = $page_h_time;
}
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$sub_menu_displayed = consultio_get_opt( 'sub_menu_displayed', 'sub-hover' );
$h_custom_menu_left = consultio_get_page_opt( 'h_custom_menu_left' );
$h_custom_menu_right = consultio_get_page_opt( 'h_custom_menu_right' );
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout14 fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?> <?php if($header_full == 1) { echo 'is-full-header'; } ?>">
        <?php if($h_topbar == 'show') : ?>
            <div id="ct-header-top" class="ct-header-top5">
                <div class="container">
                    <div class="row">
                        <div class="ct-header-social">
                            <?php consultio_social_header(); ?>
                        </div>
                        <ul class="ct-header-holder">
                            <?php if(!empty($h_phone)) : ?>
                                <li><a href="tel:<?php echo esc_attr($h_phone_link ); ?>"><i class="fac fac-phone"></i><?php echo esc_attr($h_phone); ?></a></li>
                            <?php endif; ?>
                            <?php if(!empty($h_time)) : ?>
                                <li><i class="fac fac-clock"></i><?php echo esc_attr($h_time_label.' '.$h_time); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div id="ct-header" class="ct-header-main">
            <div class="container">
                <div class="row">
                    <div class="h-line"></div>
                    <div class="ct-header-navigation ct-header-navigation-left">
                        
                        <?php if($hidden_sidebar_icon && is_active_sidebar( 'sidebar-hidden' )) : ?>
                            <div class="ct-header-meta">
                                <div class="header-right-item h-btn-sidebar"><i class="fac fac-th-large"></i></div>
                            </div>
                        <?php endif; ?>
                        
                        <nav class="ct-main-navigation">
                            <div class="ct-main-navigation-inner">
                                <?php if ( has_nav_menu( 'menu-left' ) ) {
                                    $attr_menu = array(
                                        'theme_location' => 'menu-left',
                                        'container'  => '',
                                        'menu_id'    => 'ct-main-menu-left',
                                        'menu_class' => 'ct-main-menu '.$sub_menu_displayed.' clearfix',
                                        'link_before'     => '<span>',
                                        'link_after'      => '</span>',
                                        'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
                                    );
                                    if(isset($h_custom_menu_left) && !empty($h_custom_menu_left)) {
                                        $attr_menu['menu'] = $h_custom_menu_left;
                                    }
                                    wp_nav_menu( $attr_menu );
                                } ?>
                            </div>
                        </nav>
                    </div>
                    <div class="ct-header-branding">
                        <div class="ct-header-branding-inner">
                            <?php get_template_part( 'template-parts/header-branding' ); ?>
                        </div>
                    </div>
                    <div class="ct-header-navigation ct-header-navigation-right">
                        <nav class="ct-main-navigation">
                            <div class="ct-main-navigation-inner">
                                <?php if ($logo_mobile['url']) { ?>
                                    <div class="ct-logo-mobile">
                                        <a href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_mobile['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                    </div>
                                <?php } ?>
                                <?php consultio_header_mobile_search(); ?>
                                <?php if ( has_nav_menu( 'menu-left' ) ) {
                                    $attr_menu = array(
                                        'theme_location' => 'menu-left',
                                        'container'  => '',
                                        'menu_id'    => 'ct-main-menu-left-mobile',
                                        'menu_class' => 'ct-main-menu '.$sub_menu_displayed.' clearfix',
                                        'link_before'     => '<span>',
                                        'link_after'      => '</span>',
                                        'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
                                    );
                                    if(isset($h_custom_menu_left) && !empty($h_custom_menu_left)) {
                                        $attr_menu['menu'] = $h_custom_menu_left;
                                    }
                                    wp_nav_menu( $attr_menu );
                                } ?>
                                <?php if ( has_nav_menu( 'menu-right' ) ) {
                                    $attr_menu = array(
                                        'theme_location' => 'menu-right',
                                        'container'  => '',
                                        'menu_id'    => 'ct-main-menu-right',
                                        'menu_class' => 'ct-main-menu '.$sub_menu_displayed.' clearfix',
                                        'link_before'     => '<span>',
                                        'link_after'      => '</span>',
                                        'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
                                    );
                                    if(isset($h_custom_menu_right) && !empty($h_custom_menu_right)) {
                                        $attr_menu['menu'] = $h_custom_menu_right;
                                    }
                                    wp_nav_menu( $attr_menu );
                                } ?>
                                <div class="ct-header-social-mobile">
                                    <?php consultio_social_header(); ?>
                                </div>
                            </div>
                        </nav>
                        <?php if($search_icon) : ?>
                            <div class="ct-header-meta">
                                <div class="header-right-item h-btn-search"><i class="fac fac-search"></i></div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            <div id="ct-menu-mobile">
                <span class="btn-nav-mobile open-menu">
                    <span></span>
                </span>
            </div>
        </div>
    </div>
</header>