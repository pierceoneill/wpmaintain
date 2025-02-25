<?php
// Register Button Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_menu_popup',
        'title' => esc_html__('Case Menu Popup', 'consultio' ),
        'icon' => 'eicon-nav-menu',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source Settings', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'label_menu',
                            'label' => esc_html__('Label', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'label_typography',
                            'label' => esc_html__('Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-header-menu-popup span',
                        ),
                        array(
                            'name' => 'label_color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-header-menu-popup span ' => 'color: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);