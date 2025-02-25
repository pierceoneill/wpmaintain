<?php if(isset($settings['ct_text']) && !empty($settings['ct_text']) && count($settings['ct_text'])): ?>
	<div class="ct-text-effect ct-text-effect1 <?php echo esc_attr($settings['text_style']); ?>">
		<div class="ct-text--inner">
			<div class="ct-text--holder <?php echo esc_attr($settings['text_effect']); ?>" <?php if($settings['text_effect'] == 'ct-scroll-parallax') : ?>data-parallax='{"<?php echo esc_attr($settings['scroll_parallax_type']); ?>":<?php echo esc_attr($settings['parallax_scroll_value']); ?>}'<?php endif; ?> <?php if(!empty($settings['effect_speed'])) { ?>style="animation-duration:<?php echo esc_attr($settings['effect_speed']); ?>ms"<?php } ?>>
				<?php foreach ($settings['ct_text'] as $key => $value):
		            $text = isset($value['text']) ? $value['text'] : '';
		           	?>
		            <div class="ct-text--item">
		            	<div class="ct-text--content"><?php echo esc_attr($text); ?></div>
		            </div>
		        <?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>