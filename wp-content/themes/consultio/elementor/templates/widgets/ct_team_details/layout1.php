<?php
$default_settings = [
    'image' => '',
    'title' => '',
    'position' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'icons' => '',
    'btn_text' => '',
    'btn_link' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
if ( ! empty( $btn_link['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $btn_link['url'] );

    if ( $btn_link['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $btn_link['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<div class="ct-team-details">
	<?php if(!empty($image)) : ?>
		<div class="ct-team-image bg-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);"></div>
	<?php endif; ?>
	<div class="ct-team-content bg-image">
		<div class="ct-team-holder">
			<div class="ct-team-icon"><i class="fac fac-user-cog"></i></div>
			<div class="ct-team-meta">
				<h3 class="ct-team-title"><?php echo esc_attr($title); ?></h3>
				<div class="ct-team-position"><?php echo esc_attr($position); ?></div>
			</div>
		</div>
		<ul class="ct-team-contact">
			<?php if(!empty($email)) : ?>
				<li><i class="fac fac-envelope"></i><?php echo esc_attr($email); ?></li>
			<?php endif; ?>
			<?php if(!empty($phone)) : ?>
				<li><i class="fac fac-phone"></i><?php echo esc_attr($phone); ?></li>
			<?php endif; ?>
			<?php if(!empty($address)) : ?>
				<li class="contact-address"><i class="fac fac-map-marker-alt"></i><?php echo esc_attr($address); ?></li>
			<?php endif; ?>
		</ul>
		<div class="ct-team-social">
			<?php if(!empty($btn_text)) : ?>
				<div class="ct-team-button">
					<a class="btn" <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($btn_text); ?></a>
				</div>
			<?php endif; ?>
			<?php if(isset($icons) && !empty($icons) && count($icons)): ?>
				<div class="ct-team-social-list">
					<?php foreach ($settings['icons'] as $key => $value):
			            $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
			            $has_icon = ! empty( $value['ct_icon'] );
			            $widget->add_render_attribute( $icon_key, [
			                'class' => $value['ct_icon'],
			                'aria-hidden' => 'true',
			            ] );

			            $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
			            if ( ! empty( $value['icon_link']['url'] ) ) {
			                $widget->add_render_attribute( $link_key, 'href', $value['icon_link']['url'] );

			                if ( $value['icon_link']['is_external'] ) {
			                    $widget->add_render_attribute( $link_key, 'target', '_blank' );
			                }

			                if ( $value['icon_link']['nofollow'] ) {
			                    $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
			                }
			            }
			            $link_attributes = $widget->get_render_attribute_string( $link_key );
			            ?>
			            <?php if ( $has_icon ) : ?>
			                <a <?php echo implode( ' ', [ $link_attributes ] ); ?>>
			                    <?php
			                        if($is_new):
			                            \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
			                    ?>
			                    <?php else: ?>
			                        <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
			                    <?php endif; ?>
			                </a>
			            <?php endif; ?>
			        <?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>