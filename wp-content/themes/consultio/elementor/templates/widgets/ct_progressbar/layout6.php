<?php

if(isset($settings['progressbar_list']) && !empty($settings['progressbar_list'])): ?>
    <div class="ct-progressbar ct-progressbar6">
        <?php foreach ($settings['progressbar_list'] as $key => $progressbar):
            $wrapper_key = $widget->get_repeater_setting_key( 'wrapper', 'progressbar_list', $key );
            $progress_bar_key = $widget->get_repeater_setting_key( 'progress_bar', 'progressbar_list', $key );
            $inner_text_key = $widget->get_repeater_setting_key( 'inner_text', 'progressbar_list', $key );
            $widget->add_render_attribute( $progress_bar_key, [
                'class' => 'ct-progress-bar',
                'role' => 'progressbar',
                'data-valuetransitiongoal' => $progressbar['percent']['size'],
            ] );

            $widget->add_render_attribute( $inner_text_key, [
                'class' => 'ct-progress-text',
            ] );

            $widget->add_inline_editing_attributes( $inner_text_key ); ?>
            
            <div class="ct-progress-item">
                <?php if ( ! empty( $progressbar['title'] ) ) { ?>
                    <div class="ct-progress-title"><?php echo esc_html($progressbar['title']); ?></div>
                <?php } ?>
                <div class="ct-progress-meta">
                    <div class="ct-progress-holder">
                        <div <?php ct_print_html($widget->get_render_attribute_string( $progress_bar_key )); ?>></div>
                    </div>
                    <div class="ct-progress-percentage"><?php echo esc_html($progressbar['percent']['size']); ?>%</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>