<?php
/**
 * The template for displaying all single portfolio
 *
 * @package Consultio
 */
get_header();
get_header();
if(class_exists('\Elementor\Plugin')){
    $id = get_the_ID();
    if ( is_singular() && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
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
        <div id="primary" class="col-12">
            <main id="main" class="site-main">
                <?php

                    while ( have_posts() )
                    {
                        the_post();
                        get_template_part( 'template-parts/content-portfolio/content', get_post_format() );
                        if ( comments_open() || get_comments_number() )
                        {
                            comments_template();
                        }
                    }
                    
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div>
</div>
<?php get_footer();
