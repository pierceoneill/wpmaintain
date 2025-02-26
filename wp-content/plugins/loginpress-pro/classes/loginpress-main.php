<?php
/**
 * LoginPress Pro
 *
 * @package LoginPress Pro
 */

/**
 * LoginPress Pro main class
 */
class LoginPress_Pro {

	/**
	 * The name and ID of the download on WPBrigade.com for this plugin.
	 */
	const LOGINPRESS_PRODUCT_NAME = 'LoginPress Pro';
	const LOGINPRESS_PRODUCT_ID = 1837;

	/**
	 * The URL of the our store.
	 */
	const LOGINPRESS_SITE_URL = 'https://wpbrigade.com/';

	/**
	 * The WP options registration data key.
	 */
	const REGISTRATION_DATA_OPTION_KEY = 'loginpress_pro_registration_data';

	/**
	 * Function constructor
	 */
	public function __construct() {

		$this->hooks();
		$this->includes();
		$this->loginpress_pro_hook();
	}

	/**
	 * Hook into actions and filters
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'textdomain' ) );
		add_filter( 'loginpress_pro_add_template', array( $this, 'customizer_template_array' ), 10, 1 );
		add_action( 'loginpress_add_pro_theme', array( $this, 'add_pro_theme' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'loginpress_admin_scripts' ), 10, 1 );
		add_action( 'login_head', array( $this, 'login_page_custom_head' ) );
		add_action( 'admin_init', array( $this, 'init_plugin_updater' ), 0 );
		add_action( 'admin_init', array( $this, 'manage_license' ) );
		add_action( 'wp', array( $this, 'loginpress_member_only_site' ) );
		add_action( 'login_footer', array( $this, 'loginpress_custom_footer' ), 11 );
		// add_filter( 'login_message', array( $this, 'change_reset_message' ), 20 );
		add_action( 'login_enqueue_scripts', array( $this, 'loginpress_enqueue_jquery' ), 1 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'loginpress_pro_customizer_js' ) );
		add_action( 'wp_ajax_loginpress_pro_google_fonts', array( $this, 'loginpress_pro_google_fonts' ) );
		add_action( 'wp_ajax_nopriv_loginpress_pro_google_fonts', array( $this, 'loginpress_pro_google_fonts' ) );
		// add_filter( 'plugins_api', array( $this, 'install_addons' ), 100, 3 );
		if ( version_compare( LOGINPRESS_VERSION, '3.0.0', '>=' ) ) {
			add_filter( 'loginpress_settings_tab', array( $this, 'loginpress_license_tab' ), 99, 1 );
		}
	}

	/**
	 * LoginPress Customizer Scripts
	 *
	 * @return void
	 */
	public function loginpress_pro_customizer_js() {

		wp_enqueue_script( 'loginpress-pro-customize-control', plugins_url( '../assets/js/customizer.js', __FILE__ ), array( 'jquery', 'customize-preview' ), LOGINPRESS_PRO_VERSION, true );
		wp_localize_script(
			'loginpress-pro-customize-control',
			'LoginPressProCustomize',
			array(
				'font_nonce' => wp_create_nonce( 'loginpressprocustomize_nonce' ),
			)
		);
	}

