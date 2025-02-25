<?php
/**
 * Template part for displaying default header layout
 */
$header_type_page = consultio_get_page_opt( 'header_type', 'themeoption' );

$e_header_layout = consultio_get_opt('e_header_layout');
$e_header_layout_sticky = consultio_get_opt('e_header_layout_sticky');

$e_header_layout_page = consultio_get_page_opt('e_header_layout');
if($header_type_page == 'custom' ) {
    $e_header_layout = $e_header_layout_page;
}

$e_header_layout_sticky_page = consultio_get_page_opt('e_header_layout_sticky');
if($header_type_page == 'custom' ) {
    $e_header_layout_sticky = $e_header_layout_sticky_page;
}

$logo_m = consultio_get_opt( 'logo_mobile', array( 'url' => get_template_directory_uri().'/assets/images/logo-dark.png', 'id' => '' ) );
$e_logo_mobile = consultio_get_page_opt( 'e_logo_mobile' );
if($header_type_page == 'custom' && !empty($e_logo_mobile['url']) ) {
    $logo_m = $e_logo_mobile;
}

if(class_exists('\Elementor\Plugin')){
    $id = get_the_ID();
    if ( is_singular() && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
        $classes = 'ct-header-content';
    } else {
        $classes = 'container';
    }
} else {
    $classes = 'container';
}

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
<header id="ct-header-elementor" class="is-sticky">
	<?php if(isset($e_header_layout) && !empty($e_header_layout)) : ?>
		<div class="ct-header-elementor-main">
		    <div class="ct-header-content">
		        <div class="row">
		        	<div class="col-12">
			            <?php $post_main = get_post($e_header_layout);
	                    if (!is_wp_error($post_main) && $post_main->ID == $e_header_layout && class_exists('Case_Theme_Core') && function_exists('ct_print_html')){
	                        $content_main = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $e_header_layout );
	                        ct_print_html($content_main);
	                    } ?>
	                </div>
		        </div>
		    </div>
		</div>
	<?php endif; ?>
	<?php if(isset($e_header_layout_sticky) && !empty($e_header_layout_sticky)) : ?>
		<div class="ct-header-elementor-sticky">
		    <div class="container">
		        <div class="row">
		            <?php $post_sticky = get_post($e_header_layout_sticky);
	                    if (!is_wp_error($post_sticky) && $post_sticky->ID == $e_header_layout_sticky && class_exists('Case_Theme_Core') && function_exists('ct_print_html')){
	                        $content_sticky = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $e_header_layout_sticky );
	                        ct_print_html($content_sticky);
	                    } ?>
		        </div>
		    </div>
		</div>
	<?php endif; ?>
    <div class="ct-header-mobile">
        <div id="ct-header" class="ct-header-main ct-header-mobile-main">
            <div class="container">
                <div class="row">
                    <div class="ct-header-branding">
                        <?php get_template_part( 'template-parts/header-branding' ); ?>
                    </div>
                    <div class="ct-header-navigation">
                        <nav class="ct-main-navigation">
                            <div class="ct-main-navigation-inner">
                                <?php if ($logo_m['url']) { ?>
                                    <div class="ct-logo-mobile">
                                        <a href="<?php esc_url( esc_url( home_url( '/' ) ) ); ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo_m['url'] ); ?>" alt="<?php esc_attr( get_bloginfo( 'name' ) ); ?>"/></a>
                                    </div>
                                <?php } ?>
                                <?php consultio_header_mobile_search(); ?>
                                <?php get_template_part( 'template-parts/header-menu' ); ?>
                                <div class="ct-header-holder-mobile">
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
                                        <div class="ct-header-info-item ct-header-mail">
                                            <div class="h-item-icon">
                                                <i class="flaticonv3-envelope text-gradient"></i>
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
                                        <a class="btn btn-default" href="<?php echo esc_url( $h_btn_url ); ?>" target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr( $h_btn_text ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </nav>
                    </div>
                    <div class="ct-menu-overlay"></div>
                </div>
            </div>
            <div id="ct-menu-mobile">
                <div class="ct-mobile-meta-item btn-nav-mobile open-menu">
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</header>