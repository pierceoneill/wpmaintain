<?php
if (!class_exists('ReduxFramework')) {
    return;
}
if (class_exists('ReduxFrameworkPlugin')) {
    remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
}

if(class_exists('Newsletter')) {
    $forms = array_filter( (array) get_option( 'newsletter_forms', array() ) );

    $newsletter_forms = array(
        'default' => esc_html__( 'Default Form', 'consultio' )
    );

    if ( $forms )
    {
        $index = 1;
        foreach ( $forms as $key => $form )
        {
            $newsletter_forms[ $key ] = sprintf( esc_html__( 'Form %s', 'consultio' ), $index );
            $index ++;
        }
    }
} else {
    $newsletter_forms = '';
}

$opt_name = consultio_get_opt_name();
$theme = wp_get_theme();

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => $theme->get('Name'),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get('Version'),
    // Version that appears at the top of your panel
    'menu_type'            => class_exists('Case_Theme_Core') ? 'submenu' : '',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => esc_html__('Theme Options', 'consultio'),
    'page_title'           => esc_html__('Theme Options', 'consultio'),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => false,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-admin-generic',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => true,
    // Show the time the page took to load, etc
    'update_notice'        => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
    'show_options_object' => false,
    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => class_exists('Case_Theme_Core') ? $theme->get('TextDomain') : '',
    // For a full list of options, visit: //codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => '',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'theme-options',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    ),
    'templates_path'       => get_template_directory() . '/inc/templates/redux/'
);

Redux::SetArgs($opt_name, $args);

