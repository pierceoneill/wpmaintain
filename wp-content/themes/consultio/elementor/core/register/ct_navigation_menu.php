<?php
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
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
        'name' => 'ct_navigation_menu',
        'title' => esc_html__('Case Navigation Menu', 'consultio'),
        'icon' => 'eicon-menu-bar',
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
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'default' => 'Default',
                                'one-col-light' => '1 Column Light',
                                'tow-col-light' => '2 Columns Light',
                                'tow-col-light preset2' => '2 Columns Light - Preset 2',
                                'style-light1' => 'Style Light 1',
                                'style-light2' => 'Style Light 2',
                                'style-modern' => 'Modern',
                            ],
                            'default' => 'default',
                        ),
                        array(
                            'name' => 'link_color',
                            'label' => esc_html__('Link Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-navigation-menu1 ul.menu li a' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_color_hover',
                            'label' => esc_html__('Link Color Hover & Active', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-navigation-menu1 ul.menu li a:hover, {{WRAPPER}} .ct-navigation-menu1 ul.menu li.current_page_item > a, {{WRAPPER}} .ct-navigation-menu1 ul.menu li.current-menu-item > a' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'line_color_hover',
                            'label' => esc_html__('Line Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-navigation-menu1 ul.menu li a:after' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_typography',
                            'label' => esc_html__('Link Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-navigation-menu1 ul.menu li a',
                        ),
                        array(
                            'name' => 'item_space',
                            'label' => esc_html__('Item Spacer', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-navigation-menu1 ul li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);