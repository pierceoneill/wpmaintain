<?php
// Register Banner Box Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_about_us',
        'title' => esc_html__('Case About Us', 'consultio' ),
        'icon' => 'eicon-site-logo',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'content_section',
                    'label' => esc_html__('Banner Box', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'sub_title',
                            'label' => esc_html__('Sub Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'sub_title_color',
                            'label' => esc_html__('Sub Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-about-subtitle' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-about-us .ct-about-subtitle span:before' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-about-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'desc',
                            'label' => esc_html__('Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'rows' => 10,
                            'show_label' => false,
                        ),
                        array(
                            'name' => 'desc_color',
                            'label' => esc_html__('Description Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-about-desc' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'bg',
                            'type' => \Elementor\Group_Control_Background::get_type(),
                            'control_type' => 'group',
                            'types'             => [ 'classic' , 'gradient' ],
                            'selector'          => '{{WRAPPER}} .ct-about-us .ct-about-holder'
                        ),
                        array(
                            'name' => 'icon_box',
                            'label' => esc_html__('Icon Box', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'ib_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'default' => [
                                        'value' => 'fas fa-star',
                                        'library' => 'fa-solid',
                                    ],
                                ),
                                array(
                                    'name' => 'ib_title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'ib_content',
                                    'label' => esc_html__('Content', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                ),
                            ),
                            'title_field' => '{{{ ib_title }}}',
                        ),
                        array(
                            'name' => 'box_icon_color',
                            'label' => esc_html__('Box Icon Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-box-item .ct-box-icon i' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'box_icon_title',
                            'label' => esc_html__('Box Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-box-item .ct-box-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'box_icon_desc',
                            'label' => esc_html__('Box Description Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-about-us .ct-box-item .ct-box-desc' => 'color: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);