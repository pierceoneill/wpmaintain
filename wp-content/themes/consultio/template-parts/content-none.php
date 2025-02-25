<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package Consultio
 */
$content_none_title = consultio_get_opt( 'content_none_title' );
$content_none_desc = consultio_get_opt( 'content_none_desc' );
?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php if(!empty($content_none_title)) { echo esc_attr($content_none_title); } else { esc_html_e( 'Nothing Found', 'consultio' ); } ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php if ( is_search() ) : ?>

            <p><?php if(!empty($content_none_desc)) { echo esc_attr($content_none_desc); } else { esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'consultio' ); } ?></p>
            
            <?php
            get_search_form();

        else : ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'consultio' ); ?></p>
            <?php
            get_search_form();

        endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->