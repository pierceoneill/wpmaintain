<?php
// Post term options
$post_term_options = ct_get_grid_term_options('post');
$slides_to_show = range( 1, 10 );
$slides_to_show = array_combine( $slides_to_show, $slides_to_show );
// Register Post Carousel Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_blog_carousel',
        'title' => esc_html__('Case Blog Carousel', 'consultio' ),
        'icon' => 'eicon-posts-carousel',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => array(
            'jquery-slick',
            'ct-post-carousel-widget-js',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'layout_section',
                    'label' => esc_html__('Layout', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'prefix_class' => 'ct-post-carousel-layout',
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'consultio' ),
                            'type' => Case_Theme_Core::LAYOUT_CONTROL,
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout4.jpg'
                                ],
                                '5' => [
                                    'label' => esc_html__('Layout 5', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout5.jpg'
                                ],
                                '6' => [
                                    'label' => esc_html__('Layout 6', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout6.jpg'
                                ],
                                '7' => [
                                    'label' => esc_html__('Layout 7', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout7.jpg'
                                ],
                                '8' => [
                                    'label' => esc_html__('Layout 8', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout8.jpg'
                                ],
                                '9' => [
                                    'label' => esc_html__('Layout 9', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_blog_carousel/layout-image/layout9.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default'       => 'style1',
                            'options'       => [
                                'style1'    => 'Style 1',
                                'style2'    => 'Style 2',
                                'style3'    => 'Style 3',
                                'style4'    => 'Style 4 (Dark)',
                                'style5'    => 'Style 5',
                                'style6'    => 'Style 6',
                            ],
                            'condition' => [
                                'layout' => ['1', '4'],
                            ],
                        ),
                        array(
                            'name' => 'style_l2',
                            'label' => esc_html__('Style', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default'       => 'style1',
                            'options'       => [
                                'style1'    => 'Style 1',
                                'style2'    => 'Style 2',
                            ],
                            'condition' => [
                                'layout' => '2',
                            ],
                        ),
                        array(
                            'name' => 'style_l7',
                            'label' => esc_html__('Style', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default'       => 'style1',
                            'options'       => [
                                'style1'    => 'Style 1',
                                'style2'    => 'Style 2',
                                'style3'    => 'Style 3',
                                'style4'    => 'Style 4',
                                'style5'    => 'Style 5',
                            ],
                            'condition' => [
                                'layout' => '7',
                            ],
                        ),
                        array(
                            'name' => 'thumbnail',
                            'type' => \Elementor\Group_Control_Image_Size::get_type(),
                            'control_type' => 'group',
                            'default' => 'full',
                        ),
                        array(
                            'name' => 'img_style',
                            'label' => esc_html__('Image Style', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default'       => 'filter',
                            'options'       => [
                                'default'    => 'Default',
                                'filter'    => 'Filter',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'source',
                            'label' => esc_html__('Select Categories', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $post_term_options,
                        ),
                        array(
                            'name' => 'orderby',
                            'label' => esc_html__('Order By', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'date',
                            'options' => [
                                'date' => esc_html__('Date', 'consultio' ),
                                'ID' => esc_html__('ID', 'consultio' ),
                                'author' => esc_html__('Author', 'consultio' ),
                                'title' => esc_html__('Case Title', 'consultio' ),
                                'rand' => esc_html__('Random', 'consultio' ),
                            ],
                        ),
                        array(
                            'name' => 'order',
                            'label' => esc_html__('Sort Order', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'desc',
                            'options' => [
                                'desc' => esc_html__('Descending', 'consultio' ),
                                'asc' => esc_html__('Ascending', 'consultio' ),
                            ],
                        ),
                        array(
                            'name' => 'limit',
                            'label' => esc_html__('Total items', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => '6',
                        ),
                    ),
                ),
                array(
                    'name' => 'display_section',
                    'label' => esc_html__('Display Options', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name'         => 'box_sh_color',
                            'label' => esc_html__( 'Box Shadow', 'consultio' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .ct-blog-carousel-layout4 .grid-item-inner:not(:hover)',
                            'condition' => [
                                'layout' => ['4'],
                            ],
                        ),
                        array(
                            'name' => 'show_date',
                            'label' => esc_html__('Show Date', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'show_category',
                            'label' => esc_html__('Show Category', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                            'condition' => [
                                'layout' => ['4','7','8','9'],
                            ],
                        ),
                        array(
                            'name' => 'show_author',
                            'label' => esc_html__('Show Author', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                            'condition' => [
                                'layout' => ['1','2','3','4','5','8'],
                            ],
                        ),
                        array(
                            'name' => 'show_button',
                            'label' => esc_html__('Show Button Readmore', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                            'condition' => [
                                'layout!' => ['8'],
                            ],
                        ),
                        array(
                            'name' => 'button_text',
                            'label' => esc_html__('Button Text', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'show_button' => 'true',
                                'layout!' => ['8'],
                            ],
                        ),
                        array(
                            'name' => 'show_excerpt',
                            'label' => esc_html__('Show Excerpt', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                            'condition' => [
                                'layout' => ['2','4','6', '7', '9'],
                            ],
                        ),
                        array(
                            'name' => 'num_words',
                            'label' => esc_html__('Number of Words', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 25,
                            'condition' => [
                                'show_excerpt' => 'true',
                                'layout' => ['2','4','6', '7', '9'],
                            ],
                            'separator' => 'after',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_carousel_settings',
                    'label' => esc_html__('Carousel', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'ct_animate',
                            'label' => esc_html__('Case Animate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'col_xs',
                            'label' => esc_html__('Columns XS Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_sm',
                            'label' => esc_html__('Columns SM Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_md',
                            'label' => esc_html__('Columns MD Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_lg',
                            'label' => esc_html__('Columns LG Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_xl',
                            'label' => esc_html__('Columns XL Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),

                        array(
                            'name' => 'slides_to_scroll',
                            'label' => esc_html__('Slides to scroll', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'arrows',
                            'label' => esc_html__('Show Arrows', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'false',
                        ),
                        array(
                            'name' => 'dots',
                            'label' => esc_html__('Show Dots', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'false',
                        ),
                        array(
                            'name' => 'dot_color_from',
                            'label' => esc_html__('Dot Color From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .slick-dots' => '--gradient-color-from: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '7',
                                'style_l7' => 'style5',
                            ],
                        ),
                        array(
                            'name' => 'dot_color_to',
                            'label' => esc_html__('Dot Color To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .slick-dots' => '--gradient-color-to: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '7',
                                'style_l7' => 'style5',
                            ],
                        ),
                        array(
                            'name' => 'dots_style',
                            'label' => esc_html__('Dots Style', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default'       => 'blog-layout6-default',
                            'options'       => [
                                'blog-layout6-default'    => 'Style 1',
                                'dot-style-u8'    => 'Style 2',
                            ],
                            'condition' => [
                                'layout' => ['6'],
                            ],
                        ),
                        array(
                            'name' => 'pause_on_hover',
                            'label' => esc_html__('Pause on Hover', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'autoplay',
                            'label' => esc_html__('Autoplay', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'false',
                        ),
                        array(
                            'name' => 'autoplay_speed',
                            'label' => esc_html__('Autoplay Speed', 'consultio'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 5000,
                            'condition' => [
                                'autoplay' => 'false'
                            ]
                        ),
                        array(
                            'name' => 'infinite',
                            'label' => esc_html__('Infinite Loop', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'speed',
                            'label' => esc_html__('Animation Speed', 'consultio'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 500,
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);