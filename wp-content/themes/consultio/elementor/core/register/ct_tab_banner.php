<?php
// Register Tabs Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_tab_banner',
        'title' => esc_html__( 'Case Tab Banner', 'consultio' ),
        'icon' => 'eicon-tabs',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
          'ct-tabs-widget-js',
        ],
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'layout_section',
                    'label' => esc_html__('Layout', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'prefix_class' => 'ct-tab-banner-layout',
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'consultio' ),
                            'type' => Case_Theme_Core::LAYOUT_CONTROL,
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_tab_banner/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_tab_banner/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_tab_banner/layout-image/layout3.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_tabs',
                    'label' => esc_html__( 'Tabs', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'active_tab',
                            'label' => esc_html__( 'Active Tab', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 1,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'tabs',
                            'label' => esc_html__( 'Tabs Items', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'condition' => [
                                'layout' => ['1','2'],
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'ct_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                                array(
                                    'name' => 'tab_title',
                                    'label' => esc_html__( 'Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => esc_html__( 'Tab Title', 'consultio' ),
                                    'placeholder' => esc_html__( 'Tab Title', 'consultio' ),
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'tab_content',
                                    'label' => esc_html__( 'Content', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                                    'default' => esc_html__( 'Tab Content', 'consultio' ),
                                    'placeholder' => esc_html__( 'Tab Content', 'consultio' ),
                                    'show_label' => false,
                                ),
                                array(
                                    'name' => 'banner',
                                    'label' => esc_html__( 'Image', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'description' => esc_html__('Select image.', 'consultio'),
                                ),
                            ),
                            'title_field' => '{{{ tab_title }}}',
                        ),
                        /* Layout 3 */
                        array(
                            'name' => 'tabs3',
                            'label' => esc_html__( 'Tabs Items', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'condition' => [
                                'layout' => ['3'],
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'ct_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                                array(
                                    'name' => 'tab_title',
                                    'label' => esc_html__( 'Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => esc_html__( 'Tab Title', 'consultio' ),
                                    'placeholder' => esc_html__( 'Tab Title', 'consultio' ),
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'tab_content',
                                    'label' => esc_html__( 'Content', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'default' => esc_html__( 'Tab Content', 'consultio' ),
                                    'placeholder' => esc_html__( 'Tab Content', 'consultio' ),
                                    'show_label' => false,
                                ),
                                array(
                                    'name' => 'banner',
                                    'label' => esc_html__( 'Image', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'description' => esc_html__('Select image.', 'consultio'),
                                ),
                                array(
                                    'name' => 'box_title',
                                    'label' => esc_html__( 'Box Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'box_subtitle',
                                    'label' => esc_html__( 'Box Sub Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'box_content',
                                    'label' => esc_html__( 'Box Content', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'show_label' => false,
                                ),
                                array(
                                    'name' => 'box_btn_text',
                                    'label' => esc_html__( 'Box Button Text', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'box_btn_link',
                                    'label' => esc_html__('Box Button Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
                                ),
                            ),
                            'title_field' => '{{{ tab_title }}}',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);