<?php
/**
 * Template part for displaying default header layout
 */
$sticky_on = consultio_get_opt( 'sticky_on', false );
$search_icon = consultio_get_opt( 'search_icon', false );
$logo_mobile = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$custom_header = consultio_get_page_opt('custom_header');
$p_logo_mobile = consultio_get_page_opt('p_logo_mobile');
if($custom_header && !empty($p_logo_mobile['url'])) {
    $logo_mobile['url'] = $p_logo_mobile['url'];
}
$get_revslide = consultio_get_opt( 'get_revslide' );
$header_layout = consultio_get_page_opt( 'header_layout' );
$get_revslide_page = consultio_get_page_opt( 'get_revslide' );
if($custom_header && $header_layout == '26' && !empty($get_revslide_page)) {
    $get_revslide = $get_revslide_page;
}

$hidden_logo = consultio_get_opt( 'hidden_logo', 'show' );
$p_hidden_logo = consultio_get_page_opt( 'hidden_logo', 'themeoption' );
if($custom_header && $header_layout == '26' && $p_hidden_logo != 'themeoption') {
    $hidden_logo = $p_hidden_logo;
}

?>
<header id="ct-masthead">
    
    <?php if (!empty($get_revslide)) { ?>
        <div class="ct-header-slider">
            <?php echo do_shortcode('[rev_slider alias="'.$get_revslide.'"][/rev_slider]'); ?>
        </div>
    <?php } ?>
    
    <div id="ct-header-wrap" class="ct-header-layout26 fixed-height <?php if (!empty($get_revslide)) { echo 'sub-show-top'; } ?> <?php if($sticky_on == 1) { echo 'is-sticky-offset'; } ?>">
        <div id="ct-header" class="ct-header-main">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding <?php if($hidden_logo == 'hide') { echo 'ct-header-branding-hide'; } ?>">
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
                        <div class="ct-header-meta">
                            <?php if($search_icon) : ?>
                                <div class="header-right-item h-btn-search"><i class="fac fac-search"></i></div>
                            <?php endif; ?>
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