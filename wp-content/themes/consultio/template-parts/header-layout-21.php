<?php
/**
 * Template part for displaying default header layout
 */
$header_full = consultio_get_opt( 'header_full', false );
$h_topbar = consultio_get_opt( 'h_topbar', 'show' );
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$wellcome = consultio_get_opt( 'wellcome', '' );
$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_phone_link = consultio_get_opt( 'h_phone_link', '' );
$h_phone_link_target = consultio_get_opt( 'h_phone_link_target', '_self' );
$h_address_link = consultio_get_opt( 'h_address_link', '' );
$h_address_link_target = consultio_get_opt( 'h_address_link_target', '_self' );

$h_email = consultio_get_opt( 'h_email', '' );
$h_email_label = consultio_get_opt( 'h_email_label', '' );
$h_email_link = consultio_get_opt( 'h_email_link', '' );
$h_email_link_target = consultio_get_opt( 'h_email_link_target', '_self' );

$h_time = consultio_get_opt( 'h_time', '' );
$h_time_label = consultio_get_opt( 'h_time_label', '' );

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
$language_switch = consultio_get_opt( 'language_switch', false );
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout21 fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?> <?php if($header_full == 1) { echo 'is-full-header'; } ?>">
        
        <?php if($h_topbar == 'show') : ?>
            <div id="ct-header-top" class="ct-header-top11">
                <div class="container">
                    <div class="row">
                        <?php if($language_switch) : ?>
                            <?php if(class_exists('SitePress')) { ?>
                                <div class="site-header-lang">
                                    <?php do_action('wpml_add_language_selector'); ?>
                                </div>
                            <?php } else { 
                                wp_enqueue_style('wpml-style', get_template_directory_uri() . '/assets/css/style-lang.css', array(), '1.0.0');
                                ?>
                                <div class="site-header-lang custom">
                                    <div class="wpml-ls-statics-shortcode_actions wpml-ls wpml-ls-legacy-dropdown js-wpml-ls-legacy-dropdown">
                                        <ul>
                                            <li tabindex="0" class="wpml-ls-slot-shortcode_actions wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-first-item wpml-ls-item-legacy-dropdown">
                                                <a href="#" class="js-wpml-ls-item-toggle wpml-ls-item-toggle"><img class="wpml-ls-flag" src="<?php echo esc_url(get_template_directory_uri().'/assets/images/flag/en.png'); ?>" alt="en" title="English"><span class="wpml-ls-native"><?php echo esc_html__('English', 'consultio'); ?></span></a>
                                                <ul class="wpml-ls-sub-menu">
                                                    <li class="wpml-ls-slot-shortcode_actions wpml-ls-item wpml-ls-item-fr">
                                                        <a href="#" class="wpml-ls-link"><img class="wpml-ls-flag" src="<?php echo esc_url(get_template_directory_uri().'/assets/images/flag/fr.png'); ?>" alt="fr" title="France"><span class="wpml-ls-native"><?php echo esc_html__('France', 'consultio'); ?></span></a>
                                                    </li>
                                                    <li class="wpml-ls-slot-shortcode_actions wpml-ls-item wpml-ls-item-de wpml-ls-last-item">
                                                        <a href="#" class="wpml-ls-link"><img class="wpml-ls-flag" src="<?php echo esc_url(get_template_directory_uri().'/assets/images/flag/ru.png'); ?>" alt="ue" title="Russia"><span class="wpml-ls-native"><?php echo esc_html__('Russia', 'consultio'); ?></span></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php endif; ?>
                        <div class="ct-header-top-holder">
                            <?php if(!empty($h_time) || !empty($h_time_label)) : ?>
                                <div class="ct-header-time">
                                    <i class="fac fac-clock"></i>
                                    <span><?php echo esc_attr($h_time_label.' '.$h_time); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="ct-header-social">
                                <?php consultio_social_header(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div id="ct-header-middle">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <?php get_template_part( 'template-parts/header-branding' ); ?>
                    </div>
                    <div class="ct-header-holder">
                        <?php if(!empty($h_phone)) : ?>
                            <div class="ct-header-info-item ct-header-call">
                                <div class="h-item-icon">
                                    <i class="flaticon-telephone text-gradient"></i>
                                </div>
                                <div class="h-item-meta">
                                    <label><?php echo esc_attr($h_phone_label); ?></label>
                                    <span><?php echo wp_kses_post($h_phone); ?></span>
                                </div>
                                <?php if(!empty($h_phone_link)) : ?>
                                    <a href="tel:<?php echo esc_attr($h_phone_link); ?>" target="<?php echo esc_attr($h_phone_link_target); ?>" class="h-item-link"></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($h_email)) : ?>
                            <div class="ct-header-info-item ct-header-call">
                                <div class="h-item-icon">
                                    <i class="flaticon-black-back-closed-envelope-shape text-gradient"></i>
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
                                    <i class="flaticon-map text-gradient"></i>
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
                        <nav class="ct-main-navigation">
                            <div class="ct-main-navigation-inner">
                                <?php if ($logo_mobile['url']) { ?>
                                    <div class="ct-logo-mobile">
                                        <a href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_mobile['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                    </div>
                                <?php } ?>
                                <?php consultio_header_mobile_search(); ?>
                                <?php get_template_part( 'template-parts/header-menu' ); ?>
                                <div class="ct-header-holder ct-header-holder-mobile">
                                    <?php if(!empty($h_phone)) : ?>
                                        <div class="ct-header-info-item ct-header-call">
                                            <div class="h-item-icon">
                                                <i class="flaticon-telephone text-gradient"></i>
                                            </div>
                                            <div class="h-item-meta">
                                                <label><?php echo esc_attr($h_phone_label); ?></label>
                                                <span><?php echo wp_kses_post($h_phone); ?></span>
                                            </div>
                                            <?php if(!empty($h_phone_link)) : ?>
                                                <a href="tel:<?php echo esc_attr($h_phone_link); ?>" target="<?php echo esc_attr($h_phone_link_target); ?>" class="h-item-link"></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($h_email)) : ?>
                                        <div class="ct-header-info-item ct-header-call">
                                            <div class="h-item-icon">
                                                <i class="flaticon-black-back-closed-envelope-shape text-gradient"></i>
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
                                                <i class="flaticon-map text-gradient"></i>
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
                                </div>
                                <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                    <div class="ct-header-button-mobile">
                                        <a class="btn btn-default btn-effect" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?></a>
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
                            <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                <div class="header-right-item ct-header-btn">
                                    <a class="btn btn-default btn-effect" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?></a>
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