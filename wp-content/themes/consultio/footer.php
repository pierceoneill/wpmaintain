<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after.
 *
 * @package Consultio
 */ 
$back_totop_on = consultio_get_opt('back_totop_on', 'show');
$page_back_totop = consultio_get_page_opt('page_back_totop', 'themeoption');
if(isset($page_back_totop) && !empty($page_back_totop) && $page_back_totop != 'themeoption') {
	$back_totop_on = $page_back_totop;
}
?>
	</div><!-- #content inner -->
</div><!-- #content -->

<?php consultio_footer(); ?>
<?php if (isset($back_totop_on) && $back_totop_on == 'show') : ?>
    <a href="#" class="scroll-top"><i class="zmdi zmdi-long-arrow-up"></i></a>
<?php endif; ?>

</div><!-- #page -->
<?php consultio_icon_cart_bar(); ?>
<?php consultio_search_popup(); ?>
<?php consultio_sidebar_hidden(); ?>
<?php consultio_cart_sidebar(); ?>
<?php consultio_newsletter_popup(); ?>
<?php consultio_mouse_move_animation(); ?>
<?php consultio_header_elementor_popup(); ?>
<?php do_action( 'ct_anchor_target') ?>
<?php wp_footer(); ?>

</body>
</html>