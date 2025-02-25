<?php
$default_settings = [
    'title' => '',
    'sub_title' => '',
    'phone_number' => '',
    'btn_text' => '',
    'btn_link' => '',
    'btn_icon' => '',
    'footer_text' => '',
    'ct_animate' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
if ( ! empty( $btn_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $btn_link['url'] );

    if ( $btn_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $btn_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="ct-box-info1 <?php echo esc_attr($ct_animate); ?>">
	<div class="ct-box-inner">
		<div class="ct-box-title">
			<?php echo ct_print_html($title); ?>
		</div>
		<div class="ct-box-subtitle">
			<?php echo esc_attr($sub_title); ?>
		</div>
		<div class="ct-box-phonenumber">
			<a href="tel:<?php echo esc_attr($phone_number); ?>"><?php echo esc_attr($phone_number); ?></a>
		</div>
		<?php if(!empty($btn_text)) : ?>
			<div class="ct-box-button">
				<a <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>>
					<span>
						<?php if($is_new):
		                    \Elementor\Icons_Manager::render_icon( $btn_icon, [ 'aria-hidden' => 'true' ] );
		                    else: ?>
		                    <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
		                <?php endif; ?>
						<?php echo ct_print_html($btn_text); ?>		
					</span>
				</a>
			</div>
		<?php endif; ?>
		<div class="ct-box-footer">
			<?php echo esc_attr($footer_text); ?>
		</div>
	</div>
</div>