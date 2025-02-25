<?php
/**
 * Functions and definitions
 *
 * @package Consultio
 */



if(!defined('DEV_MODE')){
	if ( is_user_logged_in() ) {
    	define('DEV_MODE', true);
    } else {
    	define('DEV_MODE', false);
    }
}

if(!function_exists('consultio_require_folder')){
    function consultio_require_folder($foldername,$path = '')
    {
        if($path === '') $path = get_template_directory();
        $dir = $path . DIRECTORY_SEPARATOR . $foldername;
        if (!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), array('..', '.'));
        foreach ($files as $file) {
            $patch = $dir . DIRECTORY_SEPARATOR . $file;
            if (file_exists($patch) && strpos($file, ".php") !== false) {
                require_once $patch;
            }
        }
    }
}

if ( ! function_exists( 'consultio_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function consultio_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'consultio', get_template_directory() . '/languages' );

		// Custom Header
		add_theme_support( "custom-header" );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'consultio' ),
			'secondary' => esc_html__( 'Secondary', 'consultio' ),
			'menu-popup' => esc_html__( 'Popup', 'consultio' ),
			'menu-left' => esc_html__( 'Menu Left', 'consultio' ),
			'menu-right' => esc_html__( 'Menu Right', 'consultio' ),
		) );


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'consultio_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for core custom logo.
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		add_theme_support( 'post-formats', array(
			'',
		) );

        // Enable support for Post Thumbnails on posts and pages.
        $f_crop_w = consultio_get_opt( 'f_crop_w', '900' );
        $f_crop_h = consultio_get_opt( 'f_crop_h', '313' );
        add_theme_support('post-thumbnails');
        add_image_size( 'consultio-large', $f_crop_w, $f_crop_h, true );
        add_image_size( 'consultio-medium', 600, 450, true );

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		remove_theme_support('widgets-block-editor');
	}
endif;
add_action( 'after_setup_theme', 'consultio_setup' );

add_action( 'ct_locations', function ( $ct_locations ) {
	return $ct_locations;
} );

add_filter( 'ct_locations', 'consultio_extra_nav_maga_menu' );
function consultio_extra_nav_maga_menu() {
	return array('primary', 'menu-left','menu-right', '');
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 */
function consultio_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'consultio_content_width', 640 );
}

add_action( 'after_setup_theme', 'consultio_content_width', 0 );

/**
 * Register widget area.
 */
