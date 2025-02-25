<?php 
$default_settings = [
    'style' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
?>
<div class="ct-icon-hidden-sidebar <?php echo esc_attr($style); ?>">
	<div class="item--inner h-btn-sidebar">
		<span class="icon-line"></span>
		<span class="icon-hidden-sidebar flaticonv3-menu"></span>
	</div>
</div>
<?php add_action( 'ct_anchor_target', 'consultio_hook_anchor_hidden_sidebar'); ?>