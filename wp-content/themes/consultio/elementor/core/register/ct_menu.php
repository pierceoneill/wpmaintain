<?php
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
$custom_menus = array(
    '' => esc_html__('Default', 'consultio')
);
if ( is_array( $menus ) && ! empty( $menus ) ) {
    foreach ( $menus as $single_menu ) {
        if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
            $custom_menus[ $single_menu->slug ] = $single_menu->name;
        }
    }
} else {
    $custom_menus = '';
}
ct_add_custom_widget(
    array(
        'name' => 'ct_menu',
        'title' => esc_html__('Case Nav Menu', 'consultio'),
        'icon' => 'eicon-nav-menu',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'scripts' => array(),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'menu',
                            'label' => esc_html__('Select Menu', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $custom_menus,
                        ),
                        array(
                            'name' => 'align',
                            'label' => esc_html__('Alignment', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => ['1'],
                            ],
                            'options' => [
                                'left' => [
                                    'title' => esc_html__('Case Left', 'consultio' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Case Center', 'consultio' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__('Case Right', 'consultio' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu' => 'text-align: {{VALUE}};',
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li' => 'float: none;',
                            ],
                        ),
                        array(
                            'name' => 'flex_grow',
                            'label' => esc_html__('Flex Grow', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'inherit' => [
                                    'title' => esc_html__( 'Inherit', 'consultio' ),
                                    'icon' => 'fas fa-arrows-alt-v',
                                ],
                                '1' => [
                                    'title' => esc_html__( 'Full', 'consultio' ),
                                    'icon' => 'fas fa-arrows-alt-h',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' => 'flex-grow: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'first_section',
                    'label' => esc_html__('Style First Level', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Default',
                                'style2' => 'Style Line',
                                'style3' => 'Style Hover Line 1',
                                'style4' => 'Style Hover Line 2',
                                'style5' => 'Style Box',
                                'style6' => 'Style Hover Line Gradient',
                            ],
                            'default' => 'style1',
                        ),
                        array(
                            'name' => 'color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li > a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-nav-menu .ct-menu--plus::before, {{WRAPPER}} .ct-nav-menu .ct-menu--plus::after' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'color_hover',
                            'label' => esc_html__('Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li > a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-menu-item-marker' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .ct-menu-item-marker::before' => 'border-color: {{VALUE}} transparent transparent transparent;',
                            ],
                        ),
                        array(
                            'name' => 'color_active',
                            'label' => esc_html__('Color Active', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li.current_page_parent > a, {{WRAPPER}} .ct-nav-menu .ct-main-menu > li.current_page_item > a, .ct-nav-menu .ct-main-menu > li a.is-one-page.ct-onepage-active' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-menu-item-marker' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'marker_color',
                            'label' => esc_html__('Marker Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-menu-item-marker:before' => 'border-color: {{VALUE}} transparent transparent transparent !important;',
                                '{{WRAPPER}} .ct-menu-item-marker' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'line_color',
                            'label' => esc_html__('Line Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu1 .ct-main-menu > li > a::before' => 'background: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'style' => ['style2','style4'],
                            ],
                        ),
                        array(
                            'name' => 'divider_color_from',
                            'label' => esc_html__('Divider Color From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu1.style6' => '--gradient-color-from: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style6'],
                            ],
                        ),
                        array(
                            'name' => 'divider_color_to',
                            'label' => esc_html__('Divider Color To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu1.style6' => '--gradient-color-to: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style6'],
                            ],
                        ),
                        array(
                            'name' => 'typography',
                            'label' => esc_html__('Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li > a',
                        ),
                        array(
                            'name' => 'item_space',
                            'label' => esc_html__('Item Space', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%', 'rem' ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .ct-nav-menu1.style2 .ct-main-menu > li::after' => 'right: -{{RIGHT}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'sub_section',
                    'label' => esc_html__('Style Sub Level', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'sub_color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li > a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'sub_color_hover',
                            'label' => esc_html__('Color Hover/Actvie', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li:hover > a, {{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li.current_page_item > a, {{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li.current-menu-item > a, {{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li.current_page_ancestor > a, {{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu li.current-menu-ancestor > a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'sub_typography',
                            'label' => esc_html__('Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-nav-menu .ct-main-menu li .sub-menu a',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);