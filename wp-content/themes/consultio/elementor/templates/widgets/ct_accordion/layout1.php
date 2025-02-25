<?php
use Elementor\Icons_Manager;
Icons_Manager::enqueue_shim();
$default_settings = [
    'active_section' => '',
    'ct_accordion' => '',
    'main_icon' => '',
    'icon_active' => '',
    'title_html_tag' => 'div',
    'ct_animate' => '',
    'btn_text' => '',
    'btn_link' => '',
    'style' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
$active_section = intval($active_section);
$accordions = $widget->get_settings('ct_accordion');
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
if(!empty($accordions)) : ?>
    <div id="<?php echo esc_attr($html_id); ?>" class="ct-accordion layout1 <?php echo esc_attr($style.' '.$ct_animate); ?>">
        <?php foreach ($accordions as $key => $value):
            $is_active = ($key + 1) == $active_section;
            $_id = isset($value['_id']) ? $value['_id'] : '';
            $ac_title = isset($value['ac_title']) ? $value['ac_title'] : '';
            $ac_content = isset($value['ac_content']) ? $value['ac_content'] : '';

            $title_key = $widget->get_repeater_setting_key( 'ac_title', 'ct_accordion', $key );
            $widget->add_render_attribute( $title_key, [
                'class' => [ 'ct-ac-title-text' ],
            ] );
            $widget->add_inline_editing_attributes( $title_key, 'basic' );

            $content_key = $widget->get_repeater_setting_key( 'ac_content', 'ct_accordion', $key );
            $widget->add_render_attribute( $content_key, [
                'id' => $_id.$html_id,
                'class' => [ 'ct-ac-content' ],
            ] );
            if($is_active){
                $widget->add_render_attribute( $content_key, 'style', 'display:block;' );
            }
            $widget->add_inline_editing_attributes( $content_key, 'basic' );
            $icon_key = $widget->get_repeater_setting_key( 'ct_icon', 'icons', $key );
            $has_icon = ! empty( $value['ct_icon'] );
            $widget->add_render_attribute( $icon_key, [
                'class' => $value['ct_icon'],
                'aria-hidden' => 'true',
            ] );
            ?>
            <div class="ct-accordion-item <?php echo esc_attr($is_active?'active':''); ?>">
                <<?php ct_print_html($title_html_tag); ?> class="ct-ac-title <?php echo esc_attr($is_active?'active':''); ?>" data-target="<?php echo esc_attr('#' . $_id.$html_id); ?>">
                    <a <?php ct_print_html($widget->get_render_attribute_string( $title_key )); ?>>
                        <?php
                            if($is_new):
                                \Elementor\Icons_Manager::render_icon( $value['ct_icon'], [ 'aria-hidden' => 'true' ] );
                        ?>
                        <?php else: ?>
                            <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                        <?php endif; ?>
                        <?php echo esc_html($ac_title); ?>
                        <i class="ct-icon-accordion"></i>
                    </a>
                </<?php ct_print_html($title_html_tag); ?>>
                <div <?php ct_print_html($widget->get_render_attribute_string( $content_key )); ?>><?php echo wp_kses_post(nl2br($ac_content)); ?></div>
            </div>
        <?php
            endforeach;
        ?>
    </div>
    <?php if(!empty($btn_text)) : ?>
        <div class="ct-accordion-button">
            <a <?php ct_print_html($widget->get_render_attribute_string( 'button' )); ?>><?php echo esc_attr($btn_text); ?><i class="flaticonv6-right-arrow"></i></a>
        </div>
    <?php endif; ?>
<?php endif; ?>