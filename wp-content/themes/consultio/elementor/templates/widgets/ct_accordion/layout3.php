<?php
use Elementor\Icons_Manager;
Icons_Manager::enqueue_shim();
$default_settings = [
    'active_section' => '',
    'ct_accordion_l3' => '',
    'main_icon' => '',
    'icon_active' => '',
    'title_html_tag' => 'div',
    'ct_animate' => '',
    'box_icon' => '',
    'box_title' => '',
    'box_desc' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);
$html_id = ct_get_element_id($settings);
$active_section = intval($active_section);
$accordions = $widget->get_settings('ct_accordion_l3');
$widget->add_render_attribute( 'box_icon', 'class' );
$has_icon = ! empty( $box_icon );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $box_icon );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
if(!empty($accordions)) : ?>
<div class="ct-accordion-wrap <?php echo esc_attr($ct_animate); ?>">
    <div class="ct-accordion-top">
        <div class="ct-accordion-meta">
            <?php if($is_new):
                \Elementor\Icons_Manager::render_icon( $box_icon, [ 'aria-hidden' => 'true' ] );
                else: ?>
                <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
            <?php endif; ?>
            <h5><?php echo esc_attr($box_title); ?></h5>
        </div>
        <div class="ct-accordion-desc"><?php echo esc_attr($box_desc); ?></div>
    </div>
    <div id="<?php echo esc_attr($html_id); ?>" class="ct-accordion layout3">
        <?php foreach ($accordions as $key => $value):
            $is_active = ($key + 1) == $active_section;
            $_id = isset($value['_id']) ? $value['_id'] : '';
            $ac_title = isset($value['ac_title']) ? $value['ac_title'] : '';
            $ac_info = isset($value['ac_info']) ? $value['ac_info'] : '';
            $title_key = $widget->get_repeater_setting_key( 'ac_title', 'ct_accordion_l3', $key );
            $widget->add_render_attribute( $title_key, [
                'class' => [ 'ct-ac-title-text' ],
            ] );
            $widget->add_inline_editing_attributes( $title_key, 'basic' );

            $content_key = $widget->get_repeater_setting_key( 'ac_content', 'ct_accordion_l3', $key );
            $widget->add_render_attribute( $content_key, [
                'id' => $_id.$html_id,
                'class' => [ 'ct-ac-content' ],
            ] );
            if($is_active){
                $widget->add_render_attribute( $content_key, 'style', 'display:block;' );
            }
            $widget->add_inline_editing_attributes( $content_key, 'basic' );
            ?>
            <div class="ct-accordion-item <?php echo esc_attr($is_active?'active':''); ?>">
                <<?php ct_print_html($title_html_tag); ?> class="ct-ac-title <?php echo esc_attr($is_active?'active':''); ?>" data-target="<?php echo esc_attr('#' . $_id.$html_id); ?>">
                    <a <?php ct_print_html($widget->get_render_attribute_string( $title_key )); ?>>
                        <?php echo esc_html($ac_title); ?>
                    </a>
                </<?php ct_print_html($title_html_tag); ?>>
                <div <?php ct_print_html($widget->get_render_attribute_string( $content_key )); ?>>
                    <ul class="item--info">
                        <?php if(!empty($ac_info)):
                            $ac_info = json_decode($ac_info, true);
                            foreach ($ac_info as $value): ?>
                                <li><i class="<?php echo esc_attr($value['icon']); ?>"></i><?php echo esc_attr($value['content']); ?></li>
                            <?php endforeach;
                        endif; ?>
                    </ul>
                </div>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</div>
<?php endif; ?>