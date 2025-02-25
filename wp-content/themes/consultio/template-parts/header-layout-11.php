<?php
/**
 * Template part for displaying default header layout
 */
$header_full = consultio_get_opt( 'header_full', false );
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$wellcome = consultio_get_opt( 'wellcome', '' );
$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_email = consultio_get_opt( 'h_email', '' );
$h_email_label = consultio_get_opt( 'h_email_label', '' );
$h_address_link = consultio_get_opt( 'h_address_link', '' );
$h_address_link_target = consultio_get_opt( 'h_address_link_target', '_self' );
$h_email_link = consultio_get_opt( 'h_email_link', '' );
$h_email_link_target = consultio_get_opt( 'h_email_link_target', '_self' );

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
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout11 fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?> <?php if($header_full == 1) { echo 'is-full-header'; } ?>">
        <div id="ct-header-middle">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <?php get_template_part( 'template-parts/header-branding' ); ?>
                    </div>
                    <div class="ct-header-holder">
                        <?php if(!empty($h_email)) : ?>
                            <div class="ct-header-info-item ct-header-call">
                                <div class="h-item-icon">
                                    <i class="fal fa-envelope"></i>
                                </div>
                                <div class="h-item-meta">
                                    <label><?php echo esc_attr($h_email_label); ?></label>
                                    <a href="mailto:<?php echo wp_kses_post($h_email); ?>"><?php echo wp_kses_post($h_email); ?></a>
                                </div>
                                <?php if(!empty($h_email_link)) : ?>
                                    <a href="mailto:<?php echo esc_attr($h_email_link); ?>" target="<?php echo esc_attr($h_email_link_target); ?>" class="h-item-link"></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($h_address)) : ?>
                            <div class="ct-header-info-item ct-header-address">
                                <div class="h-item-icon">
                                    <i class="fal fa-map-marker-alt"></i>
                                </div>
                                <div class="h-item-meta">
                                    <label><?php echo esc_attr($h_address_label); ?></label>
                                    <span><?php echo wp_kses_post($h_address); ?></span>
                                </div>
                                <?php if(!empty($h_address_link)) : ?>
                                    <a href="<?php echo esc_url($h_address_link); ?>" target="<?php echo esc_attr($h_address_link_target); ?>" class="h-item-link"></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($h_phone) || !empty($h_phone_label)) : ?>
                            <div class="ct-header-phone">
                                <div class="ct-header-phone-icon"><i class="fac fa-phone"></i></div>
                                <div class="ct-header-phone-meta">
                                    <label><?php echo esc_attr($h_phone_label); ?></label>
                                    <a href="tel:<?php echo esc_attr($h_phone); ?>"><?php echo esc_attr($h_phone); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="ct-header" class="ct-header-main">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <?php get_template_part( 'template-parts/header-branding' ); ?>
                    </div>
                    <div class="ct-header-navigation">
                        <div class="ct-header-navigation-bg">
                            <nav class="ct-main-navigation">
                                <div class="ct-main-navigation-inner">
                                    <?php if ($logo_mobile['url']) { ?>
                                        <div class="ct-logo-mobile">
                                            <a href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_mobile['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                        </div>
                                    <?php } ?>
                                    <?php consultio_header_mobile_search(); ?>
                                    <?php get_template_part( 'template-parts/header-menu' ); ?>
                                    <div class="ct-header-meta-mobile">
                                        <?php if(!empty($h_email)) : ?>
                                            <div class="ct-header-info-item ct-header-call">
                                                <div class="h-item-icon">
                                                    <i class="fal fa-envelope"></i>
                                                </div>
                                                <div class="h-item-meta">
                                                    <label><?php echo esc_attr($h_email_label); ?></label>
                                                    <a href="mailto:<?php echo wp_kses_post($h_email); ?>"><?php echo wp_kses_post($h_email); ?></a>
                                                </div>
                                                <?php if(!empty($h_email_link)) : ?>
                                                    <a href="mailto:<?php echo esc_attr($h_email_link); ?>" target="<?php echo esc_attr($h_email_link_target); ?>" class="h-item-link"></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($h_address)) : ?>
                                            <div class="ct-header-info-item ct-header-address">
                                                <div class="h-item-icon">
                                                    <i class="fal fa-map-marker-alt"></i>
                                                </div>
                                                <div class="h-item-meta">
                                                    <label><?php echo esc_attr($h_address_label); ?></label>
                                                    <span><?php echo wp_kses_post($h_address); ?></span>
                                                </div>
                                                <?php if(!empty($h_address_link)) : ?>
                                                    <a href="<?php echo esc_url($h_address_link); ?>" target="<?php echo esc_attr($h_address_link_target); ?>" class="h-item-link"></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($h_phone) || !empty($h_phone_label)) : ?>
                                            <div class="ct-header-phone">
                                                <div class="ct-header-phone-icon"><i class="fac fa-phone"></i></div>
                                                <div class="ct-header-phone-meta">
                                                    <label><?php echo esc_attr($h_phone_label); ?></label>
                                                    <a href="tel:<?php echo esc_attr($h_phone); ?>"><?php echo esc_attr($h_phone); ?></a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                        <div class="ct-header-button-mobile">
                                            <a class="btn btn-default btn-icon-fixed" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><i class="fac fac-share"></i><?php echo esc_attr( $h_btn_text ); ?></a>
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
                            </div>
                            <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                <div class="ct-header-button">
                                    <a class="btn btn-default btn-icon-fixed" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><i class="fac fac-share"></i><?php echo esc_attr( $h_btn_text ); ?></a>
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