/*--------------------------------------------------------------
# General
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title'  => esc_html__('General', 'consultio'),
    'icon'   => 'el-icon-home',
    'fields' => array(
        array(
            'id'       => 'favicon',
            'type'     => 'media',
            'title'    => esc_html__('Favicon', 'consultio'),
            'default' => '',
            'url'      => false
        ),
        array(
            'id'       => 'dev_mode',
            'type'     => 'switch',
            'title'    => esc_html__('Dev Mode (not recommended)', 'consultio'),
            'description' => 'no minimize , generate css over time...',
            'default'  => false
        ),
        array(
            'id'       => 'show_page_loading',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Page Loading', 'consultio'),
            'subtitle' => esc_html__('Enable page loading effect when you load site.', 'consultio'),
            'default'  => false
        ),
        array(
            'id'       => 'loading_type',
            'type'     => 'select',
            'title'    => esc_html__('Loading Style', 'consultio'),
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
                'style11'  => esc_html__('Style 11', 'consultio'),
                'style12'  => esc_html__('Style 12', 'consultio'),
                'style13'  => esc_html__('Style 13', 'consultio'),
                'style14'  => esc_html__('Style 14 (Custom Image)', 'consultio'),
            ),
            'default'  => 'style1',
            'required' => array( 0 => 'show_page_loading', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'       => 'loading_img',
            'type'     => 'media',
            'title'    => esc_html__('Loading Image', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'loading_type', 1 => 'equals', 2 => 'style14' ),
            'force_output' => true
        ),

        array(
            'id'       => 'mouse_move_animation',
            'type'     => 'switch',
            'title'    => esc_html__('Mouse Move Animation', 'consultio'),
            'default'  => false
        ),
        array(
            'id'       => 'newsletter_popup',
            'type'     => 'switch',
            'title'    => esc_html__('Newsletter Popup', 'consultio'),
            'default'  => false
        ),
        array(
            'id'      => 'newslette_title',
            'type'    => 'text',
            'title'   => esc_html__('Newsletter Title', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'newsletter_popup', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'=> 'newslette_desc',
            'type' => 'textarea',
            'title' => esc_html__('Newsletter Description', 'consultio'),
            'validate' => 'html_custom',
            'default' => '',
            'required' => array( 0 => 'newsletter_popup', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'      => 'newslette_close_text',
            'type'    => 'text',
            'title'   => esc_html__('Newsletter Close Text', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'newsletter_popup', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'      => 'newslette_email_placeholder',
            'type'    => 'text',
            'title'   => esc_html__('Newsletter Email Placeholder', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'newsletter_popup', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
    )
));

/*--------------------------------------------------------------
# Header
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Header', 'consultio'),
    'icon'   => 'el el-indent-left',
    'fields' => array(
        array(
            'id'       => 'header_type',
            'type'     => 'button_set',
            'title'    => esc_html__('Header Type', 'consultio'),
            'options'  => array(
                'layout'  => esc_html__('Default Layout', 'consultio'),
                'custom'  => esc_html__('Custom Layout Builder', 'consultio')
            ),
            'default'  => 'layout',
        ),
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
            'id'       => 'header_layout',
            'type'     => 'image_select',
            'title'    => esc_html__('Layout', 'consultio'),
            'subtitle' => esc_html__('Select a layout for header.', 'consultio'),
            'options'  => array(
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
            'default'  => '1',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
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
                'show'  => esc_html__( 'Show', 'consultio' ),
                'hide'  => esc_html__( 'Hide', 'consultio' ),
            ),
            'default'      => 'show',
            'required'     => array( 0 => 'header_layout', 1 => 'equals', 2 => '26' ),
            'force_output' => true
        ),
        array(
            'id'          => 'header_bg_color',
            'type'        => 'color',
            'title'       => esc_html__('Main Header Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'          => 'header_m_bg_color',
            'type'        => 'color',
            'title'       => esc_html__('Middle Header Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'required' => array( 0 => 'header_layout', 1 => 'equals', 2 => '21' ),
        ),
        array(
            'id'          => 'header_bg_color_sticky',
            'type'        => 'color',
            'title'       => esc_html__('Sticky Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'desc'    => esc_html__('Apply all.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'header_full',
            'type'     => 'switch',
            'title'    => esc_html__('Full Width Header', 'consultio'),
            'default'  => false,
            'desc'    => esc_html__('Apply header layout 1.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'sticky_on',
            'type'     => 'switch',
            'title'    => esc_html__('Sticky Header', 'consultio'),
            'subtitle' => esc_html__('Header will be sticked when applicable.', 'consultio'),
            'default'  => false,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'search_icon',
            'type'     => 'switch',
            'title'    => esc_html__('Search Icon', 'consultio'),
            'default'  => false,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_search_field_placeholder',
            'type'    => 'text',
            'title'   => esc_html__('Search Field Text Placeholder', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: Enter Keywords...', 'consultio'),
            'required' => array( 0 => 'search_icon', 1 => 'equals', 2 => '1' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'cart_icon',
            'type'     => 'switch',
            'title'    => esc_html__('Cart Icon Header', 'consultio'),
            'default'  => false,
            'subtitle' => esc_html__('Will display in some header layouts.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'cart_icon_sidebar',
            'type'     => 'switch',
            'title'    => esc_html__('Cart Icon Sidebar', 'consultio'),
            'default'  => false,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'hidden_sidebar_icon',
            'type'     => 'switch',
            'title'    => esc_html__('Hidden Sidebar Icon', 'consultio'),
            'default'  => false,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'language_switch',
            'type'     => 'switch',
            'title'    => esc_html__('Language Switch', 'consultio'),
            'default'  => false,
            'desc'    => esc_html__('Apply header layout 15.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'h_style1',
            'type'     => 'button_set',
            'title'    => esc_html__('Header Style', 'consultio'),
            'options'  => array(
                'h-style1'  => esc_html__('Style 1 (Default)', 'consultio'),
                'h-style2'  => esc_html__('Style 2 (Full width + Button)', 'consultio'),
                'h-style3'  => esc_html__('Style 3 (Menu Center)', 'consultio'),
                'h-style4'  => esc_html__('Style 4', 'consultio'),
                'h-style5'  => esc_html__('Style 5', 'consultio'),
            ),
            'default'  => 'h-style1',
            'desc'    => esc_html__('Apply header layout 9.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),

        array(
            'title' => esc_html__('Phone Button', 'consultio'),
            'type'  => 'section',
            'id' => 'phone_button',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id' => 'phone_button_label',
            'type' => 'text',
            'title' => esc_html__('Phone Button Label', 'consultio'),
            'desc'    => esc_html__('Apply header layout 20.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id' => 'phone_button_link',
            'type' => 'text',
            'title' => esc_html__('Phone Button Link', 'consultio'),
            'desc'    => esc_html__('Apply header layout 20.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Contact Info', 'consultio'),
    'icon'       => 'el el-credit-card',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'h_topbar',
            'type'     => 'button_set',
            'title'    => esc_html__('Topbar', 'consultio'),
            'options'  => array(
                'show'  => esc_html__('Show', 'consultio'),
                'hide'  => esc_html__('Hide', 'consultio')
            ),
            'default'  => 'show',
            'desc' => 'Apply fixed layout.'
        ),
        array(
            'id'          => 'topbar_bg_color',
            'type'        => 'color',
            'title'       => esc_html__('Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
        ),
        array(
            'id' => 'wellcome',
            'type' => 'text',
            'title' => esc_html__('Wellcome', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_phone_label',
            'type' => 'text',
            'title' => esc_html__('Phone Number Label', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_phone',
            'type' => 'text',
            'title' => esc_html__('Phone Number', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_phone_link',
            'type' => 'text',
            'title' => esc_html__('Phone Link', 'consultio'),
            'default' => '',
        ),
        array(
            'id'       => 'h_phone_link_target',
            'type'     => 'button_set',
            'title'    => esc_html__('Phone Link Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
        ),
        array(
            'id' => 'h_address_label',
            'type' => 'text',
            'title' => esc_html__('Address Label', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_address',
            'type' => 'text',
            'title' => esc_html__('Address', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_address_link',
            'type' => 'text',
            'title' => esc_html__('Address Link', 'consultio'),
            'default' => '',
        ),
        array(
            'id'       => 'h_address_link_target',
            'type'     => 'button_set',
            'title'    => esc_html__('Address Link Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
        ),
        array(
            'id' => 'h_time_label',
            'type' => 'text',
            'title' => esc_html__('Time Label', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_time',
            'type' => 'text',
            'title' => esc_html__('Time', 'consultio'),
            'default' => '',
        ),
        array(
            'id' => 'h_time_link',
            'type' => 'text',
            'title' => esc_html__('Time Link', 'consultio'),
            'default' => '',
        ),
        array(
            'id'       => 'h_time_link_target',
            'type'     => 'button_set',
            'title'    => esc_html__('Time Link Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
        ),
        array(
            'id' => 'h_email_label',
            'type' => 'text',
            'title' => esc_html__('Email Label', 'consultio'),
            'default' => '',
            'description' => 'Apply header layout 1, 11.',
        ),
        array(
            'id' => 'h_email',
            'type' => 'text',
            'title' => esc_html__('Email', 'consultio'),
            'default' => '',
            'description' => 'Apply header layout 1, 11.',
        ),
        array(
            'id' => 'h_email_link',
            'type' => 'text',
            'title' => esc_html__('Email Link', 'consultio'),
            'default' => '',
        ),
        array(
            'id'       => 'h_email_link_target',
            'type'     => 'button_set',
            'title'    => esc_html__('Email Link Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
        ),
        array(
            'title' => esc_html__('Social', 'consultio'),
            'type'  => 'section',
            'id' => 'header_social',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),

        array(
            'id'      => 'h_social_facebook_url',
            'type'    => 'text',
            'title'   => esc_html__('Facebook URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_twitter_url',
            'type'    => 'text',
            'title'   => esc_html__('Twitter URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_inkedin_url',
            'type'    => 'text',
            'title'   => esc_html__('Linkedin URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_instagram_url',
            'type'    => 'text',
            'title'   => esc_html__('Instagram URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_google_url',
            'type'    => 'text',
            'title'   => esc_html__('Google URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_skype_url',
            'type'    => 'text',
            'title'   => esc_html__('Skype URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_pinterest_url',
            'type'    => 'text',
            'title'   => esc_html__('Pinterest URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_vimeo_url',
            'type'    => 'text',
            'title'   => esc_html__('Vimeo URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_youtube_url',
            'type'    => 'text',
            'title'   => esc_html__('Youtube URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_yelp_url',
            'type'    => 'text',
            'title'   => esc_html__('Yelp URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_tumblr_url',
            'type'    => 'text',
            'title'   => esc_html__('Tumblr URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_social_tripadvisor_url',
            'type'    => 'text',
            'title'   => esc_html__('Tripadvisor URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Logo', 'consultio'),
    'icon'       => 'el el-picture',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'logo_light',
            'type'     => 'media',
            'title'    => esc_html__('Logo Light', 'consultio'),
            'default' => array(
                'url'=>get_template_directory_uri().'/assets/images/logo-light.png'
            ),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
            'url'      => false
        ),
        array(
            'id'       => 'logo',
            'type'     => 'media',
            'title'    => esc_html__('Logo Dark', 'consultio'),
             'default' => array(
                'url'=>get_template_directory_uri().'/assets/images/logo-dark.png'
            ),
             'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
             'url'      => false
        ),
        array(
            'id' => 'logo_tagline',
            'type' => 'text',
            'title' => esc_html__('Logo Tagline', 'consultio'),
            'default' => '',
            'desc'    => esc_html__('Apply header layout 8.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'logo_maxh',
            'type'     => 'dimensions',
            'title'    => esc_html__('Logo Max Height', 'consultio'),
            'subtitle' => esc_html__('Enter number.', 'consultio'),
            'width'    => false,
            'unit'     => 'px',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'logo_maxh_sticky',
            'type'     => 'dimensions',
            'title'    => esc_html__('Logo Max Height - Sticky', 'consultio'),
            'subtitle' => esc_html__('Enter number.', 'consultio'),
            'width'    => false,
            'unit'     => 'px',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Navigation', 'consultio'),
    'icon'       => 'el el-lines',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'          => 'font_menu',
            'type'        => 'typography',
            'title'       => esc_html__('Custom Google Font', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'font-style'  => false,
            'font-weight'  => true,
            'text-align'  => false,
            'font-size'  => false,
            'line-height'  => false,
            'color'  => false,
            'output'      => array('.ct-main-menu > li > a, body .ct-main-menu .sub-menu li a'),
            'units'       => 'px',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'title' => esc_html__('Main Menu', 'consultio'),
            'type'  => 'section',
            'id' => 'main_menu',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'icon_has_children',
            'type'     => 'button_set',
            'title'    => esc_html__('Icon Has Children', 'consultio'),
            'options'  => array(
                'plus'  => esc_html__('Plus', 'consultio'),
                'arrow'  => esc_html__('Arrow', 'consultio'),
                'hidden'  => esc_html__('None', 'consultio'),
            ),
            'default'  => 'plus',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'main_menu_color',
            'type'    => 'link_color',
            'title'   => esc_html__('Color', 'consultio'),
            'default' => array(
                'regular' => '',
                'hover'   => '',
                'active'   => '',
            ),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'menu_fs',
            'type'    => 'text',
            'title'   => esc_html__('Font Size', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: 16px. Enter number.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'title' => esc_html__('Sticky Menu', 'consultio'),
            'type'  => 'section',
            'id' => 'sticky_menu',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'sticky_menu_color',
            'type'    => 'link_color',
            'title'   => esc_html__('Color', 'consultio'),
            'default' => array(
                'regular' => '',
                'hover'   => '',
                'active'   => '',
            ),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'title' => esc_html__('Sub Menu', 'consultio'),
            'type'  => 'section',
            'id' => 'ssub_menu',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'sub_menu_displayed',
            'type'     => 'button_set',
            'title'    => esc_html__('Displayed', 'consultio'),
            'options'  => array(
                'sub-hover'  => esc_html__('Hover', 'consultio'),
                'sub-click'  => esc_html__('Click', 'consultio')
            ),
            'default'  => 'sub-hover',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'sub_menu_color',
            'type'    => 'link_color',
            'title'   => esc_html__('Menu Item Color', 'consultio'),
            'default' => array(
                'regular' => '',
                'hover'   => '',
                'active'   => '',
            ),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'          => 'sub_menu_bgcolor',
            'type'        => 'color',
            'title'       => esc_html__('Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'sub_menu_fs',
            'type'    => 'text',
            'title'   => esc_html__('Font Size', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: 14px. Enter number.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'title' => esc_html__('Button Navigation One', 'consultio'),
            'type'  => 'section',
            'id' => 'button_navigation1',
            'indent' => true,
        ),
        array(
            'id'       => 'h_btn_on',
            'type'     => 'button_set',
            'title'    => esc_html__('Show/Hide Button', 'consultio'),
            'options'  => array(
                'show'  => esc_html__('Show', 'consultio'),
                'hide'  => esc_html__('Hide', 'consultio')
            ),
            'default'  => 'hide',
        ),
        array(
            'id' => 'h_btn_text',
            'type' => 'text',
            'title' => esc_html__('Button Text', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'h_btn_on', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
        ),
        array(
            'id'       => 'h_btn_link_type',
            'type'     => 'button_set',
            'title'    => esc_html__('Button Link Type', 'consultio'),
            'options'  => array(
                'page'  => esc_html__('Page', 'consultio'),
                'custom'  => esc_html__('Custom', 'consultio')
            ),
            'default'  => 'page',
            'required' => array( 0 => 'h_btn_on', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
        ),
        array(
            'id'    => 'h_btn_link',
            'type'  => 'select',
            'title' => esc_html__( 'Page Link', 'consultio' ), 
            'data'  => 'page',
            'args'  => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array( 0 => 'h_btn_link_type', 1 => 'equals', 2 => 'page' ),
            'force_output' => true,
        ),
        array(
            'id' => 'h_btn_link_custom',
            'type' => 'text',
            'title' => esc_html__('Custom Link', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'h_btn_link_type', 1 => 'equals', 2 => 'custom' ),
            'force_output' => true,
        ),
        array(
            'id'       => 'h_btn_target',
            'type'     => 'button_set',
            'title'    => esc_html__('Button Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
            'required' => array( 0 => 'h_btn_on', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
        ),

        array(
            'title' => esc_html__('Button Navigation Two', 'consultio'),
            'type'  => 'section',
            'id' => 'button_navigation2',
            'indent' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'h_btn_on2',
            'type'     => 'button_set',
            'title'    => esc_html__('Show/Hide Button', 'consultio'),
            'options'  => array(
                'show'  => esc_html__('Show', 'consultio'),
                'hide'  => esc_html__('Hide', 'consultio')
            ),
            'default'  => 'hide',
            'desc'    => esc_html__('Apply header layout 12.', 'consultio'),
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id' => 'h_btn_text2',
            'type' => 'text',
            'title' => esc_html__('Button Text', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'h_btn_on2', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'h_btn_link_type2',
            'type'     => 'button_set',
            'title'    => esc_html__('Button Link Type', 'consultio'),
            'options'  => array(
                'page'  => esc_html__('Page', 'consultio'),
                'custom'  => esc_html__('Custom', 'consultio')
            ),
            'default'  => 'page',
            'required' => array( 0 => 'h_btn_on2', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'    => 'h_btn_link2',
            'type'  => 'select',
            'title' => esc_html__( 'Page Link', 'consultio' ), 
            'data'  => 'page',
            'args'  => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array( 0 => 'h_btn_link_type2', 1 => 'equals', 2 => 'page' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id' => 'h_btn_link_custom2',
            'type' => 'text',
            'title' => esc_html__('Custom Link', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'h_btn_link_type2', 1 => 'equals', 2 => 'custom' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'       => 'h_btn_target2',
            'type'     => 'button_set',
            'title'    => esc_html__('Button Target', 'consultio'),
            'options'  => array(
                '_self'  => esc_html__('Self', 'consultio'),
                '_blank'  => esc_html__('Blank', 'consultio')
            ),
            'default'  => '_self',
            'required' => array( 0 => 'h_btn_on2', 1 => 'equals', 2 => 'show' ),
            'force_output' => true,
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
    )
));


Redux::setSection($opt_name, array(
    'title'      => esc_html__('Popup', 'consultio'),
    'icon'       => 'el el-website',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'h_bg_popup',
            'type'     => 'background',
            'title'    => esc_html__('Background Image', 'consultio'),
            'output'   => array('.ct-header-elementor-popup'),
        ),
        array(
            'id'       => 'h_popup_logo',
            'type'     => 'media',
            'title'    => esc_html__('Logo', 'consultio'),
            'default' => '',
        ),
        array(
            'title' => esc_html__('Social', 'consultio'),
            'type'  => 'section',
            'id' => 'header_popup_social',
        ),

        array(
            'id'      => 'h_popup_facebook_url',
            'type'    => 'text',
            'title'   => esc_html__('Facebook URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_twitter_url',
            'type'    => 'text',
            'title'   => esc_html__('Twitter URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_inkedin_url',
            'type'    => 'text',
            'title'   => esc_html__('Linkedin URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_instagram_url',
            'type'    => 'text',
            'title'   => esc_html__('Instagram URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_skype_url',
            'type'    => 'text',
            'title'   => esc_html__('Skype URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_pinterest_url',
            'type'    => 'text',
            'title'   => esc_html__('Pinterest URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_vimeo_url',
            'type'    => 'text',
            'title'   => esc_html__('Vimeo URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_youtube_url',
            'type'    => 'text',
            'title'   => esc_html__('Youtube URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_yelp_url',
            'type'    => 'text',
            'title'   => esc_html__('Yelp URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_tumblr_url',
            'type'    => 'text',
            'title'   => esc_html__('Tumblr URL', 'consultio'),
            'default' => '',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
        array(
            'id'      => 'h_popup_tripadvisor_url',
            'type'    => 'text',
            'title'   => esc_html__('Tripadvisor URL', 'consultio'),
            'default' => '#',
            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'layout' ),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Mobile', 'consultio'),
    'icon'       => 'el el-website',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'h_mobile_type',
            'type'     => 'button_set',
            'title'    => esc_html__('Header Mobile Type', 'consultio'),
            'options'  => array(
                'light'  => esc_html__('Light', 'consultio'),
                'dark'  => esc_html__('Dark', 'consultio')
            ),
            'default'  => 'light',
        ),
        array(
            'id'       => 'logo_mobile',
            'type'     => 'media',
            'title'    => esc_html__('Logo Tablet & Mobile', 'consultio'),
             'default' => array(
                'url'=>get_template_directory_uri().'/assets/images/logo-dark.png'
            ),
            'url'      => false
        ),
        array(
            'id'       => 'logo_maxh_sm',
            'type'     => 'dimensions',
            'title'    => esc_html__('Logo Max Height for Tablet & Mobile', 'consultio'),
            'subtitle' => esc_html__('Enter number.', 'consultio'),
            'width'    => false,
            'unit'     => 'px'
        ),
        array(
            'id'          => 'header_mobile_bg_color',
            'type'        => 'color',
            'title'       => esc_html__('Mobile Header Background Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
        ),
        array(
            'id'          => 'hamburger_btn_color',
            'type'        => 'color',
            'title'       => esc_html__('Hamburger Button Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
        ),
    )
));

/*--------------------------------------------------------------
# Page Title area
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Page Title', 'consultio'),
    'icon'   => 'el-icon-map-marker',
    'fields' => array(

        array(
            'id'           => 'pagetitle',
            'type'         => 'button_set',
            'title'        => esc_html__( 'Page Title', 'consultio' ),
            'options'      => array(
                'show'  => esc_html__( 'Show', 'consultio' ),
                'hide'  => esc_html__( 'Hide', 'consultio' ),
            ),
            'default'      => 'show',
        ),

        array(
            'id'       => 'ptitle_type',
            'type'     => 'button_set',
            'title'    => esc_html__('Page Title Type', 'consultio'),
            'options'  => array(
                'layout'  => esc_html__('Default Layout', 'consultio'),
                'custom'  => esc_html__('Custom Layout Builder', 'consultio')
            ),
            'default'  => 'layout',
            'required' => array( 0 => 'pagetitle', 1 => 'equals', 2 => 'show' ),
        ),

        array(
            'id'       => 'ptitle_bg',
            'type'     => 'background',
            'title'    => esc_html__('Background', 'consultio'),
            'subtitle' => esc_html__('Page title background.', 'consultio'),
            'output'   => array('body #pagetitle'),
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'       => 'ptitle_align',
            'type'     => 'button_set',
            'title'    => esc_html__('Align', 'consultio'),
            'options'  => array(
                'left'  => esc_html__('Left', 'consultio'),
                'center'  => esc_html__('Center', 'consultio'),
                'right'  => esc_html__('Right', 'consultio'),
            ),
            'default'  => 'center',
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'       => 'ptitle_overlay',
            'type'     => 'button_set',
            'title'    => esc_html__('Overlay', 'consultio'),
            'options'  => array(
                'show'  => esc_html__('Show', 'consultio'),
                'hidden'  => esc_html__('Hidden', 'consultio'),
            ),
            'default'  => 'show',
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'       => 'pagetitle_bg_color',
            'type'     => 'color_rgba',
            'title'    => esc_html__('Overlay Color', 'consultio'),
            'output' => array('background-color' => 'body #pagetitle::before'),
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'          => 'page_title_color',
            'type'        => 'color',
            'title'       => esc_html__('Page Title Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'output'    => array('#pagetitle .page-title'),
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'             => 'page_title_padding',
            'type'           => 'spacing',
            'output'         => array('body #pagetitle'),
            'right'   => false,
            'left'    => false,
            'mode'           => 'padding',
            'units'          => array('px'),
            'units_extended' => 'false',
            'title'          => esc_html__('Page Title Padding', 'consultio'),
            'desc'           => esc_html__('Default: Top-126px, Bottom-116px', 'consultio'),
            'default'            => array(
                'padding-top'   => '',
                'padding-bottom'   => '',
                'units'          => 'px',
            ),
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),

        array(
            'id'             => 'page_title_padding_sm',
            'type'           => 'spacing',
            'right'   => false,
            'left'    => false,
            'mode'           => 'padding',
            'units'          => array('px'),
            'units_extended' => 'false',
            'title'          => esc_html__('Page Title Padding Mobile + Tablet', 'consultio'),
            'default'            => array(
                'padding-top'   => '',
                'padding-bottom'   => '',
                'units'          => 'px',
            ),
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),

        array(
            'id'       => 'ptitle_breadcrumb_on',
            'type'     => 'button_set',
            'title'    => esc_html__('Breadcrumb', 'consultio'),
            'options'  => array(
                'show'  => esc_html__('Show', 'consultio'),
                'hidden'  => esc_html__('Hidden', 'consultio'),
            ),
            'default'  => 'show',
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'layout' ),
            'force_output' => true
        ),
        array(
            'id'          => 'breadcrumb_color',
            'type'        => 'color',
            'title'       => esc_html__('Breadcrumb Color', 'consultio'),
            'transparent' => false,
            'default'     => '',
            'output'    => array('.ct-breadcrumb'),
            'required'     => array( 0 => 'ptitle_breadcrumb_on', 1 => '=', 2 => 'show' ),
            'force_output' => true
        ),

        array(
            'id'          => 'p_title_layout',
            'type'        => 'select',
            'title'       => esc_html__('Page Title Layout', 'consultio'),
            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your page title layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=page-title' ) ) . '">','</a>'),
            'options'     => consultio_list_post('page-title'),
            'default'     => '',
            'required' => array( 0 => 'ptitle_type', 1 => 'equals', 2 => 'custom' ),
        ),
    )
));

/*--------------------------------------------------------------
# WordPress default content
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title' => esc_html__('Content', 'consultio'),
    'icon'  => 'el-icon-pencil',
    'fields'     => array(
        array(
            'id'             => 'content_padding',
            'type'           => 'spacing',
            'output'         => array('#content'),
            'right'   => false,
            'left'    => false,
            'mode'           => 'padding',
            'units'          => array('px'),
            'units_extended' => 'false',
            'title'          => esc_html__('Content Padding', 'consultio'),
            'desc'           => esc_html__('Default: Top-80px, Bottom-80px', 'consultio'),
            'default'            => array(
                'padding-top'   => '',
                'padding-bottom'   => '',
                'units'          => 'px',
            )
        ),
        array(
            'id'      => 'search_field_placeholder',
            'type'    => 'text',
            'title'   => esc_html__('Search Form - Text Placeholder', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: Search Keywords...', 'consultio'),
        ),
    )
));


Redux::setSection($opt_name, array(
    'title'      => esc_html__('Archive', 'consultio'),
    'icon'       => 'el-icon-list',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'archive_sidebar_pos',
            'type'     => 'button_set',
            'title'    => esc_html__('Sidebar Position', 'consultio'),
            'subtitle' => esc_html__('Select a sidebar position for blog home, archive, search...', 'consultio'),
            'options'  => array(
                'left'  => esc_html__('Left', 'consultio'),
                'right' => esc_html__('Right', 'consultio'),
                'none'  => esc_html__('Disabled', 'consultio')
            ),
            'default'  => 'right'
        ),
        array(
            'id'       => 'archive_date_on',
            'title'    => esc_html__('Date', 'consultio'),
            'subtitle' => esc_html__('Show date posted on each post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
        ),
        array(
            'id'       => 'archive_author_on',
            'title'    => esc_html__('Author', 'consultio'),
            'subtitle' => esc_html__('Show author name on each post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
        ),
        array(
            'id'       => 'archive_categories_on',
            'title'    => esc_html__('Categories', 'consultio'),
            'subtitle' => esc_html__('Show category names on each post.', 'consultio'),
            'type'     => 'switch',
            'default'  => false,
        ),
        array(
            'id'       => 'archive_comments_on',
            'title'    => esc_html__('Comments', 'consultio'),
            'subtitle' => esc_html__('Show comments count on each post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
        ),
        array(
            'id'      => 'excerpt_length',
            'type'    => 'text',
            'title'   => esc_html__('Excerpt Length', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: 50', 'consultio'),
        ),
        array(
            'id'      => 'text_btn_more',
            'type'    => 'text',
            'title'   => esc_html__('Text - Read More Button', 'consultio'),
            'default' => '',
            'desc'           => esc_html__('Default: Read More', 'consultio'),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Search Page', 'consultio'),
    'icon'       => 'el el-search-alt',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'      => 's_custom_header',
            'type'    => 'switch',
            'title'   => esc_html__( 'Custom Layout Header', 'consultio' ),
            'default' => false,
            'indent'  => true
        ),
        array(
            'id'           => 's_header_layout',
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
            'default'      => '1',
            'required'     => array( 0 => 's_custom_header', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'           => 's_custom_pagetitle',
            'type'         => 'button_set',
            'title'        => esc_html__( 'Custom Page Title', 'consultio' ),
            'options'      => array(
                'default'  => esc_html__( 'Default', 'consultio' ),
                'show'  => esc_html__( 'Custom', 'consultio' ),
            ),
            'default'      => 'default',
        ),
        array(
            'id'       => 's_ptitle_bg',
            'type'     => 'background',
            'background-color'     => false,
            'background-repeat'     => false,
            'background-size'     => false,
            'background-attachment'     => false,
            'background-position'     => false,
            'title'    => esc_html__('Background', 'consultio'),
            'subtitle' => esc_html__('Page title background image.', 'consultio'),
            'required'     => array( 0 => 's_custom_pagetitle', 1 => '=', 2 => 'show' ),
            'force_output' => true
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Empty Content', 'consultio'),
    'icon'       => 'el el-bookmark-empty',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'      => 'content_none_title',
            'type'    => 'text',
            'title'   => esc_html__('Title', 'consultio'),
            'default' => '',
        ),
        array(
            'id'       => 'content_none_desc',
            'type'     => 'textarea',
            'title'    => esc_html__('Description', 'consultio'),
            'validate' => 'no_html'
        )
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Single Post', 'consultio'),
    'icon'       => 'el-icon-file-edit',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'post_sidebar_pos',
            'type'     => 'button_set',
            'title'    => esc_html__('Sidebar Position', 'consultio'),
            'subtitle' => esc_html__('Select a sidebar position', 'consultio'),
            'options'  => array(
                'left'  => esc_html__('Left', 'consultio'),
                'right' => esc_html__('Right', 'consultio'),
                'none'  => esc_html__('Disabled', 'consultio')
            ),
            'default'  => 'right'
        ),
        array(
            'id'       => 'post_title_display',
            'type'     => 'button_set',
            'title'    => esc_html__('Post Title Display', 'consultio'),
            'options'  => array(
                'p-title'  => esc_html__('Page Title', 'consultio'),
                'p-content'  => esc_html__('Page Content', 'consultio'),
            ),
            'default'  => 'p-title'
        ),
        array(
            'id'       => 'post_date_on',
            'title'    => esc_html__('Date', 'consultio'),
            'subtitle' => esc_html__('Show date on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true
        ),
        array(
            'id'       => 'post_author_on',
            'title'    => esc_html__('Author', 'consultio'),
            'subtitle' => esc_html__('Show author name on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true
        ),
        array(
            'id'       => 'post_categories_on',
            'title'    => esc_html__('Categories', 'consultio'),
            'subtitle' => esc_html__('Show category names on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true
        ),
        array(
            'id'       => 'post_tags_on',
            'title'    => esc_html__('Tags', 'consultio'),
            'subtitle' => esc_html__('Show tag names on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => true
        ),
        array(
            'id'      => 'f_crop_w',
            'type'    => 'text',
            'title'   => esc_html__('Feature Image Crop (Width)', 'consultio'),
            'default' => '900',
        ),
        array(
            'id'      => 'f_crop_h',
            'type'    => 'text',
            'title'   => esc_html__('Feature Image Crop (Height)', 'consultio'),
            'default' => '313',
            'desc'        => sprintf(esc_html__('After you resize the width and height. You need to install Regenerate Thumbnails plugin to copy image. %sClick Here%s','consultio'),'<a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">','</a>'),
        ),
        array(
            'id'       => 'post_navigation_on',
            'title'    => esc_html__('Navigation', 'consultio'),
            'subtitle' => esc_html__('Show navigation on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => false,
        ),
        array(
            'title' => esc_html__('Social Share', 'consultio'),
            'type'  => 'section',
            'id' => 'post_social_share',
            'indent' => true,
        ),
        array(
            'id'       => 'post_social_share_on',
            'title'    => esc_html__('Social Share', 'consultio'),
            'subtitle' => esc_html__('Show social share on single post.', 'consultio'),
            'type'     => 'switch',
            'default'  => false,
        ),
        array(
            'id'       => 'post_social_facebook',
            'title'    => esc_html__('Facebook', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
            'required' => array( 0 => 'post_social_share_on', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'       => 'post_social_twitter',
            'title'    => esc_html__('Twitter', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
            'required' => array( 0 => 'post_social_share_on', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'       => 'post_social_pinterest',
            'title'    => esc_html__('Pinterest', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
            'required' => array( 0 => 'post_social_share_on', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
        array(
            'id'       => 'post_social_linkedin ',
            'title'    => esc_html__('LinkedIn', 'consultio'),
            'type'     => 'switch',
            'default'  => true,
            'required' => array( 0 => 'post_social_share_on', 1 => 'equals', 2 => '1' ),
            'force_output' => true
        ),
    )
));

/*--------------------------------------------------------------
# Shop
--------------------------------------------------------------*/
if(class_exists('Woocommerce')) {
    Redux::setSection($opt_name, array(
        'title'  => esc_html__('Shop', 'consultio'),
        'icon'   => 'el el-shopping-cart',
        'fields' => array(
            array(
                'id'       => 'sidebar_shop',
                'type'     => 'button_set',
                'title'    => esc_html__('Sidebar Position', 'consultio'),
                'subtitle' => esc_html__('Select a sidebar position for archive shop.', 'consultio'),
                'options'  => array(
                    'left'  => esc_html__('Left', 'consultio'),
                    'right' => esc_html__('Right', 'consultio'),
                    'none'  => esc_html__('Disabled', 'consultio')
                ),
                'default'  => 'right'
            ),
            array(
                'title' => esc_html__('Products displayed per page', 'consultio'),
                'id' => 'product_per_page',
                'type' => 'slider',
                'subtitle' => esc_html__('Number product to show', 'consultio'),
                'default' => 8,
                'min'  => 4,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text'
            ),
        )
    ));
}