	/**
	 * Force user to login before viewing the site.
	 *
	 * @since 2.0.13
	 * @version 3.0.0
	 */
	public function loginpress_member_only_site() {

		$exclude_forcelogin     = apply_filters( 'loginpress_exclude_forcelogin', false );
		$loginpress_setting     = get_option( 'loginpress_setting' );
		$force_login_ids        = apply_filters( 'loginpress_apply_forcelogin_only_on', false );
		$force_login_permission = isset( $loginpress_setting['force_login'] ) ? $loginpress_setting['force_login'] : 'off';

		/**
		 * This filter will disable the "Force Login" and will enable it only on certain posts/Pages.
		 *
		 * @since 3.0.0
		 */
		if ( 'on' === $force_login_permission && false !== $force_login_ids && function_exists( 'is_user_logged_in' ) && ! is_user_logged_in() ) {
			global $post;
			$post_id   = isset( $post->ID ) ? $post->ID : false;
			$post_slug = isset( $post->post_name ) ? $post->post_name : false;

			// if array is provided by user.
			if ( is_array( $force_login_ids ) ) {
				foreach ( $force_login_ids as $value ) {
					if ( $post_slug === $value || $post_id === $value ) {
						auth_redirect();
					}
				}
			} else {
				// if single value is provided by user.
				if ( $post_slug === $force_login_ids || $post_id === $force_login_ids ) {
					auth_redirect();
				}
			}
			return;
		}

		/**
		 * Exclude 404 Page from Force Login.
		 *
		 * @version 3.0.0
		 */
		if ( is_404() ) {
			return;
		}

		if ( 'on' === $force_login_permission && function_exists( 'is_user_logged_in' ) && ! is_user_logged_in() ) {

			global $post;
			$post_id   = isset( $post->ID ) ? $post->ID : false;
			$post_slug = isset( $post->post_name ) ? $post->post_name : false;

			if ( $post_slug && false !== $exclude_forcelogin ) {

				// if array is provided by user.
				if ( is_array( $exclude_forcelogin ) ) {
					foreach ( $exclude_forcelogin as $value ) {
						if ( $post_slug === $value || $post_id === $value ) {
							return;
						}
					}
				} else {
					// if single value is provided by user.
					if ( $post_slug === $exclude_forcelogin || $post_id === $exclude_forcelogin ) {
						return;
					}
				}
			}

			/**
			 * Hook to prevent force login.
			 *
			 * @since 2.4.0
			 */
			if ( apply_filters( 'loginpress_prevent_forcelogin', false ) ) {
				return;
			}

			if ( apply_filters( 'loginpress_forcelogin_noauth', false ) ) {
				wp_safe_redirect( wp_login_url() );
			} else {
				auth_redirect();
			}
		}
	}

