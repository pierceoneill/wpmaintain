<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_pricing_multi',
        'title' => esc_html__('Case Pricing Multi', 'consultio'),
        'icon' => 'eicon-settings',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'layout_section',
                    'label' => esc_html__('Layout', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'consultio' ),
                            'type' => Case_Theme_Core::LAYOUT_CONTROL,
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing_multi/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing_multi/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing_multi/layout-image/layout3.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_list_monthly',
                    'label' => esc_html__('Pricing Monthly', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'tab_title_monthly',
                            'label' => esc_html__('Tab Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'col_monthly',
                            'label' => esc_html__('Column', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                            ],
                            'default' => '4',
                        ),
                        array(
                            'name' => 'pricing_note',
                            'label' => esc_html__('Note', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['3'],
                            ],
                        ),
                        array(
                            'name' => 'ct_animate',
                            'label' => esc_html__('Case Animate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'content_monthly',
                            'label' => esc_html__('Content', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'condition' => [
                                'layout' => ['1','3'],
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'featured',
                                    'label' => esc_html__('Featured', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'no' => 'No',
                                        'yes' => 'Yes',
                                    ],
                                    'default' => 'no',
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'desc',
                                    'label' => esc_html__('Description', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                ),
                                array(
                                    'name' => 'time',
                                    'label' => esc_html__('Time', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'price',
                                    'label' => esc_html__('Price', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'feature',
                                    'label' => esc_html__( 'Feature', 'consultio' ),
                                    'type' => Case_Theme_Core::LIST_PRICING_CONTROL,
                                ),
                                array(
                                    'name' => 'button_text',
                                    'label' => esc_html__('Button Text', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'button_link',
                                    'label' => esc_html__('Button Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                        array(
                            'name' => 'content_monthly2',
                            'label' => esc_html__('Content', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'condition' => [
                                'layout' => '2',
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'ct_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'default' => [
                                        'value' => 'fas fa-star',
                                        'library' => 'fa-solid',
                                    ],
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'time',
                                    'label' => esc_html__('Time', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'price',
                                    'label' => esc_html__('Price', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'feature',
                                    'label' => esc_html__( 'Feature', 'consultio' ),
                                    'type' => Case_Theme_Core::LIST_PRICING_CONTROL,
                                ),
                                array(
                                    'name' => 'button_text',
                                    'label' => esc_html__('Button Text', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'button_link',
                                    'label' => esc_html__('Button Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_list_year',
                    'label' => esc_html__('Pricing Year', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'tab_title_year',
                            'label' => esc_html__('Tab Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'col_year',
                            'label' => esc_html__('Column', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                            ],
                            'default' => '4',
                        ),
                        array(
                            'name' => 'content_year',
                            'label' => esc_html__('Content', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'condition' => [
                                'layout' => ['1','3'],
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'featured',
                                    'label' => esc_html__('Featured', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'no' => 'No',
                                        'yes' => 'Yes',
                                    ],
                                    'default' => 'no',
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'desc',
                                    'label' => esc_html__('Description', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                ),
                                array(
                                    'name' => 'time',
                                    'label' => esc_html__('Time', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'price',
                                    'label' => esc_html__('Price', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'feature',
                                    'label' => esc_html__( 'Feature', 'consultio' ),
                                    'type' => Case_Theme_Core::LIST_PRICING_CONTROL,
                                ),
                                array(
                                    'name' => 'button_text',
                                    'label' => esc_html__('Button Text', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'button_link',
                                    'label' => esc_html__('Button Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                        array(
                            'name' => 'content_year2',
                            'label' => esc_html__('Content', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'condition' => [
                                'layout' => '2',
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'ct_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'default' => [
                                        'value' => 'fas fa-star',
                                        'library' => 'fa-solid',
                                    ],
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'time',
                                    'label' => esc_html__('Time', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'price',
                                    'label' => esc_html__('Price', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'feature',
                                    'label' => esc_html__( 'Feature', 'consultio' ),
                                    'type' => Case_Theme_Core::LIST_PRICING_CONTROL,
                                ),
                                array(
                                    'name' => 'button_text',
                                    'label' => esc_html__('Button Text', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'button_link',
                                    'label' => esc_html__('Button Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
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