/*--------------------------------------------------------------
# Footer
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Footer', 'consultio'),
    'icon'   => 'el el-website',
    'fields' => array(
        array(
            'id'          => 'footer_layout_custom',
            'type'        => 'select',
            'title'       => esc_html__('Layout', 'consultio'),
            'desc'        => sprintf(esc_html__('To use this Option please %sClick Here%s to add your custom footer layout first.','consultio'),'<a href="' . esc_url( admin_url( 'edit.php?post_type=footer' ) ) . '">','</a>'),
            'options'     => consultio_list_post('footer'),
            'default'     => '',
        ),
        array(
            'id'           => 'back_totop_on',
            'type'         => 'button_set',
            'title'        => esc_html__( 'Back to Top Button', 'consultio' ),
            'options'      => array(
                'show'  => esc_html__( 'Show', 'consultio' ),
                'hide'  => esc_html__( 'Hide', 'consultio' ),
            ),
            'default'      => 'show',
        ),
        array(
            'id'       => 'fixed_footer',
            'type'     => 'switch',
            'title'    => esc_html__('Fixed Footer', 'consultio'),
            'default'  => false,
        ),
    )
));

/* 404 Page /--------------------------------------------------------- */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('404 Page', 'consultio'),
    'icon'   => 'el-cog-alt el',
    'fields' => array(
        array(
            'id'       => 'page_404',
            'type'     => 'button_set',
            'title'    => esc_html__('Select 404 Page', 'consultio'),
            'options'  => array(
                'default'  => esc_html__('Default', 'consultio'),
                'custom'  => esc_html__('Custom', 'consultio'),
            ),
            'default'  => 'default'
        ),

        array(
            'id'          => 'page_custom_404',
            'type'        => 'select',
            'title'       => esc_html__('Page', 'consultio'),
            'options'     => consultio_list_post('page'),
            'default'     => '',
            'required' => array( 0 => 'page_404', 1 => 'equals', 2 => 'custom' ),
            'force_output' => true
        ),
    ),
));

