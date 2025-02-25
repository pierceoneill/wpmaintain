<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Consultio
 */

get_header();
$sidebar_pos = '';
$show_sidebar_page = consultio_get_page_opt( 'show_sidebar_page', false );
if ($show_sidebar_page){
    $sidebar_pos = consultio_get_page_opt( 'sidebar_page_pos' );
}

if(class_exists('\Elementor\Plugin')){
    $id = get_the_ID();
    if ( is_singular() && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() && $sidebar_pos == '' ) {
        $classes = 'ct-page-content';
    } else {
        $classes = 'container';
    }
} else {
    $classes = 'container';
}

?>
    <div class="<?php echo esc_attr($classes); ?> content-container">
        <div class="row content-row">
            <div id="primary" <?php consultio_primary_class( $sidebar_pos, 'content-area' ); ?>>
                <main id="main" class="site-main">
                    <?php

                    while ( have_posts() )
                    {
                        the_post();

                        get_template_part( 'template-parts/content', 'page' );

                        if ( comments_open() || get_comments_number() )
                        {
                            comments_template();
                        }
                    }

                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php if ( 'left' == $sidebar_pos || 'right' == $sidebar_pos ) : ?>
                <aside id="secondary" <?php consultio_secondary_class( $sidebar_pos, 'widget-area' ); ?>>
                    <?php dynamic_sidebar( 'sidebar-page' ); ?>
                </aside>
            <?php endif; ?>

        </div>
    </div>
<?php
get_footer();