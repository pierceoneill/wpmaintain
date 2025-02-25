<?php
/**
 * Template part for displaying default header layout
 */
$sticky_on = consultio_get_opt( 'sticky_on', false );
$h_topbar = consultio_get_opt( 'h_topbar', 'show' );
$search_icon = consultio_get_opt( 'search_icon', false );
$cart_icon = consultio_get_opt( 'cart_icon', false );
$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}

$h_time = consultio_get_opt( 'h_time', '' );
$h_time_label = consultio_get_opt( 'h_time_label', '' );
$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_address_link = consultio_get_opt( 'h_address_link', '' );
$h_email = consultio_get_opt( 'h_email', '' );
$h_email_label = consultio_get_opt( 'h_email_label', '' );
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

?>
<header id="ct-masthead">
    <div id="ct-header-wrap" class="ct-header-layout22 fixed-height <?php if($sticky_on == 1) { echo 'is-sticky'; } ?>">
        <?php if($h_topbar == 'show') : ?>
            <div id="ct-header-top" class="ct-header-top12">
                <div class="container">
                    <div class="row">
                        <ul class="ct-header-holder">
                            <?php if(!empty($h_email)) : ?>
                                <li><a href="mailto:<?php echo esc_attr($h_email_link); ?>"><i class="fac fac-envelope"></i><?php echo esc_attr($h_email); ?></a></li>
                            <?php endif; ?>
                            <?php if(!empty($h_time)) : ?>
                                <li><i class="fac fac-clock"></i><?php echo esc_attr($h_time_label.' '.$h_time); ?></li>
                            <?php endif; ?>
                            <?php if(!empty($h_address)) : ?>
                                <li><a href="<?php echo esc_url($h_address_link); ?>"><i class="fac fac-map-marker-alt"></i><?php echo esc_attr($h_address_label.' '.$h_address); ?></a></li>
                            <?php endif; ?>
                        </ul>
                        <?php if($h_btn_on == 'show' && !empty($h_btn_text)) : ?>
                            <div class="ct-header-topbar-btn">
                                <a class="btn effect-left-to-right" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?><i class="flaticonv2 flaticonv2-right-arrow"></i></a>
                            </div>
                        <?php endif; ?>
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
                                        <a class="logo-mobile" href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_mobile['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                    </div>
                                <?php } ?>
                                <?php consultio_header_mobile_search(); ?>
                                <div class="ct-main-navigation-filter">
                                    <?php get_template_part( 'template-parts/header-menu' ); ?>
                                </div>
                            </div>
                        </nav>
                        <div class="ct-header-social">
                            <?php consultio_social_header(); ?>
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