<?php
$elementor_templates = get_posts([
    'post_type' => 'elementor_library',
    'numberposts' => -1,
    'post_status' => 'publish',
]);
$elementor_templates_opt = [
    '' => esc_html__( 'Select Template', 'consultio' ),
];
if($elementor_templates){
    foreach ($elementor_templates as $template) {
        $elementor_templates_opt[$template->ID] = $template->post_title;
    }
}
$contact_forms = '';
if(class_exists('WPCF7')) {
    $cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');

    $contact_forms = array();
    if ($cf7) {
        foreach ($cf7 as $cform) {
            $contact_forms[$cform->ID] = $cform->post_title;
        }
    } else {
        $contact_forms[esc_html__('No contact forms found', 'consultio')] = 0;
    }
}
// Register Tabs Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_tabs',
        'title' => esc_html__( 'Case Tabs', 'consultio' ),
        'icon' => 'eicon-tabs',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
          'ct-tabs-widget-js',
        ],
        'params' => array(
            'sections' => array(
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
                                    'name' => 'content_type',
                                    'label' => esc_html__( 'Content Type', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'default' => 'text_editor',
                                    'options' => [
                                        'text_editor' => esc_html__( 'Text Editor', 'consultio' ),
                                        'template' => esc_html__( 'Template', 'consultio' ),
                                        'form' => esc_html__( 'Contact Form 7', 'consultio' ),
                                    ],
                                ),
                                array(
                                    'name' => 'tab_content',
                                    'label' => esc_html__( 'Content', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                                    'default' => esc_html__( 'Tab Content', 'consultio' ),
                                    'placeholder' => esc_html__( 'Tab Content', 'consultio' ),
                                    'show_label' => false,
                                    'condition' => [
                                        'content_type' => 'text_editor'
                                    ],
                                ),
                                array(
                                    'name' => 'tab_content_template',
                                    'label' => esc_html__( 'Template', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'default' => '',
                                    'options' => $elementor_templates_opt,
                                    'condition' => [
                                        'content_type' => 'template'
                                    ],
                                ),
                                array(
                                    'name' => 'form_id',
                                    'label' => esc_html__('Select Contact Form 7', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => $contact_forms,
                                    'condition' => [
                                        'content_type' => 'form'
                                    ],
                                ),
                            ),
                            'title_field' => '{{{ tab_title }}}',
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-tabs--layout1.style3 .ct-tab-title' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'tab_style' => 'style3',
                            ],
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Content Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-tabs--layout1.style3 .ct-tab-content' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'tab_style' => 'style3'
                            ],
                        ),
                        array(
                            'name' => 'tab_type',
                            'label' => esc_html__('Type', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'horizontal' => 'Horizontal',
                                'vertical' => 'Vertical Style 1',
                                'vertical2' => 'Vertical Style 2',
                            ],
                            'default' => 'horizontal',
                        ),
                        array(
                            'name' => 'title_color_type',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-tabs--layout2.type-vertical .ct-tab-title' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-tabs--layout2.type-vertical .ct-tabs-title' => 'border-color: {{VALUE}};',
                                '{{WRAPPER}} .ct-tabs--layout2.type-vertical .ct-tab-title i:before' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'tab_type' => 'vertical',
                            ],
                        ),
                        array(
                            'name' => 'content_color_type',
                            'label' => esc_html__('Content Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-tabs--layout2.type-vertical .ct-tab-content' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'tab_type' => 'vertical',
                            ],
                        ),
                        array(
                            'name' => 'tab_style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2 (Radio / Only 2 items)',
                                'style3' => 'Style 3',
                                'style4' => 'Style 4',
                                'style5' => 'Style 5',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'tab_type' => 'horizontal'
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-tabs .ct-tabs-title .ct-tab-title',
                        ),
                        array(
                            'name' => 'content_typography',
                            'label' => esc_html__('Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-tabs .ct-tab-content',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);