<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_history',
        'title' => esc_html__('Case History', 'consultio'),
        'icon' => 'eicon-history',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'start_text',
                            'label' => esc_html__('Start Text', 'consultio'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => 'Start',
                        ),
                        array(
                            'name' => 'end_image',
                            'label' => esc_html__('End Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'history',
                            'label' => esc_html__('History', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'content',
                                    'label' => esc_html__('Content', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'item_link',
                                    'label' => esc_html__('Link', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'item_margin_bottom',
                                    'label' => esc_html__('Item Spacer Bottom', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SLIDER,
                                    'control_type' => 'responsive',
                                    'size_units' => [ 'px' ],
                                    'range' => [
                                        'px' => [
                                            'min' => 0,
                                            'max' => 1000,
                                        ],
                                    ],
                                    'selectors' => [
                                        '{{WRAPPER}} .ct-history1 .ct-history--holder {{CURRENT_ITEM}}' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                                    ],
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);