/*--------------------------------------------------------------
# Colors
--------------------------------------------------------------*/

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Colors', 'consultio'),
    'icon'   => 'el-icon-file-edit',
    'fields' => array(
        array(
            'id'          => 'primary_color',
            'type'        => 'color',
            'title'       => esc_html__('Primary Color', 'consultio'),
            'transparent' => false,
            'default'     => '#c1282a'
        ),
        array(
            'id'          => 'secondary_color',
            'type'        => 'color',
            'title'       => esc_html__('Secondary Color', 'consultio'),
            'transparent' => false,
            'default'     => '#000000'
        ),
        array(
            'id'          => 'third_color',
            'type'        => 'color',
            'title'       => esc_html__('Third Color', 'consultio'),
            'transparent' => false,
            'default'     => '#106cc7'
        ),
        array(
            'id'          => 'four_color',
            'type'        => 'color',
            'title'       => esc_html__('Four Color', 'consultio'),
            'transparent' => false,
            'default'     => '#ffd200'
        ),
        array(
            'id'      => 'link_color',
            'type'    => 'link_color',
            'title'   => esc_html__('Link Colors', 'consultio'),
            'default' => array(
                'regular' => '#c1282a',
                'hover'   => '#d1651a',
                'active'  => '#d1651a'
            ),
            'output'  => array('a')
        ),
        array(
            'id'          => 'gradient_color',
            'type'        => 'color_gradient',
            'title'       => esc_html__('Gradient Color', 'consultio'),
            'transparent' => false,
            'default'  => array(
                'from' => '#d1651a',
                'to'   => '#c1282a', 
            ),
        ),
        array(
            'id'       => 'button_type_color',
            'type'     => 'button_set',
            'title'    => esc_html__('Button Type Color', 'consultio'),
            'options'  => array(
                'normal'  => esc_html__('Normal', 'consultio'),
                'gradient'  => esc_html__('Gradient', 'consultio'),
            ),
            'default'  => 'gradient'
        ),
        array(
            'id'       => 'body_bg_color',
            'type'     => 'color_rgba',
            'title'    => esc_html__('Body Background Color', 'consultio'),
            'output' => array('background-color' => 'body, .fixed-footer #ct-masthead, .fixed-footer .site-content')
        ),
        array(
            'id'          => 'body_text_color',
            'type'        => 'color',
            'title'       => esc_html__('Body Text Color', 'consultio'),
            'transparent' => false,
            'output'      => array('body'),
        ),
        array(
            'id'          => 'heading_color',
            'type'        => 'color',
            'title'       => esc_html__('Heading Color', 'consultio'),
            'transparent' => false,
            'output'      => array('h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6'),
        ),
    )
));

