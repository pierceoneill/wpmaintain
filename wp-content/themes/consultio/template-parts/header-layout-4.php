<?php
/**
 * Template part for displaying default header layout
 */
$sticky_on = consultio_get_opt( 'sticky_on', false );
$h_address = consultio_get_opt( 'h_address', '' );
$h_address_label = consultio_get_opt( 'h_address_label', '' );
$h_phone = consultio_get_opt( 'h_phone', '' );
$h_phone_label = consultio_get_opt( 'h_phone_label', '' );
$h_time = consultio_get_opt( 'h_time', '' );
$h_time_label = consultio_get_opt( 'h_time_label', '' );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
?>
<header id="ct-header-left">
    <div id="ct-header-wrap">
        <div class="ct-header-inner">
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
                        <?php get_template_part( 'template-parts/header-menu' ); ?>
                    </div>
                </nav>
            </div>
            <div class="ct-header-meta">
                <?php if(!empty($h_address)) : ?>
                    <div class="ct-header-address">
                        <div class="h-item-icon">
                            <i class="far fac-globe"></i>
                        </div>
                        <div class="h-item-meta">
                            <label><?php echo esc_attr($h_address_label); ?></label>
                            <span><?php echo esc_attr($h_address); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!empty($h_phone)) : ?>
                    <div class="ct-header-call">
                        <div class="h-item-icon">
                            <i class="far fac-phone"></i>
                        </div>
                        <div class="h-item-meta">
                            <label><?php echo esc_attr($h_phone_label); ?></label>
                            <span><?php echo esc_attr($h_phone); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!empty($h_time)) : ?>
                    <div class="ct-header-address">
                        <div class="h-item-icon">
                            <i class="far fac-clock"></i>
                        </div>
                        <div class="h-item-meta">
                            <label><?php echo esc_attr($h_time_label); ?></label>
                            <span><?php echo esc_attr($h_time); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="ct-header-social">
                <?php consultio_social_header(); ?>
            </div>
        </div>

        <div id="ct-menu-mobile">
            <span class="btn-nav-mobile open-menu">
                <span></span>
            </span>
        </div>
    </div>
</header>