<?php
/**
 * Template part for displaying default header layout
 */
$h_topbar = consultio_get_opt( 'h_topbar', 'show' );
$header_full = consultio_get_opt( 'header_full', false );
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$wellcome = consultio_get_opt( 'wellcome', '' );

$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_address_link = consultio_get_opt( 'h_address_link', '' );
$h_address_link_target = consultio_get_opt( 'h_address_link_target', '_self' );

$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_phone_link = consultio_get_opt( 'h_phone_link', '' );
$h_phone_link_target = consultio_get_opt( 'h_phone_link_target', '_self' );

$h_email = consultio_get_opt( 'h_email', '' );
$h_email_label = consultio_get_opt( 'h_email_label', '' );
$h_email_link = consultio_get_opt( 'h_email_link', '' );
$h_email_link_target = consultio_get_opt( 'h_email_link_target', '_self' );

$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$language_switch = consultio_get_opt( 'language_switch', false );
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout15 fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?> <?php if($header_full == 1) { echo 'is-full-header'; } ?>">
        <?php if($h_topbar == 'show') : ?>
            <div id="ct-header-top" class="ct-header-top6">
                <div class="container">
                    <div class="row">
                        <?php if(!empty($wellcome)) : ?>
                            <div class="ct-header-wellcome">
                                <?php echo wp_kses_post($wellcome); ?>
                            </div>
                        <?php endif; ?>
                        <div class="ct-header-social">
                            <?php consultio_social_header(); ?>
                        </div>
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
                                    <i class="flaticon-telephone"></i>
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
                            <div class="ct-header-info-item ct-header-address">
                                <div class="h-item-icon">
                                    <i class="flaticonv3-envelope"></i>
                                </div>
                                <div class="h-item-meta">
                                    <label><?php echo esc_attr($h_email_label); ?></label>
                                    <span><?php echo wp_kses_post($h_email); ?></span>
                                </div>
                                <?php if(!empty($h_email_link)) : ?>
                                    <a href="mailto:<?php echo esc_attr($h_email_link); ?>" target="<?php echo esc_attr($h_email_link_target); ?>" class="h-item-link"></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($h_address)) : ?>
                            <div class="ct-header-info-item ct-header-address">
                                <div class="h-item-icon">
                                    <i class="flaticon-map"></i>
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
                                    <?php if ( has_nav_menu( 'secondary' ) ) { ?>
                                        <div class="h-menu-secondary-sm">
                                            <?php wp_nav_menu( array(
                                                'theme_location' => 'secondary',
                                                'menu_class' => 'ct-main-menu',
                                                'depth' => 2
                                            ) ); ?>
                                        </div>
                                    <?php } ?>

                                    <div class="ct-header-holder ct-header-holder-mobile">
                                        <?php if(!empty($h_phone)) : ?>
                                            <div class="ct-header-info-item ct-header-call">
                                                <div class="h-item-icon">
                                                    <i class="flaticon-telephone"></i>
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
                                            <div class="ct-header-info-item ct-header-address">
                                                <div class="h-item-icon">
                                                    <i class="flaticonv3-envelope"></i>
                                                </div>
                                                <div class="h-item-meta">
                                                    <label><?php echo esc_attr($h_email_label); ?></label>
                                                    <span><?php echo wp_kses_post($h_email); ?></span>
                                                </div>
                                                <?php if(!empty($h_email_link)) : ?>
                                                    <a href="mailto:<?php echo esc_attr($h_email_link); ?>" target="<?php echo esc_attr($h_email_link_target); ?>" class="h-item-link"></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($h_address)) : ?>
                                            <div class="ct-header-info-item ct-header-address">
                                                <div class="h-item-icon">
                                                    <i class="flaticon-map"></i>
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
                            </nav>
                            <div class="ct-header-meta">
                                <?php if($search_icon) : ?>
                                    <div class="header-right-item h-btn-search"><i class="fac fac-search"></i></div>
                                <?php endif; ?>
                                <?php if ( has_nav_menu( 'secondary' ) ) { ?>
                                    <div class="header-right-item h-menu-secondary">
                                        <?php wp_nav_menu( array(
                                            'theme_location' => 'secondary',
                                            'menu_class' => 'ct-secondary-menu',
                                            'depth' => 2
                                        ) ); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
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