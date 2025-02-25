<?php
/**
 * Template part for displaying default header layout
 */
$h_topbar = consultio_get_opt( 'h_topbar', 'show' );
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$wellcome = consultio_get_opt( 'wellcome', '' );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_link = consultio_get_opt( 'h_phone_link', '' );
$h_email = consultio_get_opt( 'h_email', '' );
$h_email_link = consultio_get_opt( 'h_email_link', '' );

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

$h_btn_on2 = consultio_get_opt( 'h_btn_on2', 'hide' );
$h_btn_text2 = consultio_get_opt( 'h_btn_text2' );
$h_btn_link_type2 = consultio_get_opt( 'h_btn_link_type2', 'page' );
$h_btn_link2 = consultio_get_opt( 'h_btn_link2' );
$h_btn_link_custom2 = consultio_get_opt( 'h_btn_link_custom2' );
$h_btn_target2 = consultio_get_opt( 'h_btn_target2', '_self' );
if($h_btn_link_type2 == 'page') {
    $h_btn_url2 = get_permalink($h_btn_link2);
} else {
    $h_btn_url2 = $h_btn_link_custom2;
}
?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout12 <?php if($sticky_on == 1) { echo 'is-sticky'; } ?>">
        <?php if($h_topbar == 'show') : ?>
            <div id="ct-header-top" class="ct-header-top3">
                <div class="container">
                    <div class="row">
                        <?php if(!empty($wellcome)) : ?>
                            <div class="ct-header-wellcome">
                                <?php echo ct_print_html($wellcome); ?>
                            </div>
                        <?php endif; ?>
                        <ul class="ct-header-holder">
                            <?php if(!empty($h_phone)) : ?>
                                <li><a href="tel:<?php echo esc_attr($h_phone_link); ?>"><i class="fac fac-phone"></i><?php echo esc_attr($h_phone); ?></a></li>
                            <?php endif; ?>
                            <?php if(!empty($h_email)) : ?>
                                <li><a href="mailto:<?php echo esc_attr($h_email_link); ?>"><i class="fac fac-envelope"></i><?php echo esc_attr($h_email); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
                                <?php get_template_part( 'template-parts/header-menu' ); ?>
                                <div class="ct-header-button-mobile">
                                    <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                        <a class="btn" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><i class="fac fa-file-alt space-right"></i><?php echo esc_attr( $h_btn_text ); ?></a>
                                    <?php endif; ?>
                                    <?php if($h_btn_on2 == 'show' && !empty($h_btn_text2)) : ?>
                                        <a class="btn" href="<?php echo esc_url( $h_btn_url2 ); ?>" target="<?php echo esc_attr($h_btn_target2); ?>"><i class="fac fa-phone space-right"></i><?php echo esc_attr( $h_btn_text2 ); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </nav>
                        <div class="ct-header-meta">
                            <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                                <div class="header-right-item">
                                    <a class="btn btn-default" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>">
                                        <i class="fac fa-file-alt space-right"></i>
                                        <?php echo esc_attr( $h_btn_text ); ?>
                                        <i class="fac fa-file-alt icon-abs"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if($h_btn_on2 == 'show' && !empty($h_btn_text2)) : ?>
                                <div class="header-right-item">
                                    <a class="btn btn-third2" href="<?php echo esc_url( $h_btn_url2 ); ?>" target="<?php echo esc_attr($h_btn_target2); ?>"><i class="fac fa-phone space-right"></i><?php echo esc_attr( $h_btn_text2 ); ?></a>
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