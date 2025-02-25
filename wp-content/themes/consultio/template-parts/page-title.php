<?php
$titles = consultio_get_page_titles();
$custom_pagetitle = consultio_get_page_opt( 'custom_pagetitle', 'themeoption');
$pagetitle = consultio_get_opt( 'pagetitle', 'show' );
$ptitle_type = consultio_get_opt( 'ptitle_type', 'layout' );
$ptitle_align = consultio_get_opt( 'ptitle_align', 'center' );
$p_title_layout = consultio_get_opt( 'p_title_layout' );

$c_ptitle_type = consultio_get_page_opt( 'c_ptitle_type', 'layout');
$c_p_title_layout = consultio_get_page_opt( 'c_p_title_layout');

if($custom_pagetitle != 'themeoption' && $custom_pagetitle != '' && $c_ptitle_type == 'layout') {
    $ptitle_type = $c_ptitle_type;
}

if($custom_pagetitle != 'themeoption' && $custom_pagetitle != '' && !empty($c_p_title_layout)) {
    $p_title_layout = $c_p_title_layout;
}

$ptitle_display = consultio_get_page_opt( 'ptitle_display', 'show' );
$ptitle_overlay = consultio_get_opt( 'ptitle_overlay', 'show' );
$page_ptitle_overlay = consultio_get_page_opt( 'ptitle_overlay', 'themeoption' );
if($custom_pagetitle != 'themeoption' && $custom_pagetitle != '' && isset($page_ptitle_overlay) && $page_ptitle_overlay != 'themeoption' && $page_ptitle_overlay != '') {
    $ptitle_overlay = $page_ptitle_overlay;
}
$ptitle_breadcrumb_page = consultio_get_page_opt( 'ptitle_breadcrumb_page', 'themeoption');
$ptitle_breadcrumb_on = consultio_get_opt( 'ptitle_breadcrumb_on', 'show' );
if($custom_pagetitle != 'themeoption' && $custom_pagetitle != '') {
    $pagetitle = $custom_pagetitle;
}
if($custom_pagetitle != 'themeoption' && $custom_pagetitle != '' && !empty($ptitle_breadcrumb_page) && $ptitle_breadcrumb_page != 'themeoption') {
    $ptitle_breadcrumb_on = $ptitle_breadcrumb_page;
}

$sub_title = consultio_get_page_opt( 'sub_title' );
$sub_title_position = consultio_get_page_opt( 'sub_title_position', 'bottom-title' );
ob_start();
if ( $titles['title'] )
{
    printf( '<h1 class="page-title">%s</h1>', wp_kses_post($titles['title']) );
}
$post_title_display = consultio_get_opt( 'post_title_display', 'p-title' );
$titles_html = ob_get_clean(); ?>

<?php if($pagetitle == 'show') : ?>
    <?php if($ptitle_type == 'layout') : ?>
        <div id="pagetitle" class="page-title bg-image <?php if($custom_pagetitle && !empty($ptitle_display) && $ptitle_display == 'hidden') { echo 'ptitle-hidden '; } if($ptitle_overlay == 'hidden') { echo 'overlay-hide'; } ?> text-<?php echo esc_attr($ptitle_align); ?>">
            <div class="container">
                <div class="page-title-inner">
                    
                    <div class="page-title-holder">
                        <?php if(!empty($sub_title)) : ?>
                            <h6 class="page-sub-title"><?php echo esc_attr($sub_title); ?></h6>
                        <?php endif; ?>
                        <?php if(is_singular('post') && $post_title_display == 'p-content') { echo '<div class="hidden-ptitle"></div>'; } ?>
                        <?php printf( '%s', wp_kses_post($titles_html)); ?>
                    </div>

                    <?php if($ptitle_breadcrumb_on == 'show') : ?>
                        <?php consultio_breadcrumb(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($ptitle_type == 'custom' && isset($p_title_layout) && !empty($p_title_layout)) : ?>
        <div id="pagetitle-elementor">
            <?php $post_main = get_post($p_title_layout);
            if (!is_wp_error($post_main) && $post_main->ID == $p_title_layout && class_exists('Case_Theme_Core') && function_exists('ct_print_html')){
                $content_main = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $p_title_layout );
                ct_print_html($content_main);
            } ?>
        </div>
    <?php endif; ?>
<?php endif; ?>