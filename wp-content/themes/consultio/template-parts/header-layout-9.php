<?php
/**
 * Template part for displaying default header layout
 */
$h_style1 = consultio_get_opt( 'h_style1', 'h-style1' );
$p_h_style1 = consultio_get_page_opt( 'p_h_style1', 'themeoption' );
$custom_header = consultio_get_page_opt( 'custom_header', false );
if($custom_header && $p_h_style1 != 'themeoption') {
    $h_style1 = $p_h_style1;
}
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$h_btn_on = consultio_get_opt( 'h_btn_on', 'hide' );
$h_btn_text = consultio_get_opt( 'h_btn_text' );
$h_btn_link_type = consultio_get_opt( 'h_btn_link_type', 'page' );
$h_btn_link = consultio_get_opt( 'h_btn_link' );
$h_btn_link_custom = consultio_get_opt( 'h_btn_link_custom' );
$h_btn_target = consultio_get_opt( 'h_btn_target', '_self' );
if($h_btn_link_type == 'page') {
    $h_btn_url = get_permalink($h_btn_link);
} else {
    $h_btn_url = $h_btn_link_custom;
}
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout9 <?php echo esc_attr($h_style1); ?> fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?>">
        <div id="ct-header" class="ct-header-main">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <div class="ct-header-branding-inner">
                            <?php get_template_part( 'template-parts/header-branding' ); ?>
                        </div>
                    </div>
                    <div class="ct-header-navigation">
                        <nav class="ct-main-navigation">
                            <div class="ct-main-navigation-inner">
                                <?php if ($logo_mobile['url']) { ?>
                                    <div class="ct-logo-mobile">
                                        <a href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_mobile['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                    </div>
                                <?php } ?>
                                <?php consultio_header_mobile_search(); ?>
                                <div class="ct-main-navigation-filter">
                                    <?php get_template_part( 'template-parts/header-menu' ); ?>
                                </div>
                                <?php if($h_btn_on == 'show' && !empty($h_btn_text) && $h_style1 == 'h-style2') : ?>
                                    <div class="ct-header-button-mobile">
                                        <a class="btn" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </nav>
                        <div class="ct-header-meta">
                            <?php if($search_icon) : ?>
                                <div class="header-right-item h-btn-search"><i class="fac fac-search"></i></div>
                            <?php endif; ?>
                            <?php if(class_exists('Woocommerce') && $cart_icon) : ?>
                                <div class="header-right-item h-btn-cart">
                                    <i class="fac fac-shopping-basket"></i>
                                    <span class="widget_cart_counter_header"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'consultio' ), WC()->cart->cart_contents_count ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if($hidden_sidebar_icon && is_active_sidebar( 'sidebar-hidden' ) && $h_style1 == 'h-style1' || $hidden_sidebar_icon && is_active_sidebar( 'sidebar-hidden' ) && $h_style1 == 'h-style3' || $hidden_sidebar_icon && is_active_sidebar( 'sidebar-hidden' ) && $h_style1 == 'h-style5') : ?>
                                <div class="header-right-item h-btn-sidebar"><span></span></div>
                            <?php endif; ?>

                            <?php if($h_btn_on == 'show' && !empty($h_btn_text) && $h_style1 == 'h-style2') : ?>
                                <div class="header-right-item ct-header-button">
                                    <a class="btn" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ct-menu-mobile">
                <?php if(class_exists('Woocommerce') && $cart_icon) : ?>
                    <span class="btn-nav-cart"><i class="fac fac-shopping-basket"></i></span>
                <?php endif; ?>
                <span class="btn-nav-mobile open-menu">
                    <span></span>
                </span>
            </div>
        </div>
    </div>
</header>