	/**
	 * Install LoginPress add-ons for registered users automatically from WordPress.
	 *
	 * @param obj    $api The API.
	 * @param string $action The Action to perform.
	 * @param string $args The arguments.
	 */
	public function install_addons( $api, $action, $args ) {

		$data = array();

		if ( 'plugin_information' === $action && empty( $api ) && ( ! empty( $_GET['lgp'] )  ) ) {

			if ( ! self::is_registered() ) {
				echo 'No license key Registered by user.';
				return false;
			}

			$data['license'] = get_option( 'loginpress_pro_license_key' );

			if ( empty( $data['license'] ) ) {
				echo 'No license key entered by user.';
				return false;
			}

			$api_params = array(
				'loginpress_action' => 'install-loginpress-addons',
				'license'           => $data['license'],
				'slug'              => $args->slug,
				'addon_id'          => absint( $_GET['id'] ),
				'url'               => home_url(),
			);

			$request = wp_remote_post(
				self::LOGINPRESS_SITE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// $raw_response = wp_remote_post( self::LOGINPRESS_SITE_URL, array(
			//   'body' => array(
			//     'slug' => $args->slug,
			//     'loginpress_action' => 'install-loginpress-addons',
			//   ) )
			//  );

			//  echo 'dont cheet';
			// return false;

			//var_dump(wp_remote_retrieve_body( $raw_response ));wp_die();

			if ( is_wp_error( $request ) || 200 !== $request['response']['code'] ) {
				// echo '<pre>' . print_r( $request->get_error_message(), true ) . '</pre>';
				return false;
			} else {

				//$plugin = unserialize( $raw_response['body'] );

				$plugin = json_decode( wp_remote_retrieve_body( $request ) );
				//echo '<pre>' . print_r( $plugin, true ) . '</pre>';

				$api                = new stdClass();
				$api->name          = $plugin->name;
				$api->version       = $plugin->new_version;
				$api->download_link = $plugin->download_url;
			}
		}

		return $api;
	}

	/**
	 * LoginPress google Fonts.
	 *
	 * @return void
	 */
	public function loginpress_pro_google_fonts() {

		check_ajax_referer( 'loginpressprocustomize_nonce', 'nonce' );
		if ( current_user_can( 'manage_options' ) ) {
			$loginpress_google_font = isset( $_POST['fontName'] ) ? sanitize_text_field( wp_unslash( $_POST['fontName'] ) ) : false;

			if ( ! $loginpress_google_font ) {
				return;
			}

			$json_file       = file_get_contents( LOGINPRESS_PRO_ROOT_PATH . '/fonts/google-web-fonts.txt' );
			$json_font       = json_decode( $json_file );
			$json_font_array = $json_font->items;
			$font_array      = array();

			foreach ( $json_font_array as $key ) {
				$loginpress_get_font = $loginpress_google_font === $key->family ? $loginpress_google_font : false;
				if ( $loginpress_get_font ) :
					$font_array[] = $key;
				endif;
			}

			$loginpress_font_name = $font_array[0]->family;
			$font_weights         = $font_array[0]->variants;
			$font_weight          = implode( ',', $font_weights );
			$subsets              = $font_array[0]->subsets;
			$subset               = implode( ',', $subsets );
			$font_families        = array();
			$font_families[]      = "{$loginpress_font_name}:{$font_weight}";
			$query_args           = array(
				'family' => rawurlencode( implode( '|', $font_families ) ),
				'subset' => rawurlencode( $subset ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

			echo esc_url_raw( $fonts_url );
		}
		wp_die();
	}

	/**
	 * Load Languages
	 *
	 * @since 1.0.0
	 * @version 2.1.4
	 */
	public function textdomain() {

		load_plugin_textdomain( 'loginpress-pro', false, LOGINPRESS_PRO_PLUGIN_ROOT . '/languages/' );
	}

	/**
	 * Create Admin Menu Page.
	 *
	 * @since 1.0.0
	 * @version 2.1.1
	 */
	public function admin_menu() {

		/**
		 * Add sub-menu page for Managing the License.
		 */
		add_submenu_page(
			'loginpress-settings',
			esc_html__( 'Activate your license to get automatic plugin updates and premium support.', 'loginpress-pro' ),
			'<b style="color:#5fcf80">' . esc_html__( 'License Manager', 'loginpress-pro' ) . '</b>',
			'administrator',
			'loginpress-license',
			array( $this, 'loginpress_license' )
		);

		/**
		 * Apply filters for hide/show the LoginPress license page.
		 *
		 * @since 2.1.1
		 * @var boolean
		 */
		$show_license = apply_filters( 'loginpress_show_license_page', true );

		if ( ! $show_license ) { // 2.1.1
			add_action( 'admin_menu', array( $this, 'loginress_license_menu_page_removing' ), 11 );
		}
	}

	/**
	 * LoginPress License
	 *
	 * @return void
	 */
	public function loginpress_license() {

		$screen = get_current_screen();

		if ( strpos( $screen->base, 'loginpress-license' ) !== false ) {
			include_once LOGINPRESS_PRO_ROOT_PATH . '/includes/license-manager.php';
		}

	}

	/**
	 * Return array of loginpress_customization
	 *
	 * @since 2.0.6
	 * @return array
	 */
	public function loginpress_customization_array() {

		$loginpress_key = get_option( 'loginpress_customization' );

		if ( is_array( $loginpress_key ) ) {
			return $loginpress_key;
		} else {
			return array();
		}
	}

	/**
	 * Call WordPress hooks if array_key_exists in $loginpress_key
	 *
	 * @since 2.0.3
	 */
	public function loginpress_pro_hook() {

		$loginpress_key = $this->loginpress_customization_array();

		if ( array_key_exists( 'reset_hint_text', $loginpress_key ) && ! empty( $loginpress_key['reset_hint_text'] ) ) {
			add_filter( 'password_hint', array( $this, 'loginpress_password_hint' ) );
		}
	}

	/**
	 * WordPress reset password hint text.
	 *
	 * @since 2.0.3
	 *
	 * @return string $reset_hint
	 */
	public function loginpress_password_hint() {

		$loginpress_key = $this->loginpress_customization_array();
		$reset_hint     = $loginpress_key['reset_hint_text'];

		return esc_js( $reset_hint );
	}

	/**
	 * Change reset password hint text.
	 *
	 * @param string $message The change rest message.
	 * @since 2.0.3
	 *
	 * @return HTML the message with a p tag.
	 */
	public function change_reset_message( $message ) {

		$loginpress_key = $this->loginpress_customization_array();

		if ( $loginpress_key ) {
			if ( 'rp' === $_GET['action'] ) {
				if ( array_key_exists( 'reset_hint_message', $loginpress_key ) && ! empty( $loginpress_key['reset_hint_message'] ) ) {
					$message = $loginpress_key['reset_hint_message'];
					return ! empty( $message ) ? "<p class='message'>" . $message . '</p>' : '';
				}
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since 1.0.0
	 * @version 3.1.3
	 */
	public function includes() {

		if ( is_admin() ) {
			include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-addon-updater.php';
			include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-ajax.php';
		}

		// include_once( LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-license.php' );.
		// include_once( LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-license-2.php' );.

		include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-recaptcha.php';
		new LoginPress_Recaptcha();

		include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-customize.php';
		new LoginPress_Pro_Entities();

		include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-manage-addons.php';
		new LoginPress_Manage_Addons();

		$loginpress_setting = get_option( 'loginpress_setting' );

		if ( isset( $loginpress_setting['enable_user_verification'] ) && 'on' === $loginpress_setting['enable_user_verification'] ) {
			include_once LOGINPRESS_PRO_ROOT_PATH . '/classes/loginpress-approve-user.php';
			new LoginPress_Approve_User();
		}
	}

	/**
	 * The admin scripts.
	 *
	 * @param int $hook the page ID.
	 *
	 * @return void
	 */
	public function loginpress_admin_scripts( $hook ) {

		if ( 'toplevel_page_loginpress-settings' === $hook || 'loginpress_page_loginpress-license' === $hook || 'users.php' === $hook || 'widgets.php' === $hook ) {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'main-js', plugins_url( '../assets/js/main.js', __FILE__ ), array( 'jquery' ), LOGINPRESS_PRO_VERSION, true );
			wp_localize_script(
				'main-js',
				'loginpressLicense',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'license_nonce' => wp_create_nonce( 'loginpress_deactivate_license' ),
					'admin_url'      => admin_url(),
				)
			);
		}
	}

	/**
	 * Enqueue jQuery on login page.
	 *
	 * @since 2.0.10
	 * @return void
	 */
	public function loginpress_enqueue_jquery() {

		wp_enqueue_script( 'jquery', false, array(), LOGINPRESS_PRO_VERSION, false );
	}

	/**
	 * Manage the Login Head to handle the style on login page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function login_page_custom_head() {

		// Include CSS File in head.
		include LOGINPRESS_PRO_DIR_PATH . 'assets/css/style-login.php';
	}

	/**
	 * Customizer templates.
	 *
	 * @param array $loginpress_free_themes Custom LoginPress Themes.
	 * @since 1.0.0
	 *
	 * @return array[templates] $loginpress_pro_templates Custom LoginPress Themes.
	 */
	public function customizer_template_array( $loginpress_free_themes ) {

		$loginpress_pro_array  = array();
		$loginpress_theme_name = array(
			'',
			'',
			__( 'Company', 'loginpress-pro' ),
			__( 'Persona', 'loginpress-pro' ),
			__( 'Corporate', 'loginpress-pro' ),
			__( 'Corporate', 'loginpress-pro' ),
			__( 'Startup', 'loginpress-pro' ),
			__( 'Wedding', 'loginpress-pro' ),
			__( 'Wedding #2', 'loginpress-pro' ),
			__( 'Company', 'loginpress-pro' ),
			__( 'Bikers', 'loginpress-pro' ),
			__( 'Fitness', 'loginpress-pro' ),
			__( 'Shopping', 'loginpress-pro' ),
			__( 'Writers', 'loginpress-pro' ),
			__( 'Persona', 'loginpress-pro' ),
			__( 'Geek', 'loginpress-pro' ),
			__( 'Innovation', 'loginpress-pro' ),
			__( 'Photographers', 'loginpress-pro' ),
			__( 'Animated Wapo', 'loginpress-pro' ),
			__( 'Animated Wapo 2', 'loginpress-pro' ),
		);

		$_count = 2;
		while ( $_count <= 18 ) :

			$loginpress_pro_array[ "default{$_count}" ] = array(
				'img'       => plugins_url( "loginpress/img/bg{$_count}.jpg", LOGINPRESS_ROOT_PATH ),
				'thumbnail' => plugins_url( "loginpress/img/thumbnail/default-{$_count}.png", LOGINPRESS_ROOT_PATH ),
				'id'        => "default{$_count}",
				'name'      => $loginpress_theme_name[ $_count ],
			);
			$_count++;
		endwhile;

		$loginpress_pro_offer = array(
			'default19' => array(
				'img'       => plugins_url( 'loginpress/assets/img/bg17.jpg', LOGINPRESS_ROOT_PATH ),
				'thumbnail' => plugins_url( 'loginpress/img/thumbnail/custom-design.png', LOGINPRESS_ROOT_PATH ),
				'id'        => 'default19',
				'name'      => __( 'Custom Design', 'loginpress-pro' ),
				'link'      => 'yes',
			),
		);

		$loginpress_pro_themes    = array_merge( $loginpress_pro_array, $loginpress_pro_offer );
		$loginpress_pro_templates = array_merge( $loginpress_free_themes, $loginpress_pro_themes );
		return $loginpress_pro_templates;
	}

	/**
	 * Load the Pro Templates.
	 *
	 * @param string $selected_preset Selected preset.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_pro_theme( $selected_preset ) {

		include_once apply_filters( 'loginpress_premium_theme', LOGINPRESS_PRO_THEME . $selected_preset . '.php' );
	}

	/**
	 * Initialize the plugin updater class.
	 *
	 * @return void
	 */
	public function init_plugin_updater() {

		// Skip the plugn updater init, if the plugin is not registered, or if the license has expired.
		// if ( ! $this->is_registered() || $this->has_license_expired() ) {
			// return false;
		// }.

		// Require the updater class, if not already present.
		if ( ! class_exists( 'LOGINPRESS_PRO_SL_Plugin_Updater' ) ) {
			include_once LOGINPRESS_PRO_ROOT_PATH . '/lib/LOGINPRESS_PRO_SL_Plugin_Updater.php';
		}

		// Retrieve our license key from the DB.
		$license_key = $this->get_registered_license_key();

		// Setup the updater.
		try {
			// Initialize the updater.
			$edd_updater = new LOGINPRESS_PRO_SL_Plugin_Updater(
				self::LOGINPRESS_SITE_URL,
				LOGINPRESS_PRO_UPGRADE_PATH,
				array(
					'version' => LOGINPRESS_PRO_VERSION,
					'license' => $license_key,
					'item_id' => self::LOGINPRESS_PRODUCT_ID,
					'author'  => 'captian',
					'beta'    => false,
					'timeout'   => 15,
				)
			);
		} catch ( Exception $e ) {
			error_log( 'LoginPress Updater failed: ' . $e->getMessage() );
		}
	}

	// function ssb_pro_sanitize_license( $new ) {

	// 	$old = get_option( 'loginpress_pro_license_key' );
	// 	if( $old && $old != $new ) {
	// 		delete_option( 'loginpress_pro_license_status' ); // new license has been entered, so must reactivate
	// 	}
	// 	return $new;
	// }

	/**
	 * Manage LoginPress Pro license
	 *
	 * @version 2.2.2
	 */
	public static function manage_license() {

		if ( is_admin() ) {
			$hook = isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false;
			if ( version_compare( LOGINPRESS_VERSION, '3.0.5', '>=' ) ) {
				$upgrade_object = new LoginPress_Pro_Setup_30( false );
			}
		}

		if ( $hook && ( 'loginpress-settings' === $hook || 'loginpress-help' === $hook || 'loginpress-addons' === $hook ) && ( version_compare( LOGINPRESS_VERSION, '3.0.5', '<=' ) || ! $upgrade_object->is_all_addons_updated() ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=loginpress-setup-30' ) );
		}
		// Creates our settings in the options table.
		register_setting( 'loginpress_pro_license', 'loginpress_pro_license_key' );

		// Listen for our activate button to be clicked.
		if ( isset( $_POST['loginpress_pro_license_activate'] ) && check_ajax_referer( 'loginpress_pro_activate_license_nonce', 'loginpress_pro_activate_license_nonce' ) ) {

			$registration_data = self::activate_license( sanitize_text_field( wp_unslash( $_POST['loginpress_pro_license_key'] ) ) );
		}

		// Listen for our deactivate button to be clicked.
		if ( isset( $_POST['loginpress_pro_license_deactivate'] ) && check_ajax_referer( 'loginpress_pro_deactivate_license_nonce', 'loginpress_pro_deactivate_license_nonce' ) ) {

			$license           = get_option( 'loginpress_pro_license_key' );
			$registration_data = self::deactivate_license( sanitize_text_field( wp_unslash( $license ) ) );
		}

	}

	/**
	 * Try to activate the supplied license on our store.
	 *
	 * @param string $license License key to activate.
	 *
	 * @return array
	 */
	public static function activate_license( $license ) {

		$license = trim( $license );

		$result = array(
			'license_key'   => $license,
			'license_data'  => array(),
			'error_message' => '',
		);

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => self::LOGINPRESS_PRODUCT_ID,
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			self::LOGINPRESS_SITE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// Make sure the response is not WP_Error.
		if ( is_wp_error( $response ) ) {
			$result['error_message'] = $response->get_error_message() . esc_html__( 'If this error keeps displaying, please contact our support at wpbrigade.com!', 'loginpress-pro' );

			return $result;
		}

		// Make sure the response is OK (200).
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$result['error_message'] = esc_html__( 'An error occurred, please try again.', 'loginpress-pro' ) . esc_html__( 'An error occurred, please try again. If this error keeps displaying, please contact our support at wpbrigade.com!', 'loginpress-pro' );

			return $result;
		}

		// Get the response data.
		$result['license_data'] = json_decode( wp_remote_retrieve_body( $response ), true );

		// Generate the error message.
		if ( false === $result['license_data']['success'] ) {

			switch ( $result['license_data']['error'] ) {

				case 'expired':
					$result['error_message'] = sprintf(
					/* Translators: Licence key is expired */
						esc_html__( 'Your license key expired on %s.', 'loginpress-pro' ),
						date_i18n( get_option( 'date_format' ), strtotime( $result['license_data']['expires'], current_time( 'timestamp' ) ) ) // @codingStandardsIgnoreLine.
					);
					break;

				case 'revoked':
					$result['error_message'] = esc_html__( 'Your license key has been disabled.', 'loginpress-pro' );
					break;

				case 'missing':
					$result['error_message'] = esc_html__( 'Your license key is Invalid.', 'loginpress-pro' );
					break;

				case 'invalid':
				case 'site_inactive':
					$result['error_message'] = esc_html__( 'Your license is not active for this URL.', 'loginpress-pro' );
					break;

				case 'item_name_mismatch':
					/* Translators: Licence key is invalid */
					$result['error_message'] = sprintf( esc_html__( 'This appears to be an invalid license key for %s.', 'loginpress-pro' ), self::LOGINPRESS_PRODUCT_NAME );
					break;

				case 'no_activations_left':
					$result['error_message'] = esc_html__( 'Your license key has reached its activation limit.', 'loginpress-pro' );
					break;

				default:
					$result['error_message'] = esc_html__( 'An error occurred, please try again.', 'loginpress-pro' );
					break;
			}
		}

		update_option( self::REGISTRATION_DATA_OPTION_KEY, $result );

		return $result;
	}

	/**
	 * Try to deactivate the supplied license on our store.
	 *
	 * @param string $license License key to activate.
	 *
	 * @return array
	 */
	public static function deactivate_license( $license ) {

		$license = trim( $license );

		$result = array(
			'license_key'   => $license,
			'license_data'  => array(),
			'error_message' => '',
		);

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_id'    => self::LOGINPRESS_PRODUCT_ID,
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			self::LOGINPRESS_SITE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// Make sure the response is not WP_Error.
		if ( is_wp_error( $response ) ) {
			$result['error_message'] = $response->get_error_message() . esc_html__( 'If this error keeps displaying, please contact our support at wpbrigade.com!', 'loginpress-pro' );

			return $result;
		}

		// Make sure the response is OK (200).
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$result['error_message'] = esc_html__( 'An error occurred, please try again.', 'loginpress-pro' ) . esc_html__( 'An error occurred, please try again. If this error keeps displaying, please contact our support at wpbrigade.com!', 'loginpress-pro' );

			return $result;
		}

		// Get the response data.
		$result['license_data'] = json_decode( wp_remote_retrieve_body( $response ), true );

		// Generate the error message.
		if ( false === $result['license_data']['success'] ) {

			switch ( $result['license_data']['error'] ) {

				case 'expired':
					$result['error_message'] = sprintf(
					/* Translators: Licence key is expired */
						esc_html__( 'Your license key expired on %s.', 'loginpress-pro' ),
						date_i18n( get_option( 'date_format' ), strtotime( $result['license_data']['expires'], current_time( 'timestamp' ) ) ) // @codingStandardsIgnoreLine.
					);
					break;

				case 'revoked':
					$result['error_message'] = esc_html__( 'Your license key has been disabled.', 'loginpress-pro' );
					break;

				case 'missing':
					$result['error_message'] = esc_html__( 'Your license key is Invalid.', 'loginpress-pro' );
					break;

				case 'invalid':
				case 'site_inactive':
					$result['error_message'] = esc_html__( 'Your license is not active for this URL.', 'loginpress-pro' );
					break;

				case 'item_name_mismatch':
					/* Translators: Licence key is invalid */
					$result['error_message'] = sprintf( esc_html__( 'This appears to be an invalid license key for %s.', '	loginpress-pro' ), self::LOGINPRESS_PRODUCT_NAME );
					break;

				case 'no_activations_left':
					$result['error_message'] = esc_html__( 'Your license key has reached its activation limit.', 'loginpress-pro' );
					break;

				default:
					$result['error_message'] = esc_html__( 'An error occurred, please try again.', 'loginpress-pro' );
					break;
			}
		}

		update_option( self::REGISTRATION_DATA_OPTION_KEY, $result );

		return $result;
	}

	/**
	 * Check and get the license data.
	 *
	 * @param string $license The license key.
	 *
	 * @return false|array
	 */
	public static function check_license( $license ) {

		$license = trim( $license );

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_id'    => self::LOGINPRESS_PRODUCT_ID,
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			self::LOGINPRESS_SITE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Get the registration data helper function.
	 *
	 * @return false|array
	 */
	public static function get_registration_data() {
		return get_option( self::REGISTRATION_DATA_OPTION_KEY );
	}

	/**
	 * Is license activated
	 *
	 * @return bool
	 */
	public static function is_activated() {

		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['license_data']['license'] ) && 'valid' === $data['license_data']['license'] ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Get the License type
	 *
	 * @return string the license type.
	 */
	public static function get_license_type() {

		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['license_data']['success'] ) && ! empty( $data['license_data']['license'] ) && 'valid' === $data['license_data']['license'] ) {

			if ( $data['license_data']['price_id'] == 1 ) {
				return 'Personal';
			}
			if ( $data['license_data']['price_id'] == 2 ) {
				return 'Small Business';
			}
			if ( $data['license_data']['price_id'] == 3 ) {
				return 'Agency';
			}
			if ( $data['license_data']['price_id'] == 4 ) {
				return 'Ultimate';
			}
			if ( $data['license_data']['price_id'] == 7 ) {
				return 'Startup';
			}

		}

	}

	/**
	 * Get the license id
	 *
	 * @return int the license id.
	 */
	public static function get_license_id() {

		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['license_data']['success'] ) && ! empty( $data['license_data']['license'] ) && 'valid' === $data['license_data']['license'] ) {

			return $data['license_data']['price_id'];
		}

	}