/*--------------------------------------------------------------
# Typography
--------------------------------------------------------------*/
$custom_font_selectors_1 = Redux::get_option($opt_name, 'custom_font_selectors_1');
$custom_font_selectors_1 = !empty($custom_font_selectors_1) ? explode(',', $custom_font_selectors_1) : array();
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Typography', 'consultio'),
    'icon'   => 'el-icon-text-width',
    'fields' => array(
        array(
            'id'       => 'body_default_font',
            'type'     => 'select',
            'title'    => esc_html__('Body Default Font', 'consultio'),
            'options'  => array(
                'Roboto'  => esc_html__('Default', 'consultio'),
                'Google-Font'  => esc_html__('Google Font', 'consultio'),
            ),
            'default'  => 'Roboto',
        ),
        array(
            'id'          => 'font_main',
            'type'        => 'typography',
            'title'       => esc_html__('Body Google Font', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'line-height'  => true,
            'font-size'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('body'),
            'units'       => 'px',
            'required' => array( 0 => 'body_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'       => 'heading_default_font',
            'type'     => 'select',
            'title'    => esc_html__('Heading Default Font', 'consultio'),
            'options'  => array(
                'Poppins'  => esc_html__('Default', 'consultio'),
                'Google-Font'  => esc_html__('Google Font', 'consultio'),
            ),
            'default'  => 'Poppins',
        ),
        array(
            'id'          => 'font_h1',
            'type'        => 'typography',
            'title'       => esc_html__('H1', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H1 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h1', '.h1', '.text-heading'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'          => 'font_h2',
            'type'        => 'typography',
            'title'       => esc_html__('H2', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H2 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h2', '.h2'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'          => 'font_h3',
            'type'        => 'typography',
            'title'       => esc_html__('H3', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H3 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h3', '.h3'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'          => 'font_h4',
            'type'        => 'typography',
            'title'       => esc_html__('H4', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H4 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h4', '.h4'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'          => 'font_h5',
            'type'        => 'typography',
            'title'       => esc_html__('H5', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H5 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h5', '.h5'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
        array(
            'id'          => 'font_h6',
            'type'        => 'typography',
            'title'       => esc_html__('H6', 'consultio'),
            'subtitle'    => esc_html__('This will be the default font for all H6 tags of your website.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'color' => false,
            'output'      => array('h6', '.h6'),
            'units'       => 'px',
            'required' => array( 0 => 'heading_default_font', 1 => 'equals', 2 => 'Google-Font' ),
            'force_output' => true
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Fonts Selectors', 'consultio'),
    'icon'       => 'el el-fontsize',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'          => 'custom_font_1',
            'type'        => 'typography',
            'title'       => esc_html__('Custom Font', 'consultio'),
            'subtitle'    => esc_html__('This will be the font that applies to the class selector.', 'consultio'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => true,
            'text-align'  => false,
            'output'      => $custom_font_selectors_1,
            'units'       => 'px',

        ),
        array(
            'id'       => 'custom_font_selectors_1',
            'type'     => 'textarea',
            'title'    => esc_html__('CSS Selectors', 'consultio'),
            'subtitle' => esc_html__('Add class selectors to apply above font.', 'consultio'),
            'validate' => 'no_html'
        )
    )
));

/* Google Maps /--------------------------------------------------------- */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Google Maps', 'consultio'),
    'icon'   => 'el el-map-marker',
    'fields' => array(
        array(
            'id'       => 'gm_api_key',
            'type'     => 'text',
            'title'    => esc_html__('API Key', 'consultio'),
            'default' => 'AIzaSyC08_qdlXXCWiFNVj02d-L2BDK5qr6ZnfM',
            'desc' => esc_html__('Register a Google Maps Api key then put it in here.', 'consultio')
        ),
    ),
));

/* Custom Code /--------------------------------------------------------- */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Custom Code', 'consultio'),
    'icon'   => 'el-icon-edit',
    'fields' => array(

        array(
            'id'       => 'site_header_code',
            'type'     => 'textarea',
            'theme'    => 'chrome',
            'title'    => esc_html__('Header Custom Codes', 'consultio'),
            'subtitle' => esc_html__('It will insert the code to wp_head hook.', 'consultio'),
        ),
        array(
            'id'       => 'site_footer_code',
            'type'     => 'textarea',
            'theme'    => 'chrome',
            'title'    => esc_html__('Footer Custom Codes', 'consultio'),
            'subtitle' => esc_html__('It will insert the code to wp_footer hook.', 'consultio'),
        ),

    ),
));

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Extra Post Type', 'consultio'),
    'icon'       => 'el el-briefcase',
    'fields'     => array(
        array(
            'title' => esc_html__('Portfolio', 'consultio'),
            'type'  => 'section',
            'id' => 'post_portfolio',
            'indent' => true,
        ),
        array(
            'id'       => 'portfolio_display',
            'type'     => 'switch',
            'title'    => esc_html__('Portfolio', 'consultio'),
            'default'  => true
        ),
        array(
            'id'      => 'portfolio_slug',
            'type'    => 'text',
            'title'   => esc_html__('Portfolio Slug', 'consultio'),
            'default' => '',
            'desc'     => 'Default: portfolio',
            'required' => array( 0 => 'portfolio_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'      => 'portfolio_name',
            'type'    => 'text',
            'title'   => esc_html__('Portfolio Name', 'consultio'),
            'default' => '',
            'desc'     => 'Default: Portfolio',
            'required' => array( 0 => 'portfolio_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'    => 'archive_portfolio_link',
            'type'  => 'select',
            'title' => esc_html__( 'Custom Archive Page Link', 'consultio' ), 
            'data'  => 'page',
            'args'  => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array( 0 => 'portfolio_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'title' => esc_html__('Service', 'consultio'),
            'type'  => 'section',
            'id' => 'post_service',
            'indent' => true,
        ),
        array(
            'id'       => 'service_display',
            'type'     => 'switch',
            'title'    => esc_html__('Service', 'consultio'),
            'default'  => true
        ),
        array(
            'id'      => 'service_slug',
            'type'    => 'text',
            'title'   => esc_html__('Service Slug', 'consultio'),
            'default' => '',
            'desc'     => 'Default: service',
            'required' => array( 0 => 'service_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'      => 'service_name',
            'type'    => 'text',
            'title'   => esc_html__('Service Name', 'consultio'),
            'default' => '',
            'desc'     => 'Default: Services',
            'required' => array( 0 => 'service_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'    => 'archive_service_link',
            'type'  => 'select',
            'title' => esc_html__( 'Custom Archive Page Link', 'consultio' ), 
            'data'  => 'page',
            'args'  => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array( 0 => 'service_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'title' => esc_html__('Case Study', 'consultio'),
            'type'  => 'section',
            'id' => 'post_case_study',
            'indent' => true,
        ),
        array(
            'id'       => 'case_study_display',
            'type'     => 'switch',
            'title'    => esc_html__('Case Study', 'consultio'),
            'default'  => true
        ),
        array(
            'id'      => 'case_study_slug',
            'type'    => 'text',
            'title'   => esc_html__('Case Study Slug', 'consultio'),
            'default' => '',
            'desc'     => 'Default: case-study',
            'required' => array( 0 => 'case_study_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'      => 'case_study_name',
            'type'    => 'text',
            'title'   => esc_html__('Case Study Name', 'consultio'),
            'default' => '',
            'desc'     => 'Default: Case Studies',
            'required' => array( 0 => 'case_study_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'    => 'archive_case_study_link',
            'type'  => 'select',
            'title' => esc_html__( 'Custom Archive Page Link', 'consultio' ), 
            'data'  => 'page',
            'args'  => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array( 0 => 'case_study_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'title' => esc_html__('Courses', 'consultio'),
            'type'  => 'section',
            'id' => 'post_courses',
            'indent' => true,
        ),
        array(
            'id'       => 'courses_display',
            'type'     => 'switch',
            'title'    => esc_html__('Courses', 'consultio'),
            'default'  => false
        ),
        array(
            'id'      => 'courses_slug',
            'type'    => 'text',
            'title'   => esc_html__('Courses Slug', 'consultio'),
            'default' => '',
            'desc'     => 'Default: courses',
            'required' => array( 0 => 'courses_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
        array(
            'id'      => 'courses_name',
            'type'    => 'text',
            'title'   => esc_html__('Courses Name', 'consultio'),
            'default' => '',
            'desc'     => 'Default: Courses',
            'required' => array( 0 => 'courses_display', 1 => 'equals', 2 => 'true' ),
            'force_output' => true
        ),
    )
));

/* Custom CSS /--------------------------------------------------------- */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Custom CSS', 'consultio'),
    'icon'   => 'el-icon-adjust-alt',
    'fields' => array(

        array(
            'id'   => 'customcss',
            'type' => 'info',
            'desc' => esc_html__('Custom CSS', 'consultio')
        ),

        array(
            'id'       => 'site_css',
            'type'     => 'ace_editor',
            'title'    => esc_html__('CSS Code', 'consultio'),
            'subtitle' => esc_html__('Advanced CSS Options. You can paste your custom CSS Code here.', 'consultio'),
            'mode'     => 'css',
            'validate' => 'css',
            'theme'    => 'chrome',
            'default'  => ""
        ),

    ),
));