function consultio_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'consultio' ),
		'id'            => 'sidebar-blog',
		'description'   => esc_html__( 'Add widgets here.', 'consultio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if (class_exists('ReduxFramework')) {
		register_sidebar( array(
			'name'          => esc_html__( 'Page Sidebar', 'consultio' ),
			'id'            => 'sidebar-page',
			'description'   => esc_html__( 'Add widgets here.', 'consultio' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget'  => '</div></section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	if ( class_exists( 'Woocommerce' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Shop Sidebar', 'consultio' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Add widgets here.', 'consultio' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget'  => '</div></section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	$hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
	register_sidebar( array(
		'name'          => esc_html__( 'Header Hidden Sidebar', 'consultio' ),
		'id'            => 'sidebar-hidden',
		'description'   => esc_html__( 'Add widgets here.', 'consultio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'consultio_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function consultio_scripts() {
	$theme = wp_get_theme( get_template() );

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '4.0.0' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'font-awesome-v5', get_template_directory_uri() . '/assets/css/font-awesome5.min.css', array(), '5.8.0' );
	wp_enqueue_style( 'font-flaticon', get_template_directory_uri() . '/assets/css/flaticon.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v2', get_template_directory_uri() . '/assets/css/flaticon-v2.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v3', get_template_directory_uri() . '/assets/css/flaticon-v3.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v4', get_template_directory_uri() . '/assets/css/flaticon-v4.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v5', get_template_directory_uri() . '/assets/css/flaticon-v5.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v6', get_template_directory_uri() . '/assets/css/flaticon-v6.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v7', get_template_directory_uri() . '/assets/css/flaticon-v7.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v8', get_template_directory_uri() . '/assets/css/flaticon-v8.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-material-icon', get_template_directory_uri() . '/assets/css/material-design-iconic-font.min.css', array(), '2.2.0' );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.0.0' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css', array(), '1.0.0' );
	wp_enqueue_style( 'consultio-theme', get_template_directory_uri() . '/assets/css/theme.css', array(), $theme->get( 'Version' ) );
	wp_add_inline_style( 'consultio-theme', consultio_inline_styles() );
	wp_enqueue_style( 'consultio-style', get_stylesheet_uri() );
	wp_enqueue_style( 'consultio-google-fonts', consultio_fonts_url() );

	/* Lib JS */
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), '4.0.0', true );
    wp_enqueue_script( 'nice-select', get_template_directory_uri() . '/assets/js/nice-select.min.js', array( 'jquery' ), 'all', true );
    wp_enqueue_script( 'match-height', get_template_directory_uri() . '/assets/js/match-height-min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/js/magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'progressbar', get_template_directory_uri() . '/assets/js/progressbar.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'wow', get_template_directory_uri() . '/assets/js/wow.min.js', array( 'jquery' ), '1.0.0', true );
    wp_register_script( 'ct-cookie', get_template_directory_uri() . '/assets/js/jquery.cookie.js', array( 'jquery' ), '1.4.1', true );
    wp_enqueue_script( 'jquery-ui-slider' );

    wp_register_script( 'gsap', get_template_directory_uri() . '/assets/js/libs/gsap.min.js', array( 'jquery' ), '3.5.0', true );
    wp_register_script( 'pxl-scroll-trigger', get_template_directory_uri() . '/assets/js/libs/scroll-trigger.js', array( 'jquery' ), '3.10.5', true );
    wp_register_script( 'pxl-splitText', get_template_directory_uri() . '/assets/js/libs/split-text.js', array( 'jquery' ), '3.6.1', true );

    /* Theme JS */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'consultio-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), $theme->get( 'Version' ), true );
	if ( class_exists( 'Woocommerce' ) ) {
		wp_enqueue_script( 'consultio-woocommerce', get_template_directory_uri() . '/woocommerce/woocommerce.js', array( 'jquery' ), $theme->get( 'Version' ), true );
	}
    /*
     * Elementor Widget JS
     */
    // Particles
    wp_register_script( 'lib-particles-js', get_template_directory_uri() . '/assets/js/particles.min.js', array( 'jquery' ), '2.0.0', true );
    wp_register_script( 'ct-particles-js', get_template_directory_uri() . '/elementor/js/ct-particles.js', array( 'jquery' ), '2.0.0', true );

    // Countdown Widget
    wp_register_script( 'ct-countdown', get_template_directory_uri() . '/elementor/js/ct-countdown.js', [ 'jquery' ], $theme->get( 'Version' ) );
    wp_register_script( 'ct-elementor-js', get_template_directory_uri() . '/elementor/js/ct-elementor.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Inline CSS
    wp_enqueue_script( 'ct-inline-css-js', get_template_directory_uri() . '/elementor/js/ct-inline-css.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Angle
    wp_register_script( 'ct-angle-js', get_template_directory_uri() . '/elementor/js/ct-angle.js', [ 'jquery' ], $theme->get( 'Version' ) );
    wp_register_script( 'ct-particle-animate-js', get_template_directory_uri() . '/elementor/js/ct-particle-animate.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Typing Out
    wp_register_script( 'ct-typing-out-js', get_template_directory_uri() . '/elementor/js/ct-typingout.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Counter Widget
    wp_register_script( 'ct-counter-widget-js', get_template_directory_uri() . '/elementor/js/ct-counter-widget.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Progress Bar Widget
    wp_register_script( 'ct-progressbar-widget-js', get_template_directory_uri() . '/elementor/js/ct-progressbar-widget.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Pie Charts Widget
    wp_register_script( 'ct-piecharts-widget-js', get_template_directory_uri() . '/elementor/js/ct-piecharts-widget.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Line Charts Widget
    wp_register_script( 'chart-js', get_template_directory_uri() . '/elementor/js/chart.min.js', array( 'jquery' ), '2.9.4', true );
    wp_register_script( 'ct-linecharts-widget-js', get_template_directory_uri() . '/elementor/js/ct-linecharts-widget.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Case Post Carousel Widget
    wp_register_script( 'ct-post-carousel-widget-js', get_template_directory_uri() . '/elementor/js/ct-post-carousel-widget.js', [ 'jquery' ], $theme->get( 'Version' ) );
    // Google Maps Widget
    $api = consultio_get_opt('gm_api_key', 'AIzaSyC08_qdlXXCWiFNVj02d-L2BDK5qr6ZnfM');
    $api_js = "https://maps.googleapis.com/maps/api/js?sensor=false&key=".$api;
    wp_register_script('maps-googleapis', $api_js, [], date("Ymd"));
    wp_register_script('custom-gm-widget-js', get_template_directory_uri() . '/elementor/js/ct-gm-widget.js', ['maps-googleapis', 'jquery'], $theme->get( 'Version' ), true);
    wp_register_script('ct-post-masonry-widget-js', get_template_directory_uri() . '/elementor/js/ct-post-masonry-widget.js', [ 'isotope', 'jquery' ], $theme->get( 'Version' ), true);
    wp_register_script('ct-post-grid-widget-js', get_template_directory_uri() . '/elementor/js/ct-post-grid-widget.js', [ 'isotope', 'jquery' ], $theme->get( 'Version' ), true);
    wp_register_script('ct-accordion-widget-js', get_template_directory_uri() . '/elementor/js/ct-accordion-widget.js', [ 'jquery' ], $theme->get( 'Version' ), true);
    wp_register_script('ct-tabs-widget-js', get_template_directory_uri() . '/elementor/js/ct-tabs-widget.js', [ 'jquery' ], $theme->get( 'Version' ), true);
    wp_localize_script( 'ct-post-masonry-widget-js', 'main_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'consultio_scripts' );

/* add admin styles */
function consultio_admin_style() {
	$theme = wp_get_theme( get_template() );
	wp_enqueue_style( 'consultio-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-material-icon', get_template_directory_uri() . '/assets/css/material-design-iconic-font.min.css', array(), '2.2.0' );
	wp_enqueue_style('font-awesome-v5', get_template_directory_uri() . '/assets/css/font-awesome5.min.css', array(), '5.8.0' );
	wp_enqueue_style( 'font-flaticon', get_template_directory_uri() . '/assets/css/flaticon.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v2', get_template_directory_uri() . '/assets/css/flaticon-v2.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v3', get_template_directory_uri() . '/assets/css/flaticon-v3.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v4', get_template_directory_uri() . '/assets/css/flaticon-v4.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v5', get_template_directory_uri() . '/assets/css/flaticon-v5.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v6', get_template_directory_uri() . '/assets/css/flaticon-v6.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v7', get_template_directory_uri() . '/assets/css/flaticon-v7.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'font-flaticon-v8', get_template_directory_uri() . '/assets/css/flaticon-v8.css', array(), $theme->get( 'Version' ) );
}

add_action( 'admin_enqueue_scripts', 'consultio_admin_style' );

/**
 * Helper functions for this theme.
 */
require_once get_template_directory() . '/inc/template-functions.php';

/**
 * Theme options
 */
require_once get_template_directory() . '/inc/theme-options.php';

/**
 * Page options
 */
require_once get_template_directory() . '/inc/page-options.php';

/**
 * Theme Config
 */
require_once get_template_directory() . '/inc/theme-config.php';

/**
 * CSS Generator.
 */
if ( ! class_exists( 'CSS_Generator' ) ) {
	require_once get_template_directory() . '/inc/classes/class-css-generator.php';
}

/**
 * Breadcrumb.
 */
require_once get_template_directory() . '/inc/classes/class-breadcrumb.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/* Load list require plugins */
require_once( get_template_directory() . '/inc/require-plugins.php' );


/**
 * Additional widgets for the theme
 */
require_once get_template_directory() . '/widgets/widget-recent-posts.php';
require_once get_template_directory() . '/widgets/widget-social.php';
require_once get_template_directory() . '/widgets/widget-getintouch.php';
require_once get_template_directory() . '/widgets/widget-newsletter.php';
require_once get_template_directory() . '/widgets/class.widget-extends.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once get_template_directory() . '/inc/extends.php';

/**
 * Elementor
*/
consultio_require_folder('inc/elementor');


if ( ! function_exists( 'consultio_fonts_url' ) ) :
	/**
	 * Register Google fonts.
	 *
	 * Create your own consultio_fonts_url() function to override in a child theme.
	 *
	 * @since league 1.1
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function consultio_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';
		
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'consultio' ) ) {
			$fonts[] = 'Roboto:300,400,400i,500,500i,600,600i,700,700i';
		}

		if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'consultio' ) ) {
			$fonts[] = 'Poppins:300,400,400i,500,500i,600,600i,700,700i';
		}

		if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Playfair Display:400,400i,700,700i,800,900';
        }

        if ( 'off' !== _x( 'on', 'Muli font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Muli:400';
        }

        if ( 'off' !== _x( 'on', 'Lato font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Lato:400';
        }

        if ( 'off' !== _x( 'on', 'Barlow font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Barlow:400,700';
        }

        if ( 'off' !== _x( 'on', 'Nunito Sans font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Nunito Sans:400,600,700,900';
        }

        if ( 'off' !== _x( 'on', 'Kalam font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Kalam:400';
        }

        if ( 'off' !== _x( 'on', 'Rubik font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Rubik:400';
        }

        if ( 'off' !== _x( 'on', 'Inter font: on or off', 'consultio' ) )
        {
            $fonts[] = 'Inter:400,500,600,700';
        }

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), '//fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
endif;


/* Disable Automatic Updates Plugins */
add_filter( 'auto_update_plugin', '__return_false' );