	/**
	 * Check if the license is registered (has/had a valid license).
	 *
	 * @return bool
	 */
	public static function is_registered() {

		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['license_data']['success'] ) && ! empty( $data['license_data']['license'] ) && 'valid' === $data['license_data']['license'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Mask on License Key.
	 *
	 * @param string $key license key.
	 * @since 2.1.1
	 */
	public static function mask_license( $key ) {

		$license_parts  = str_split( $key, 4 );
		$i              = count( $license_parts ) - 1;
		$masked_license = '';

		foreach ( $license_parts as $license_part ) {
			if ( 0 === $i ) {
				$masked_license .= $license_part;
				continue;
			}

			$masked_license .= str_repeat( '&bull;', strlen( $license_part ) ) . '&ndash;';
			--$i;
		}

		return $masked_license;

	}

	/**
	 * Get the registered license key.
	 *
	 * @return bool|string
	 */
	public static function get_registered_license_key() {
		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return '';
		}

		if ( empty( $data['license_key'] ) ) {
			return '';
		}

		return $data['license_key'];
	}

	/**
	 * Get the registered license status.
	 *
	 * @return bool|string
	 * @version 2.2.2
	 */
	public static function get_registered_license_status() {
		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return '';
		}

		if ( ! empty( $data['error_message'] ) ) {
			return $data['error_message'];
		}

