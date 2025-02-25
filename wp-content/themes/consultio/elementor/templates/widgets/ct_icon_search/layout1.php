<?php 
$default_settings = [
    'selected_icon' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

$widget->add_render_attribute( 'selected_icon', 'class' );
if ( !empty( $selected_icon["value"] ) ) { 
    $widget->add_render_attribute( 'i', 'class', $selected_icon );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-search-popup">
	<?php if ( !empty( $selected_icon["value"] ) ) { ?>
        <?php if($is_new):
            \Elementor\Icons_Manager::render_icon( $selected_icon, [ 'aria-hidden' => 'true' ] );
            else: ?>
            <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
        <?php endif; ?>
    <?php } else { ?>
    	<i class="fac fac-search"></i>
    <?php } ?>
</div>
<?php add_action( 'ct_anchor_target', 'consultio_hook_anchor_search'); ?>