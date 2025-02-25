<?php
/**
 * Register metabox for posts based on Redux Framework. Supported methods:
 *     isset_args( $post_type )
 *     set_args( $post_type, $redux_args, $metabox_args )
 *     add_section( $post_type, $sections )
 * Each post type can contains only one metabox. Pease note that each field id
 * leads by an underscore sign ( _ ) in order to not show that into Custom Field
 * Metabox from WordPress core feature.
 *
 * @param  CT_Post_Metabox $metabox
 */

/**
 * Get list menu.
 * @return array
 */
function consultio_get_nav_menu(){

    $menus = array(
        '' => esc_html__('Default', 'consultio')
    );

    $obj_menus = wp_get_nav_menus();

    foreach ($obj_menus as $obj_menu){
        $menus[$obj_menu->term_id] = $obj_menu->name;
    }

    return $menus;
}


add_action( 'ct_post_metabox_register', 'consultio_page_options_register' );

function consultio_page_options_register( $metabox ) {

	if ( ! $metabox->isset_args( 'post' ) ) {
		$metabox->set_args( 'post', array(
			'opt_name'            => 'post_option',
			'display_name'        => esc_html__( 'Post Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'product' ) ) {
		$metabox->set_args( 'product', array(
			'opt_name'            => 'product_option',
			'display_name'        => esc_html__( 'Product Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'page' ) ) {
		$metabox->set_args( 'page', array(
			'opt_name'            => consultio_get_page_opt_name(),
			'display_name'        => esc_html__( 'Page Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'ct_pf_audio' ) ) {
		$metabox->set_args( 'ct_pf_audio', array(
			'opt_name'     => 'post_format_audio',
			'display_name' => esc_html__( 'Audio', 'consultio' ),
			'class'        => 'fully-expanded',
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'ct_pf_link' ) ) {
		$metabox->set_args( 'ct_pf_link', array(
			'opt_name'     => 'post_format_link',
			'display_name' => esc_html__( 'Link', 'consultio' )
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'ct_pf_quote' ) ) {
		$metabox->set_args( 'ct_pf_quote', array(
			'opt_name'     => 'post_format_quote',
			'display_name' => esc_html__( 'Quote', 'consultio' )
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'ct_pf_video' ) ) {
		$metabox->set_args( 'ct_pf_video', array(
			'opt_name'     => 'post_format_video',
			'display_name' => esc_html__( 'Video', 'consultio' ),
			'class'        => 'fully-expanded',
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'ct_pf_gallery' ) ) {
		$metabox->set_args( 'ct_pf_gallery', array(
			'opt_name'     => 'post_format_gallery',
			'display_name' => esc_html__( 'Gallery', 'consultio' ),
			'class'        => 'fully-expanded',
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	/* Extra Post Type */

	if ( ! $metabox->isset_args( 'service' ) ) {
		$metabox->set_args( 'service', array(
			'opt_name'            => 'service_option',
			'display_name'        => esc_html__( 'Service Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'courses' ) ) {
		$metabox->set_args( 'courses', array(
			'opt_name'            => 'courses_option',
			'display_name'        => esc_html__( 'Courses Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'portfolio' ) ) {
		$metabox->set_args( 'portfolio', array(
			'opt_name'            => 'portfolio_option',
			'display_name'        => esc_html__( 'Portfolio Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	if ( ! $metabox->isset_args( 'case-study' ) ) {
		$metabox->set_args( 'case-study', array(
			'opt_name'            => 'case_study_option',
			'display_name'        => esc_html__( 'Case Study Settings', 'consultio' ),
			'show_options_object' => false,
		), array(
			'context'  => 'advanced',
			'priority' => 'default'
		) );
	}

	/**
	 * Config post meta options
	 *
	 */
	$metabox->add_section( 'post', array(
		'title'  => esc_html__( 'Post Settings', 'consultio' ),
		'icon'   => 'el el-refresh',
		'fields' => array(
			array(
				'id'           => 'external_url',
				'type'         => 'text',
				'title'        => esc_html__( 'External URL', 'consultio' ),
			),
			array(
				'id'             => 'post_content_padding',
				'type'           => 'spacing',
				'output'         => array( '.single-post #content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'subtitle'     => esc_html__( 'Content site paddings.', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				)
			),
			array(
				'id'      => 'show_sidebar_post',
				'type'    => 'switch',
				'title'   => esc_html__( 'Custom Sidebar', 'consultio' ),
				'default' => false,
				'indent'  => true
			),
			array(
				'id'           => 'sidebar_post_pos',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Sidebar Position', 'consultio' ),
				'options'      => array(
					'left'  => esc_html__('Left', 'consultio'),
	                'right' => esc_html__('Right', 'consultio'),
	                'none'  => esc_html__('Disabled', 'consultio')
				),
				'default'      => 'right',
				'required'     => array( 0 => 'show_sidebar_post', 1 => '=', 2 => '1' ),
				'force_output' => true
			),
			array(
				'id'           => 'video_url',
				'type'         => 'text',
				'title'        => esc_html__( 'Video Url', 'consultio' ),
			),
			array(
				'id'      => 'custom_header',
				'type'    => 'switch',
				'title'   => esc_html__( 'Custom Layout Header', 'consultio' ),
				'default' => false,
				'indent'  => true
			),
			array(
				'id'           => 'header_layout',
				'type'         => 'image_select',
				'title'        => esc_html__( 'Header Layout', 'consultio' ),
				'subtitle'     => esc_html__( 'Select a layout for header.', 'consultio' ),
				'options'      => array(
					'0' => get_template_directory_uri() . '/assets/images/header-layout/h0.jpg',
					'1' => get_template_directory_uri() . '/assets/images/header-layout/h1.jpg',
					'2' => get_template_directory_uri() . '/assets/images/header-layout/h2.jpg',
					'3' => get_template_directory_uri() . '/assets/images/header-layout/h3.jpg',
					'4' => get_template_directory_uri() . '/assets/images/header-layout/h4.jpg',
					'5' => get_template_directory_uri() . '/assets/images/header-layout/h5.jpg',
					'6' => get_template_directory_uri() . '/assets/images/header-layout/h6.jpg',
					'7' => get_template_directory_uri() . '/assets/images/header-layout/h7.jpg',
					'8' => get_template_directory_uri() . '/assets/images/header-layout/h8.jpg',
					'9' => get_template_directory_uri() . '/assets/images/header-layout/h9.jpg',
					'10' => get_template_directory_uri() . '/assets/images/header-layout/h10.jpg',
					'11' => get_template_directory_uri() . '/assets/images/header-layout/h11.jpg',
					'12' => get_template_directory_uri() . '/assets/images/header-layout/h12.jpg',
					'13' => get_template_directory_uri() . '/assets/images/header-layout/h13.jpg',
					'14' => get_template_directory_uri() . '/assets/images/header-layout/h14.jpg',
					'15' => get_template_directory_uri() . '/assets/images/header-layout/h15.jpg',
					'16' => get_template_directory_uri() . '/assets/images/header-layout/h16.jpg',
					'17' => get_template_directory_uri() . '/assets/images/header-layout/h17.jpg',
					'18' => get_template_directory_uri() . '/assets/images/header-layout/h18.jpg',
					'19' => get_template_directory_uri() . '/assets/images/header-layout/h19.jpg',
					'20' => get_template_directory_uri() . '/assets/images/header-layout/h20.jpg',
					'21' => get_template_directory_uri() . '/assets/images/header-layout/h21.jpg',
					'22' => get_template_directory_uri() . '/assets/images/header-layout/h22.jpg',
					'23' => get_template_directory_uri() . '/assets/images/header-layout/h23.jpg',
					'24' => get_template_directory_uri() . '/assets/images/header-layout/h24.jpg',
					'25' => get_template_directory_uri() . '/assets/images/header-layout/h25.jpg',
					'26' => get_template_directory_uri() . '/assets/images/header-layout/h26.jpg',
				),
				'default'      => consultio_get_option_of_theme_options( 'header_layout' ),
				'required'     => array( 0 => 'custom_header', 1 => 'equals', 2 => '1' ),
				'force_output' => true
			),
			array(
				'id'           => 'custom_pagetitle',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Custom Page Title', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
			),
			array(
	            'id'       => 'ptitle_bg',
	            'type'     => 'background',
	            'background-color'     => false,
	            'background-repeat'     => false,
	            'background-size'     => false,
	            'background-attachment'     => false,
	            'background-position'     => false,
	            'title'    => esc_html__('Background', 'consultio'),
	            'subtitle' => esc_html__('Page title background image.', 'consultio'),
	            'required'     => array( 0 => 'custom_pagetitle', 1 => '=', 2 => 'show' ),
				'force_output' => true
	        ),
		)
	) );

	/**
	 * Config page meta options
	 *
	 */
	$metabox->add_section( 'page', array(
		'title'  => esc_html__( 'Header', 'consultio' ),
		'desc'   => esc_html__( 'Header settings for the page.', 'consultio' ),
		'icon'   => 'el-icon-website',
		'fields' => array(
			array(
	            'id'       => 'header_type',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Header Type', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'layout'  => esc_html__('Default Layout', 'consultio'),
	                'custom'  => esc_html__('Custom Layout Builder', 'consultio')
	            ),
	            'default'  => 'themeoption',
	        ),
	        /* Header Elementor */
            array(
	            'id'          => 'e_header_layout',
	            'type'        => 'select',
	            'title'       => esc_html__('Main Header Layout', 'consultio'),
	            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your header layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=header' ) ) . '">','</a>'),
	            'options'     => consultio_list_post('header'),
	            'default'     => '',
	            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'custom' ),
	        ),
	        array(
	            'id'          => 'e_header_layout_sticky',
	            'type'        => 'select',
	            'title'       => esc_html__('Sticky Header Layout', 'consultio'),
	            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your header layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=header' ) ) . '">','</a>'),
	            'options'     => consultio_list_post('header'),
	            'default'     => '',
	            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'custom' ),
	        ),
	        array(
	            'id'       => 'e_logo_mobile',
	            'type'     => 'media',
	            'title'    => esc_html__('Custom Logo Mobile', 'consultio'),
	            'default' => '',
	            'required'     => array( 0 => 'header_type', 1 => 'equals', 2 => 'custom' ),
				'force_output' => true
	        ),
	        /* End Header Elementor */
			array(
				'id'      => 'custom_header',
				'type'    => 'switch',
				'title'   => esc_html__( 'Custom Layout', 'consultio' ),
				'default' => false,
				'indent'  => true,
				'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
			),
			array(
				'id'           => 'header_layout',
				'type'         => 'image_select',
				'title'        => esc_html__( 'Layout', 'consultio' ),
				'subtitle'     => esc_html__( 'Select a layout for header.', 'consultio' ),
				'options'      => array(
					'0' => get_template_directory_uri() . '/assets/images/header-layout/h0.jpg',
					'1' => get_template_directory_uri() . '/assets/images/header-layout/h1.jpg',
					'2' => get_template_directory_uri() . '/assets/images/header-layout/h2.jpg',
					'3' => get_template_directory_uri() . '/assets/images/header-layout/h3.jpg',
					'4' => get_template_directory_uri() . '/assets/images/header-layout/h4.jpg',
					'5' => get_template_directory_uri() . '/assets/images/header-layout/h5.jpg',
					'6' => get_template_directory_uri() . '/assets/images/header-layout/h6.jpg',
					'7' => get_template_directory_uri() . '/assets/images/header-layout/h7.jpg',
					'8' => get_template_directory_uri() . '/assets/images/header-layout/h8.jpg',
					'9' => get_template_directory_uri() . '/assets/images/header-layout/h9.jpg',
					'10' => get_template_directory_uri() . '/assets/images/header-layout/h10.jpg',
					'11' => get_template_directory_uri() . '/assets/images/header-layout/h11.jpg',
					'12' => get_template_directory_uri() . '/assets/images/header-layout/h12.jpg',
					'13' => get_template_directory_uri() . '/assets/images/header-layout/h13.jpg',
					'14' => get_template_directory_uri() . '/assets/images/header-layout/h14.jpg',
					'15' => get_template_directory_uri() . '/assets/images/header-layout/h15.jpg',
					'16' => get_template_directory_uri() . '/assets/images/header-layout/h16.jpg',
					'17' => get_template_directory_uri() . '/assets/images/header-layout/h17.jpg',
					'18' => get_template_directory_uri() . '/assets/images/header-layout/h18.jpg',
					'19' => get_template_directory_uri() . '/assets/images/header-layout/h19.jpg',
					'20' => get_template_directory_uri() . '/assets/images/header-layout/h20.jpg',
					'21' => get_template_directory_uri() . '/assets/images/header-layout/h21.jpg',
					'22' => get_template_directory_uri() . '/assets/images/header-layout/h22.jpg',
					'23' => get_template_directory_uri() . '/assets/images/header-layout/h23.jpg',
					'24' => get_template_directory_uri() . '/assets/images/header-layout/h24.jpg',
					'25' => get_template_directory_uri() . '/assets/images/header-layout/h25.jpg',
					'26' => get_template_directory_uri() . '/assets/images/header-layout/h26.jpg',
				),
				'default'      => consultio_get_option_of_theme_options( 'header_layout' ),
				'required'     => array( 0 => 'custom_header', 1 => 'equals', 2 => '1' ),
				'force_output' => true
			),
			array(
	            'id'       => 'get_revslide',
	            'type'     => 'select',
	            'title'    => esc_html__('Select Slider Revolution', 'consultio'),
	            'options'  => consultio_build_shortcode_rev_slider(),
	            'default'  => '',
	            'required'     => array( 0 => 'header_layout', 1 => 'equals', 2 => '26' ),
	            'force_output' => true
	        ),
	        array(
				'id'           => 'hidden_logo',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Hidden Logon Main Header', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
				'required'     => array( 0 => 'header_layout', 1 => 'equals', 2 => '26' ),
	            'force_output' => true
			),
			array(
	            'id'       => 'p_h_style1',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Header Style', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'h-style1'  => esc_html__('Style 1 (Default)', 'consultio'),
	                'h-style2'  => esc_html__('Style 2 (Full width + Button)', 'consultio'),
	                'h-style3'  => esc_html__('Style 3 (Menu Center)', 'consultio'),
	                'h-style4'  => esc_html__('Style 4', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required'     => array( 0 => 'header_layout', 1 => 'equals', 2 => '9' ),
				'force_output' => true
	        ),
			array(
	            'id'       => 'p_logo_dark',
	            'type'     => 'media',
	            'title'    => esc_html__('Custom Logo Dark', 'consultio'),
	            'default' => '',
	            'required'     => array( 0 => 'custom_header', 1 => 'equals', 2 => '1' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'p_logo_light',
	            'type'     => 'media',
	            'title'    => esc_html__('Custom Logo Light', 'consultio'),
	            'default' => '',
	            'required'     => array( 0 => 'custom_header', 1 => 'equals', 2 => '1' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'p_logo_mobile',
	            'type'     => 'media',
	            'title'    => esc_html__('Custom Logo Mobile', 'consultio'),
	            'default' => '',
	            'required'     => array( 0 => 'custom_header', 1 => 'equals', 2 => '1' ),
				'force_output' => true
	        ),
	        array(
	            'id' => 'page_h_phone',
	            'type' => 'text',
	            'title' => esc_html__('Custom Phone Number', 'consultio'),
	            'default' => '',
	            'required' => array( 0 => 'header_layout', 1 => 'equals', 2 => '6' ),
            	'force_output' => true
	        ),
	        array(
	            'id' => 'page_h_time',
	            'type' => 'text',
	            'title' => esc_html__('Custom Time', 'consultio'),
	            'default' => '',
	            'required'     => array( 0 => 'header_layout', 1 => 'equals', 2 => '6' ),
				'force_output' => true
	        ),
	        array(
                'id'       => 'h_custom_menu',
                'type'     => 'select',
                'title'    => esc_html__( 'Custom Main Menu', 'consultio' ),
                'subtitle' => esc_html__( 'Custom menu for current page.', 'consultio' ),
                'options'  => consultio_get_nav_menu(),
                'default' => '',
            ),
            array(
                'id'       => 'h_custom_menu_left',
                'type'     => 'select',
                'title'    => esc_html__( 'Custom Menu Left', 'consultio' ),
                'subtitle' => esc_html__( 'Custom menu for current page.', 'consultio' ),
                'options'  => consultio_get_nav_menu(),
                'default' => '',
                'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
            ),
            array(
                'id'       => 'h_custom_menu_right',
                'type'     => 'select',
                'title'    => esc_html__( 'Custom Menu Right', 'consultio' ),
                'subtitle' => esc_html__( 'Custom menu for current page.', 'consultio' ),
                'options'  => consultio_get_nav_menu(),
                'default' => '',
                'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
            ),
		)
	) );

	$metabox->add_section( 'page', array(
		'title'  => esc_html__( 'Page Title', 'consultio' ),
		'icon'   => 'el el-indent-left',
		'fields' => array(
			array(
				'id'           => 'custom_pagetitle',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Page Title', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
			),

			array(
	            'id'       => 'c_ptitle_type',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Page Title Type', 'consultio'),
	            'options'  => array(
	                'layout'  => esc_html__('Default Layout', 'consultio'),
	                'custom'  => esc_html__('Custom Layout Builder', 'consultio')
	            ),
	            'default'  => 'layout',
	            'required' => array( 0 => 'custom_pagetitle', 1 => 'equals', 2 => 'show' ),
	        ),

	        array(
	            'id'          => 'c_p_title_layout',
	            'type'        => 'select',
	            'title'       => esc_html__('Page Title Layout', 'consultio'),
	            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your page title layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=page-title' ) ) . '">','</a>'),
	            'options'     => consultio_list_post('page-title'),
	            'default'     => '',
	            'required' => array( 0 => 'c_ptitle_type', 1 => 'equals', 2 => 'custom' ),
	        ),

			array(
				'id'           => 'custom_title',
				'type'         => 'textarea',
				'title'        => esc_html__( 'Title', 'consultio' ),
				'subtitle'     => esc_html__( 'Use custom title for this page. The default title will be used on document title.', 'consultio' ),
				'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
				'force_output' => true
			),
			array(
	            'id'       => 'ptitle_display',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Title Display', 'consultio'),
	            'options'  => array(
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'show',
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
	            'force_output' => true
	        ),
			array(
	            'id'       => 'ptitle_bg',
	            'type'     => 'background',
	            'background-color'     => false,
	            'background-repeat'     => false,
	            'background-size'     => false,
	            'background-attachment'     => false,
	            'background-position'     => false,
	            'title'    => esc_html__('Background', 'consultio'),
	            'subtitle' => esc_html__('Page title background image.', 'consultio'),
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
				'force_output' => true
	        ),
	        array(
	            'id'          => 'p_page_title_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Title Color', 'consultio'),
	            'transparent' => false,
	            'default'     => '',
	            'output'    => array('body #pagetitle .page-title'),
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_overlay',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Overlay', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
	            'force_output' => true
	        ),
	        array(
	            'id'             => 'ptitle_padding',
	            'type'           => 'spacing',
	            'output'         => array('.site #pagetitle.page-title'),
	            'right'   => false,
	            'left'    => false,
	            'mode'           => 'padding',
	            'units'          => array('px'),
	            'units_extended' => 'false',
	            'title'          => esc_html__('Page Title Padding', 'consultio'),
	            'default'            => array(
	                'padding-top'   => '',
	                'padding-bottom'   => '',
	                'units'          => 'px',
	            ),
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_breadcrumb_page',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Breadcrumb', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
	            'force_output' => true
	        ),
	        array(
	            'id'          => 'ptitle_breadcrumb_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Breadcrumb Color', 'consultio'),
	            'transparent' => false,
	            'default'     => '',
	            'output'    => array('body #pagetitle .ct-breadcrumb'),
	            'required'     => array( 0 => 'c_ptitle_type', 1 => '=', 2 => 'layout' ),
				'force_output' => true
	        ),
		)
	) );

	$metabox->add_section( 'page', array(
		'title'  => esc_html__( 'Content', 'consultio' ),
		'desc'   => esc_html__( 'Settings for content area.', 'consultio' ),
		'icon'   => 'el-icon-pencil',
		'fields' => array(
			array(
				'id'       => 'content_bg_color',
				'type'     => 'color_rgba',
				'title'    => esc_html__( 'Background Color', 'consultio' ),
				'subtitle' => esc_html__( 'Content background color.', 'consultio' ),
				'output'   => array( 'background-color' => 'body .site-content, body .footer-bg-content' )
			),
			array(
	            'id'       => 'p_bg_content',
	            'type'     => 'background',
	            'title'    => esc_html__('Background Image', 'consultio'),
	            'output'   => array('body .site-content'),
	             'background-color'     => false,
	        ),
			array(
				'id'             => 'content_padding',
				'type'           => 'spacing',
				'output'         => array( '#content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				)
			),
			array(
				'id'      => 'show_sidebar_page',
				'type'    => 'switch',
				'title'   => esc_html__( 'Show Sidebar', 'consultio' ),
				'default' => false,
				'indent'  => true
			),
			array(
				'id'           => 'sidebar_page_pos',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Sidebar Position', 'consultio' ),
				'options'      => array(
					'left'  => esc_html__( 'Left', 'consultio' ),
					'right' => esc_html__( 'Right', 'consultio' ),
				),
				'default'      => 'right',
				'required'     => array( 0 => 'show_sidebar_page', 1 => '=', 2 => '1' ),
				'force_output' => true
			),
			array(
				'id'           => 'loading_page',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Loading', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'custom' => esc_html__( 'Cuttom', 'consultio' ),
				),
				'default'      => 'themeoption',
			),
			array(
	            'id'       => 'loading_type',
	            'type'     => 'select',
	            'title'    => esc_html__('Loading Type', 'consultio'),
	            'options'  => array(
	                'style1'  => esc_html__('Style 1', 'consultio'),
	                'style2'  => esc_html__('Style 2', 'consultio'),
	                'style3'  => esc_html__('Style 3', 'consultio'),
	                'style4'  => esc_html__('Style 4', 'consultio'),
	                'style5'  => esc_html__('Style 5', 'consultio'),
	                'style6'  => esc_html__('Style 6', 'consultio'),
	                'style7'  => esc_html__('Style 7', 'consultio'),
	                'style8'  => esc_html__('Style 8', 'consultio'),
	                'style9'  => esc_html__('Style 9', 'consultio'),
	                'style10'  => esc_html__('Style 10', 'consultio'),
	            ),
	            'default'  => 'style1',
	            'required'     => array( 0 => 'loading_page', 1 => '=', 2 => 'custom' ),
				'force_output' => true
	        ),
		)
	) );

	$metabox->add_section( 'page', array(
		'title'  => esc_html__( 'Footer', 'consultio' ),
		'desc'   => esc_html__( 'Settings for footer area.', 'consultio' ),
		'icon'   => 'el el-website',
		'fields' => array(
			array(
				'id'      => 'custom_footer',
				'type'    => 'switch',
				'title'   => esc_html__( 'Custom Layout', 'consultio' ),
				'default' => false,
				'indent'  => true
			),
	        array(
	            'id'          => 'footer_layout_custom',
	            'type'        => 'select',
	            'title'       => esc_html__('Layout', 'consultio'),
	            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your custom footer layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=footer' ) ) . '">','</a>'),
	            'options'     =>consultio_list_post('footer'),
	            'default'     => '',
	            'required' => array( 0 => 'custom_footer', 1 => 'equals', 2 => '1' ),
	            'force_output' => true
	        ),
	        array(
				'id'           => 'page_back_totop',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Back to Top Button', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
			),
	    )
	) );

	$metabox->add_section( 'page', array(
		'title'  => esc_html__( 'Colors', 'consultio' ),
		'icon'   => 'el-icon-file-edit',
		'fields' => array(
			array(
	            'id'          => 'p_primary_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Primary Color', 'consultio'),
	            'transparent' => false,
	            'default'     => ''
	        ),
	        array(
	            'id'          => 'p_secondary_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Secondary Color', 'consultio'),
	            'transparent' => false,
	            'default'     => ''
	        ),
	        array(
	            'id'          => 'p_third_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Third Color', 'consultio'),
	            'transparent' => false,
	            'default'     => ''
	        ),
		)
	) );

	/**
	 * Config post format meta options
	 *
	 */

	$metabox->add_section( 'ct_pf_video', array(
		'title'  => esc_html__( 'Video', 'consultio' ),
		'fields' => array(
			array(
				'id'    => 'post-video-url',
				'type'  => 'text',
				'title' => esc_html__( 'Video URL', 'consultio' ),
				'desc'  => esc_html__( 'YouTube or Vimeo video URL', 'consultio' )
			),

			array(
				'id'    => 'post-video-file',
				'type'  => 'editor',
				'title' => esc_html__( 'Video Upload', 'consultio' ),
				'desc'  => esc_html__( 'Upload video file', 'consultio' )
			),

			array(
				'id'    => 'post-video-html',
				'type'  => 'textarea',
				'title' => esc_html__( 'Embadded video', 'consultio' ),
				'desc'  => esc_html__( 'Use this option when the video does not come from YouTube or Vimeo', 'consultio' )
			)
		)
	) );

	$metabox->add_section( 'ct_pf_gallery', array(
		'title'  => esc_html__( 'Gallery', 'consultio' ),
		'fields' => array(
			array(
				'id'       => 'post-gallery-lightbox',
				'type'     => 'switch',
				'title'    => esc_html__( 'Lightbox?', 'consultio' ),
				'subtitle' => esc_html__( 'Enable lightbox for gallery images.', 'consultio' ),
				'default'  => true
			),
			array(
				'id'       => 'post-gallery-images',
				'type'     => 'gallery',
				'title'    => esc_html__( 'Gallery Images ', 'consultio' ),
				'subtitle' => esc_html__( 'Upload images or add from media library.', 'consultio' )
			)
		)
	) );

	$metabox->add_section( 'ct_pf_audio', array(
		'title'  => esc_html__( 'Audio', 'consultio' ),
		'fields' => array(
			array(
				'id'          => 'post-audio-url',
				'type'        => 'text',
				'title'       => esc_html__( 'Audio URL', 'consultio' ),
				'description' => esc_html__( 'Audio file URL in format: mp3, ogg, wav.', 'consultio' ),
				'validate'    => 'url',
				'msg'         => 'Url error!'
			)
		)
	) );

	$metabox->add_section( 'ct_pf_link', array(
		'title'  => esc_html__( 'Link', 'consultio' ),
		'fields' => array(
			array(
				'id'       => 'post-link-url',
				'type'     => 'text',
				'title'    => esc_html__( 'URL', 'consultio' ),
				'validate' => 'url',
				'msg'      => 'Url error!'
			)
		)
	) );

	$metabox->add_section( 'ct_pf_quote', array(
		'title'  => esc_html__( 'Quote', 'consultio' ),
		'fields' => array(
			array(
				'id'    => 'post-quote-cite',
				'type'  => 'text',
				'title' => esc_html__( 'Cite', 'consultio' )
			)
		)
	) );

	/**
	 * Config service meta options
	 *
	 */
	$metabox->add_section( 'service', array(
		'title'  => esc_html__( 'General', 'consultio' ),
		'icon'   => 'el-icon-website',
		'fields' => array(
			array(
				'id'       => 'service_custom_link',
				'type'     => 'text',
				'title'    => esc_html__( 'Custom Link', 'consultio' ),
				'validate' => 'url',
				'msg'      => 'Url error!'
			),
			array(
	            'id'       => 'service_target_link',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Target Link', 'consultio'),
	            'options'  => array(
	                '_self'  => esc_html__('Inherit', 'consultio'),
	                '_blank'  => esc_html__('New Window', 'consultio'),
	            ),
	            'default'  => '_self'
	        ),
			array(
	            'id'       => 'icon_type',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Icon Type', 'consultio'),
	            'options'  => array(
	                'icon'  => esc_html__('Icon', 'consultio'),
	                'image'  => esc_html__('Image', 'consultio'),
	            ),
	            'default'  => 'icon'
	        ),
			array(
	            'id'       => 'service_icon',
	            'type'     => 'ct_iconpicker',
	            'title'    => esc_html__('Icon', 'consultio'),
	            'required' => array( 0 => 'icon_type', 1 => 'equals', 2 => 'icon' ),
            	'force_output' => true
	        ),
	        array(
	            'id'       => 'service_icon_img',
	            'type'     => 'media',
	            'title'    => esc_html__('Icon Image', 'consultio'),
	            'default' => '',
	            'required' => array( 0 => 'icon_type', 1 => 'equals', 2 => 'image' ),
            	'force_output' => true
	        ),
			array(
				'id'       => 'service_except',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Excerpt', 'consultio' ),
				'validate' => 'no_html'
			),
			array(
				'id'             => 'service_content_padding',
				'type'           => 'spacing',
				'output'         => array( '.single-service #content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'subtitle'     => esc_html__( 'Content site paddings.', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				)
			),
			array(
				'id'           => 'custom_pagetitle',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Page Title', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
			),
			array(
	            'id'       => 'ptitle_bg',
	            'type'     => 'background',
	            'background-color'     => false,
	            'background-repeat'     => false,
	            'background-size'     => false,
	            'background-attachment'     => false,
	            'background-position'     => false,
	            'title'    => esc_html__('Background', 'consultio'),
	            'subtitle' => esc_html__('Page title background image.', 'consultio'),
	            'required'     => array( 0 => 'custom_pagetitle', 1 => '=', 2 => 'show' ),
				'force_output' => true
	        ),
			array(
	            'id'          => 'p_page_title_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Page Title Color', 'consultio'),
	            'transparent' => false,
	            'default'     => '',
	            'output'    => array('body #pagetitle .page-title'),
	            'required'     => array( 0 => 'custom_pagetitle', 1 => '=', 2 => 'show' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_overlay',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Page Title Overlay', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required' => array( 0 => 'custom_pagetitle', 1 => 'equals', 2 => 'show' ),
	            'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_breadcrumb_page',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Breadcrumb', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required' => array( 0 => 'custom_pagetitle', 1 => 'equals', 2 => 'show' ),
	            'force_output' => true
	        ),
		)
	) );

	/**
	 * Config courses meta options
	 *
	 */
	$metabox->add_section( 'courses', array(
		'title'  => esc_html__( 'General', 'consultio' ),
		'icon'   => 'el-icon-website',
		'fields' => array(
			array(
				'id'       => 'courses_except',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Excerpt', 'consultio' ),
				'validate' => 'no_html'
			),
			array(
				'id'             => 'courses_content_padding',
				'type'           => 'spacing',
				'output'         => array( '.single-courses #content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'subtitle'     => esc_html__( 'Content site paddings.', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				)
			),
		)
	) );

	/**
	 * Config portfolio meta options
	 *
	 */
	$metabox->add_section( 'portfolio', array(
		'title'  => esc_html__( 'General', 'consultio' ),
		'icon'   => 'el-icon-website',
		'fields' => array(
			array(
				'id'           => 'custom_pagetitle',
				'type'         => 'button_set',
				'title'        => esc_html__( 'Page Title', 'consultio' ),
				'options'      => array(
					'themeoption'  => esc_html__( 'Theme Option', 'consultio' ),
					'show'  => esc_html__( 'Show', 'consultio' ),
					'hide'  => esc_html__( 'Hide', 'consultio' ),
				),
				'default'      => 'themeoption',
			),
			array(
	            'id'       => 'ptitle_bg',
	            'type'     => 'background',
	            'background-color'     => false,
	            'background-repeat'     => false,
	            'background-size'     => false,
	            'background-attachment'     => false,
	            'background-position'     => false,
	            'title'    => esc_html__('Background', 'consultio'),
	            'subtitle' => esc_html__('Page title background image.', 'consultio'),
	            'required'     => array( 0 => 'custom_pagetitle', 1 => '=', 2 => 'show' ),
				'force_output' => true
	        ),
	        array(
	            'id'          => 'p_page_title_color',
	            'type'        => 'color',
	            'title'       => esc_html__('Page Title Color', 'consultio'),
	            'transparent' => false,
	            'default'     => '',
	            'output'    => array('body #pagetitle .page-title'),
	            'required'     => array( 0 => 'custom_pagetitle', 1 => '=', 2 => 'show' ),
				'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_overlay',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Page Title Overlay', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required' => array( 0 => 'custom_pagetitle', 1 => 'equals', 2 => 'show' ),
	            'force_output' => true
	        ),
	        array(
	            'id'       => 'ptitle_breadcrumb_page',
	            'type'     => 'button_set',
	            'title'    => esc_html__('Breadcrumb', 'consultio'),
	            'options'  => array(
	                'themeoption'  => esc_html__('Theme Option', 'consultio'),
	                'show'  => esc_html__('Show', 'consultio'),
	                'hidden'  => esc_html__('Hidden', 'consultio'),
	            ),
	            'default'  => 'themeoption',
	            'required' => array( 0 => 'custom_pagetitle', 1 => 'equals', 2 => 'show' ),
	            'force_output' => true
	        ),
			array(
				'id'             => 'portfolio_content_padding',
				'type'           => 'spacing',
				'output'         => array( '.single-portfolio #content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'subtitle'     => esc_html__( 'Content site paddings.', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				),
			),
			array(
	            'id'      => 'portfolio_custom_link',
	            'type'    => 'text',
	            'title'   => esc_html__('Custom Link', 'consultio'),
	            'default' => '',
	        ),
	        array(
	            'id'      => 'portfolio_video_link',
	            'type'    => 'text',
	            'title'   => esc_html__('Video Link', 'consultio'),
	            'default' => '',
	        ),
		)
	) );

	/**
	 * Config case study meta options
	 *
	 */
	$metabox->add_section( 'case-study', array(
		'title'  => esc_html__( 'General', 'consultio' ),
		'icon'   => 'el-icon-website',
		'fields' => array(
			array(
	            'id'       => 'case_logo',
	            'type'     => 'media',
	            'title'    => esc_html__('Logo', 'consultio'),
	            'default' => '',
	        ),
	        array(
	            'id'      => 'case_logo_link',
	            'type'    => 'text',
	            'title'   => esc_html__('Logo Link', 'consultio'),
	            'default' => '#',
	        ),
			array(
				'id'       => 'case_study_except',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Excerpt', 'consultio' ),
				'validate' => 'no_html'
			),
			array(
				'id'             => 'case_study_content_padding',
				'type'           => 'spacing',
				'output'         => array( '.single-case-study #content' ),
				'right'          => false,
				'left'           => false,
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Content Padding', 'consultio' ),
				'subtitle'     => esc_html__( 'Content site paddings.', 'consultio' ),
				'desc'           => esc_html__( 'Default: Theme Option.', 'consultio' ),
				'default'        => array(
					'padding-top'    => '',
					'padding-bottom' => '',
					'units'          => 'px',
				)
			),
		)
	) );

	/**
     * Config product meta options
     *
     */
    $metabox->add_section('product', array(
        'title'  => esc_html__('Product Settings', 'consultio'),
        'icon'   => 'el el-briefcase',
        'fields' => array(
            array(
                'id'       => 'product_feature',
                'type'     => 'multi_text',
                'title'    => esc_html__('Feature', 'consultio'),
                'validate' => 'html',
            ),
        )
    ));

}

function consultio_get_option_of_theme_options( $key, $default = '' ) {
	if ( empty( $key ) ) {
		return '';
	}
	$options = get_option( consultio_get_opt_name(), array() );
	$value   = isset( $options[ $key ] ) ? $options[ $key ] : $default;

	return $value;
}