		switch ( $data['license_data']['license'] ) {
			case 'deactivated':
				$message = sprintf(
					/* Translators: Automatic Deactivated */
					esc_html__( 'Your license key has been deactivated on %s. Please Activate your license key to continue using Automatic Updates and Premium Support.', 'loginpress-pro' ),
					'<strong>' . date_i18n( get_option( 'date_format' ), current_time( 'timestamp' ) ) . '</strong>' // @codingStandardsIgnoreLine.
				);
				delete_option( 'loginpress_pro_license_key' );
				return $message;

			case 'revoked':
				$message = esc_html__( 'Your license key has been disabled.', 'loginpress-pro' );
				return $message;
		}

		return $data['license_data']['license'];
	}

	/**
	 * Check, if the registered license has expired.
	 *
	 * @return bool
	 */
	public static function has_license_expired() {
		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return true;
		}

		if ( empty( $data['license_data']['expires'] ) ) {
			return true;
		}

		// If it's a lifetime license, it never expires.
		if ( 'lifetime' === $data['license_data']['expires'] ) {
			return false;
		}

		$now             = new \DateTime();
		$expiration_date = new \DateTime( $data['license_data']['expires'] );

		$is_expired = $now > $expiration_date;

		if ( ! $is_expired ) {
			return false;
		}

		$prevent_check = get_transient( 'loginpress-pro-dont-check-license' );

		if ( $prevent_check ) {
			return true;
		}

		$new_license_data = self::check_license( self::get_registered_license_key() );
		set_transient( 'loginpress-pro-dont-check-license', true, DAY_IN_SECONDS );

		if ( empty( $new_license_data ) ) {
			return true;
		}

		if (
		! empty( $new_license_data['success'] ) &&
		! empty( $new_license_data['license'] ) &&
		'valid' === $new_license_data['license']
		) {
			$new_expiration_date = new \DateTime( $new_license_data['expires'] );

			$new_is_expired = $now > $new_expiration_date;

			if ( ! $new_is_expired ) {
				$data['license_data']['expires'] = $new_license_data['expires'];

				update_option( self::REGISTRATION_DATA_OPTION_KEY, $data );
			}

			return $new_is_expired;
		}

		return true;
	}

	/**
	 * Get license expiration date.
	 *
	 * @return string
	 */
	public static function get_expiration_date() {
		$data = self::get_registration_data();

		if ( empty( $data ) ) {
			return '';
		}

		return ( ! empty( $data['license_data']['expires'] ) ) ? $data['license_data']['expires'] : '';
	}

	/**
	 * Delete License options
	 *
	 * @return void
	 */
	public static function del_license_data() {
		delete_option( 'loginpress_pro_license_key' );
		delete_option( self::REGISTRATION_DATA_OPTION_KEY );
	}

	/**
	 * LoginPress_license_menu_page_removing Remove LoginPress License page.
	 *
	 * @since 2.1.1
	 */
	public function loginress_license_menu_page_removing() {

		remove_submenu_page( 'loginpress-settings', 'loginpress-license' );
	}

	/**
	 * LoginPress_custom_footer is used to call the script in footer.
	 *
	 * @since 2.3.0
	 */
	public function loginpress_custom_footer() {

		include LOGINPRESS_PRO_ROOT_PATH . '/assets/js/script-login.php';
	}

	/**
	 * Check LoginPress module compatibility.
	 *
	 * @param string $slug The addon slug.
	 *
	 * @since 3.0.0
	 * @return boolean
	 */
	public static function addon_wrapper( $slug ) {
		include LOGINPRESS_DIR_PATH . 'classes/class-loginpress-addons.php';

		if ( defined( 'LOGINPRESS_VERSION' ) && class_exists( 'LoginPress_Addons' ) ) {
			$obj_loginpress_addons = new LoginPress_Addons();
		}

		if ( defined( 'LOGINPRESS_VERSION' ) && version_compare( LOGINPRESS_VERSION, '3.0.5', '>=' ) ) {
			//if ( $obj_loginpress_addons->license_life( $slug ) ) {
				return true;
			// } else {
			// 	return false;
			// }
		}
	}

	/**
	 * Adding a tab for Licensing at LoginPress Settings Page.
	 *
	 * @param  array $loginpress_tabs Rest of the settings tabs of LoginPress.
	 * @return array $loginpress_pro_tabs License tab.
	 * @since  3.0.0
	 */
	public function loginpress_license_tab( $loginpress_tabs ) {
		$license_tab = array(
			array(
				'id'         => 'loginpress_pro_license',
				'title'      => __( 'License Manager', 'loginpress-pro' ),
				'sub-title'  => __( 'Manage Your License Key', 'loginpress' ),
				/* Translators: %1$s The line break tag. */
				'desc'       => sprintf( __( 'Validating license key is mandatory to use automatic updates and plugin support.', 'loginpress-pro' ), '<p>', '</p>' ),
				'video_link' => 'M2M3G2TB9Dk',
			),
		);

		$loginpress_pro_tabs = array_merge( $loginpress_tabs, $license_tab );

		return $loginpress_pro_tabs;
	}
}
