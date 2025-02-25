<?php
/**
 * Template part for displaying default header layout
 */
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
$logo_tagline = consultio_get_opt( 'logo_tagline' );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_time = consultio_get_opt( 'h_time', '' );
$h_time_label = consultio_get_opt( 'h_time_label', '' );

$h_phone_link = consultio_get_opt( 'h_phone_link', '' );
$h_phone_link_target = consultio_get_opt( 'h_phone_link_target', '_self' );
$h_address_link = consultio_get_opt( 'h_address_link', '' );
$h_address_link_target = consultio_get_opt( 'h_address_link_target', '_self' );
$h_time_link = consultio_get_opt( 'h_time_link', '' );
$h_time_link_target = consultio_get_opt( 'h_time_link_target', '_self' );

?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout8 <?php if($sticky_on == 1) { echo 'is-sticky'; } ?>">
        <div id="ct-header" class="ct-header-main">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <div class="ct-header-branding-inner">
                            <?php get_template_part( 'template-parts/header-branding' ); ?>
                            <?php if(!empty($logo_tagline)) : ?>
                                <div class="ct-logo-tagline"><?php echo esc_attr($logo_tagline); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ct-header-navigation-wrap">
                        <div class="ct-header-holder">
                            <?php if(!empty($h_address)) : ?>
                                <div class="ct-header-info-item ct-header-address">
                                    <div class="h-item-icon">
                                        <i class="far fa-map-marker-alt"></i>
                                    </div>
                                    <div class="h-item-meta">
                                        <label><?php echo esc_attr($h_address_label); ?></label>
                                        <span><?php echo esc_attr($h_address); ?></span>
                                    </div>
                                    <?php if(!empty($h_address_link)) : ?>
                                        <a href="<?php echo esc_url($h_address_link); ?>" target="<?php echo esc_attr($h_address_link_target); ?>" class="h-item-link"></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($h_phone)) : ?>
                                <div class="ct-header-info-item ct-header-call">
                                    <div class="h-item-icon">
                                        <i class="far fa-phone"></i>
                                    </div>
                                    <div class="h-item-meta">
                                        <label><?php echo esc_attr($h_phone_label); ?></label>
                                        <span><?php echo esc_attr($h_phone); ?></span>
                                    </div>
                                    <?php if(!empty($h_phone_link)) : ?>
                                        <a href="tel:<?php echo esc_attr($h_phone_link); ?>" target="<?php echo esc_attr($h_phone_link_target); ?>" class="h-item-link"></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($h_time)) : ?>
                                <div class="ct-header-info-item ct-header-time">
                                    <div class="h-item-icon">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div class="h-item-meta">
                                        <label><?php echo esc_attr($h_time_label); ?></label>
                                        <span><?php echo esc_attr($h_time); ?></span>
                                    </div>
                                    <?php if(!empty($h_time_link)) : ?>
                                        <a href="<?php echo esc_url($h_time_link); ?>" target="<?php echo esc_attr($h_time_link_target); ?>" class="h-item-link"></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="ct-header-info-item ct-header-social">
                                <?php consultio_social_header(); ?>
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
                                    <div class="ct-header-social-mobile ct-header-social">
                                        <?php consultio_social_header(); ?>
                                    </div>
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
                                <?php if($hidden_sidebar_icon && is_active_sidebar( 'sidebar-hidden' )) : ?>
                                    <div class="header-right-item h-btn-sidebar"><span></span></div>
                                <?php endif; ?>
                            </div>
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