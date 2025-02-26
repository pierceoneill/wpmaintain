<?php
/**
 * LoginPress reCAPTCHA.
 *
 * @since 1.0.1
 * @version 3.2.0
 * @package LoginPress
 */

if ( ! class_exists( 'LoginPress_Recaptcha' ) ) {

	/**
	 * LoginPress_Recaptcha
	 */
	class LoginPress_Recaptcha {

		/**
		 * Variable that Check for LoginPress settings.
		 *
		 * @var string
		 * @since 2.0.1
		 */
		public $loginpress_settings;

		/**
		 * Class Constructor
		 */
		public function __construct() {

			$this->loginpress_settings = get_option( 'loginpress_setting' );
			$this->hooks();
		}

		/**
		 * Add all hooks.
		 *
		 * @return void
		 */
		private function hooks() {

			add_filter( 'loginpress_pro_settings', array( $this, 'loginpress_pro_settings_array' ), 10, 1 );

			$cap_permission = isset( $this->loginpress_settings['enable_repatcha'] ) ? $this->loginpress_settings['enable_repatcha'] : 'off';

			if ( 'off' === $cap_permission ) {
				return;
			}

			$cap_type       = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';
			$cap_site       = isset( $this->loginpress_settings['site_key'] ) ? $this->loginpress_settings['site_key'] : '';
			$cap_secret     = isset( $this->loginpress_settings['secret_key'] ) ? $this->loginpress_settings['secret_key'] : '';
			$cap_site_v2    = isset( $this->loginpress_settings['site_key_v2_invisible'] ) ? $this->loginpress_settings['site_key_v2_invisible'] : '';
			$cap_secret_v2  = isset( $this->loginpress_settings['secret_key_v2_invisible'] ) ? $this->loginpress_settings['secret_key_v2_invisible'] : '';
			$cap_site_v3    = isset( $this->loginpress_settings['site_key_v3'] ) ? $this->loginpress_settings['site_key_v3'] : '';
			$cap_secret_v3  = isset( $this->loginpress_settings['secret_key_v3'] ) ? $this->loginpress_settings['secret_key_v3'] : '';

			// Return from reCaptcha if PowerPack login or registration nonce set.
			if ( isset( $_POST['pp-lf-login-nonce'] ) || isset( $_POST['pp-registration-nonce'] ) || ( isset( $_POST['action'] ) && sanitize_text_field( $_POST['action'] ) === 'loginpress_widget_login_process' ) ) { // @codingStandardsIgnoreLine.
				return;
			}

			// Validate reCaptcha based on type and corresponding keys.
			if (
			( 'v2-robot' === $cap_type && ( empty( $cap_site ) || empty( $cap_secret ) ) ) ||
			( 'v2-invisible' === $cap_type && ( empty( $cap_site_v2 ) || empty( $cap_secret_v2 ) ) ) ||
			( 'v3' === $cap_type && ( empty( $cap_site_v3 ) || empty( $cap_secret_v3 ) ) ) ) {
				return;
			}

			$cap_login           = isset( $this->loginpress_settings['captcha_enable']['login_form'] ) ? $this->loginpress_settings['captcha_enable']['login_form'] : false;
			$cap_comments        = isset( $this->loginpress_settings['captcha_enable']['comment_form_defaults'] ) ? $this->loginpress_settings['captcha_enable']['comment_form_defaults'] : false;
			$cap_lost            = isset( $this->loginpress_settings['captcha_enable']['lostpassword_form'] ) ? $this->loginpress_settings['captcha_enable']['lostpassword_form'] : false;
			$cap_register        = isset( $this->loginpress_settings['captcha_enable']['register_form'] ) ? $this->loginpress_settings['captcha_enable']['register_form'] : false;
			$woo_login_enable    = isset( $this->loginpress_settings['captcha_enable']['woocommerce_login_form'] ) ? $this->loginpress_settings['captcha_enable']['woocommerce_login_form'] : false;
			$woo_register_enable = isset( $this->loginpress_settings['captcha_enable']['woocommerce_register_form'] ) ? $this->loginpress_settings['captcha_enable']['woocommerce_register_form'] : false;
			$action              = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : ''; // @codingStandardsIgnoreLine.
			/* Add reCAPTCHA on login form */
			if ( $cap_login ) {
				add_action( 'login_form', array( $this, 'loginpress_recaptcha_field' ) );
				add_action( 'login_enqueue_scripts', array( $this, 'loginpress_recaptcha_script' ) );
			}

			/* Add reCAPTCHA on Lost password form */
			if ( $cap_lost ) {
				add_action( 'lostpassword_form', array( $this, 'loginpress_recaptcha_field' ) );
				add_action( 'login_enqueue_scripts', array( $this, 'loginpress_recaptcha_script' ) );
			}

			/**
			 * If WooCommerce is activated and either option is selected.
			 *
			 * @since 3.0.0
			 */
			if ( class_exists( 'woocommerce' ) ) {

				if ( $woo_register_enable ) {
					add_action( 'woocommerce_register_form', array( $this, 'loginpress_recaptcha_script' ) );
					add_action( 'woocommerce_register_form', array( $this, 'loginpress_recaptcha_field' ) );
					add_filter( 'woocommerce_process_registration_errors', array( $this, 'loginpress_recaptcha_registration_auth' ), 10, 3 );

				} else {
					// return from reCaptcha if woocommerce login or registration nonce set.
					if ( isset( $_POST['woocommerce-register-nonce'] ) ) { // @codingStandardsIgnoreLine.
						return;
					}
				}

				if ( $woo_login_enable ) {
					add_action( 'woocommerce_login_form', array( $this, 'loginpress_recaptcha_script' ) );
					add_action( 'woocommerce_login_form', array( $this, 'loginpress_recaptcha_field' ) );
					add_filter( 'authenticate', array( $this, 'loginpress_recaptcha_auth' ), 99, 3 );

				} else {
					// return from reCaptcha if woocommerce login or registration nonce set.
					if ( isset( $_POST['woocommerce-login-nonce'] ) ) { // @codingStandardsIgnoreLine.
						return;
					}
				}
			}
			/**
			 * Add reCAPTCHA on comments form.
			 *
			 * @since 3.0.0
			 */
			if ( $cap_comments ) {

				/* Add reCAPTCHA in comments */

				/* Add reCAPTCHA scripts for comments */
				add_action( 'comment_form', array( $this, 'loginpress_recaptcha_script' ) );

				/* Add reCAPTCHA field for comments */
				add_action( 'comment_id_fields', array( $this, 'comment_loginpress_recaptcha_field' ) );

				/* Add reCAPTCHA authentication on comments */
				add_action( 'pre_comment_on_post', array( $this, 'loginpress_recaptcha_comment' ), 10, 3 );
			}

			/* Add reCAPTCHA on registration form */
			if ( $cap_register ) {
				add_action( 'register_form', array( $this, 'loginpress_recaptcha_field' ), 99 );
				add_action( 'login_enqueue_scripts', array( $this, 'loginpress_recaptcha_script' ) );

			}

			/* Authentication reCAPTCHA on login form */
			if ( ! isset( $_GET['customize_changeset_uuid'] ) && $cap_login ) {
				add_filter( 'authenticate', array( $this, 'loginpress_recaptcha_auth' ), 99, 3 );
			}

			/* Authentication reCAPTCHA on lost-password form */
			if ( ! isset( $_GET['customize_changeset_uuid'] ) && $cap_lost && isset( $_GET['action'] ) && $_GET['action'] === 'lostpassword' ) {
				add_filter( 'allow_password_reset', array( $this, 'loginpress_recaptcha_lostpassword_auth' ), 10, 2 );
			}

			/* Authentication reCAPTCHA on registration form */
			if ( ! isset( $_GET['customize_changeset_uuid'] ) && $cap_register && 'register' === $action ) {
				add_filter( 'registration_errors', array( $this, 'loginpress_recaptcha_registration_auth' ), 10, 3 );
			}
		}

		/**
		 * Add reCaptcha field just before the Post button
		 *
		 * @param array $default Default parameter array for reCaptcha.
		 * @since 3.0.0
		 * @return array $default all the fields.
		 */
		public function comment_loginpress_recaptcha_field( $default ) {
			$this->loginpress_recaptcha_field();

			return $default;
		}

		/**
		 * LoginPress_pro_settings_array Setting Fields for reCAPTCHA.]
		 *
		 * @param array $setting_array [ settings fields of free version ].
		 * @return array $_new_settings [ recaptcha settings fields ].
		 *
		 * @version 3.0.0
		 */
		public function loginpress_pro_settings_array( $setting_array ) {

			$recaptcha_options = array(
				'login_form'        => __( 'Login Form', 'loginpress-pro' ),
				'lostpassword_form' => __( 'Lost Password Form', 'loginpress-pro' ),
				'register_form'     => __( 'Register Form', 'loginpress-pro' ),
			);

			// Introduce in 3.0.
			if ( class_exists( 'woocommerce' ) ) {

				$woo_recaptcha_options = array(
					'woocommerce_login_form'    => __( 'WooCommerce Login Form', 'loginpress-pro' ),
					'woocommerce_register_form' => __( 'WooCommerce Register Form', 'loginpress-pro' ),
				);

				$recaptcha_options = array_merge( $recaptcha_options, $woo_recaptcha_options );
			}

			// Introduce in 3.0.
			if ( get_default_comment_status() ) {

				$comments_options = array(
					'comment_form_defaults' => __( 'Comments Section', 'loginpress-pro' ),
				);

				$recaptcha_options = array_merge( $recaptcha_options, $comments_options );
			}
			$_new_settings = array(
				array(
					'name'  => 'force_login',
					'label' => __( 'Force Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable to force prompt user login for exclusive access.', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'enable_user_verification',
					'label' => __( 'New user verification', 'loginpress-pro' ),
					'desc'  => __( 'Allows admin to verify user\'s registration request on the site.', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'enable_repatcha',
					'label' => __( 'Google reCAPTCHA', 'loginpress-pro' ),
					'desc'  => __( 'Enable to add Google reCAPTCHA to your forms.', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'    => 'recaptcha_type',
					'label'   => __( 'reCAPTCHA Type', 'loginpress-pro' ),
					'desc'    => __( 'Select the type of reCAPTCHA', 'loginpress-pro' ),
					'type'    => 'select',
					'default' => 'v2-robot',
					'options' => array(
						'v2-robot'     => __( 'V2 I\'m not robot.', 'loginpress-pro' ),
						'v2-invisible' => __( 'V2 invisible', 'loginpress-pro' ),
						'v3'           => __( 'V3', 'loginpress-pro' ),
					),
				),
				array(
					'name'              => 'site_key',
					'label'             => __( 'Site Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Site Key.<br> <span class="alert-note">Make sure you  are adding right site key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'secret_key',
					'label'             => __( 'Secret Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Secret Key. <br> <span class="alert-note">Make sure you  are adding right secret key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'site_key_v2_invisible',
					'label'             => __( 'Site Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Site Key.<br> <span class="alert-note">Make sure you  are adding right site key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'secret_key_v2_invisible',
					'label'             => __( 'Secret Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Secret Key. <br> <span class="alert-note">Make sure you  are adding right secret key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'site_key_v3',
					'label'             => __( 'Site Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Site Key.<br> <span class="alert-note">Make sure you  are adding right site key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'secret_key_v3',
					'label'             => __( 'Secret Key', 'loginpress-pro' ),
					'desc'              => __( 'Get <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCAPTCHA</a> Secret Key. <br> <span class="alert-note">Make sure you  are adding right secret key for this domain.</span>', 'loginpress-pro' ),
					'type'              => 'text',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'    => 'good_score',
					'label'   => __( 'Select reCaptcha score', 'loginpress-pro' ),
					'desc'    => __( 'Set minimum level of score to be achieved by a human user.', 'loginpress-pro' ),
					'type'    => 'select',
					'default' => '0.5',
					'options' => array(
						'0.1' => '0.1',
						'0.2' => '0.2',
						'0.3' => '0.3',
						'0.4' => '0.4',
						'0.5' => '0.5',
						'0.6' => '0.6',
						'0.7' => '0.7',
						'0.8' => '0.8',
						'0.9' => '0.9',
						'1.0' => '1.0',
					),
				),
				array(
					'name'    => 'captcha_theme',
					'label'   => __( 'Choose Theme', 'loginpress-pro' ),
					'desc'    => __( 'Select a theme for reCAPTCHA', 'loginpress-pro' ),
					'type'    => 'select',
					'default' => 'light',
					'options' => array(
						'light' => 'Light',
						'dark'  => 'Dark',
					),
				),
				array(
					'name'    => 'captcha_language',
					'label'   => __( 'Choose Language', 'loginpress-pro' ),
					'desc'    => __( 'Select a language for reCAPTCHA', 'loginpress-pro' ),
					'type'    => 'select',
					'default' => 'en',
					'options' => array(
						'ar'     => 'Arabic',
						'af'     => 'Afrikaans',
						'am'     => 'Amharic',
						'hy'     => 'Armenian',
						'az'     => 'Azerbaijani',
						'eu'     => 'Basque',
						'bn'     => 'Bengali',
						'bg'     => 'Bulgarian',
						'ca'     => 'Catalan',
						'zh-HK'  => 'Chinese (HongKong)',
						'zh-CN'  => 'Chinese (Simplified)',
						'zh-TW'  => 'Chinese (Traditional)',
						'hr'     => 'Croatian',
						'cs'     => 'Czech',
						'da'     => 'Danish',
						'nl'     => 'Dutch',
						'en-GB'  => 'English (UK)',
						'en'     => 'English (US)',
						'fil'    => 'Filipino',
						'fi'     => 'Finnish',
						'fr'     => 'French',
						'fr-CA'  => 'French (Canadian)',
						'gl'     => 'Galician',
						'ka'     => 'Georgian',
						'de'     => 'German',
						'de-AT'  => 'German (Austria)',
						'de-CH'  => 'German (Switzerland)',
						'el'     => 'Greek',
						'gu'     => 'Gujarati',
						'iw'     => 'Hebrew',
						'hi'     => 'Hindi',
						'hu'     => 'Hungarain',
						'is'     => 'Icelandic',
						'id'     => 'Indonesian',
						'it'     => 'Italian',
						'ja'     => 'Japanese',
						'kn'     => 'Kannada',
						'ko'     => 'Korean',
						'lo'     => 'Laothian',
						'lv'     => 'Latvian',
						'lt'     => 'Lithuanian',
						'ms'     => 'Malay',
						'ml'     => 'Malayalam',
						'mr'     => 'Marathi',
						'mn'     => 'Mongolian',
						'no'     => 'Norwegian',
						'fa'     => 'Persian',
						'pl'     => 'Polish',
						'pt'     => 'Portuguese',
						'pt-BR'  => 'Portuguese (Brazil)',
						'pt-PT'  => 'Portuguese (Portugal)',
						'ro'     => 'Romanian',
						'ru'     => 'Russian',
						'sr'     => 'Serbian',
						'si'     => 'Sinhalese',
						'sk'     => 'Slovak',
						'sl'     => 'Slovenian',
						'es'     => 'Spanish',
						'es-419' => 'Spanish (Latin America)',
						'sw'     => 'Swahili',
						'sv'     => 'Swedish',
						'ta'     => 'Tamil',
						'te'     => 'Telugu',
						'th'     => 'Thai',
						'tr'     => 'Turkish',
						'ur'     => 'Urdu',
						'uk'     => 'Ukrainian',
						'ur'     => 'Urdu',
						'vi'     => 'Vietnamese',
						'zu'     => 'Zulu',
					),
				),
				array(
					'name'    => 'captcha_enable',
					'label'   => __( 'Enable reCAPTCHA on', 'loginpress-pro' ),
					'desc'    => __( 'Choose the form on which you need to apply Google reCAPTCHA.', 'loginpress-pro' ),
					'type'    => 'multicheck',
					'default' => array( 'login_form' => 'login_form' ),
					'options' => $recaptcha_options,
				),
			);

			return( array_merge( $_new_settings, $setting_array ) );
		}

		/**
		 * Too add the Google reCaptcha script and hidden input field in the forms.
		 *
		 * @param string $action Action of the form.
		 * @param string $element_id ID property of the form.
		 * @param string $element_class Class property of the form.
		 *
		 * @since 3.0.3
		 */
		public function loginpress_pro_recaptcha_enqueue( $action, $element_id = '', $element_class = '' ) {
			$cap_site_v3 = isset( $this->loginpress_settings['site_key_v3'] ) ? $this->loginpress_settings['site_key_v3'] : '';

			wp_enqueue_script( 'loginpress_recaptcha_v3', 'https://www.google.com/recaptcha/api.js?render=' . $cap_site_v3, array(), LOGINPRESS_PRO_VERSION, true );
			?>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					grecaptcha.ready(function() {
						grecaptcha.execute('<?php echo esc_attr( $cap_site_v3 ); ?>', { action: '<?php echo esc_attr( $action ); ?>' }).then(function(token) {
						var selector = '';
						if ( '<?php echo esc_attr( $element_id ); ?>' ) {
							selector += '#' + '<?php echo esc_attr( $element_id ); ?>';
						} else if ( '<?php echo esc_attr( $element_class ); ?>' ) {
							selector += '.' + '<?php echo esc_attr( $element_class ); ?>';
						}
						jQuery(selector).prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
						});
					});
				});
			</script>
			<?php
		}

		/**
		 * [loginpress_recaptcha_script recaptcha style]
		 *
		 * @since 1.0.1
		 * @version 2.5.0
		 */
		public function loginpress_recaptcha_script() {

			$cap_type   = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';
			$cap_site   = isset( $this->loginpress_settings['site_key'] ) ? $this->loginpress_settings['site_key'] : '';
			$cap_secret = isset( $this->loginpress_settings['secret_key'] ) ? $this->loginpress_settings['secret_key'] : '';

			$cap_site_v2   = isset( $this->loginpress_settings['site_key_v2_invisible'] ) ? $this->loginpress_settings['site_key_v2_invisible'] : '';
			$cap_secret_v2 = isset( $this->loginpress_settings['secret_key_v2_invisible'] ) ? $this->loginpress_settings['secret_key_v2_invisible'] : '';

			$cap_site_v3   = isset( $this->loginpress_settings['site_key_v3'] ) ? $this->loginpress_settings['site_key_v3'] : '';
			$cap_secret_v3 = isset( $this->loginpress_settings['secret_key_v3'] ) ? $this->loginpress_settings['secret_key_v3'] : '';

			/**
			 * Enqueue Google reCaptcha V2 "I'm not robot" script.
			 *
			 * @since 1.0.1
			 */
			if ( 'v2-robot' === $cap_type ) {

				if ( ! empty( $cap_site ) && ! empty( $cap_secret ) ) :
					$cap_language    = isset( $this->loginpress_settings['captcha_language'] ) ? $this->loginpress_settings['captcha_language'] : 'en';
					$recaptcha_size  = get_option( 'loginpress_customization' );
					$_recaptcha_size = ! empty( $recaptcha_size['recaptcha_size'] ) ? $recaptcha_size['recaptcha_size'] : 1;
					wp_enqueue_script( 'loginpress_recaptcha_lang', 'https://www.google.com/recaptcha/api.js?onload=recaptchaLoaded&hl=' . $cap_language, array(), LOGINPRESS_PRO_VERSION, true );
					?>

					<style type="text/css">
						.loginpress_recaptcha_wrapper{
							text-align: center;
						}
						body .loginpress_recaptcha_wrapper .g-recaptcha{
							display: inline-block;
							transform-origin: top left;
							transform: scale(<?php echo esc_attr( $_recaptcha_size ); ?>);
						}
						html[dir="rtl"] .g-recaptcha{
							transform-origin: top right;
						}
					</style>
					<?php
				endif;
			}

			/**
			 * Enqueue Google reCaptcha V2 invisible script.
			 *
			 * @since 2.5.0
			 */
			if ( 'v2-invisible' === $cap_type ) {

				if ( ! empty( $cap_site_v2 ) && ! empty( $cap_secret_v2 ) ) :
					wp_enqueue_script( 'loginpress_recaptcha_v2', 'https://www.google.com/recaptcha/api.js?onload=onloadV2Callback&render=explicit', array(), LOGINPRESS_PRO_VERSION, true );
					?>

					<script type="text/javascript">
						var onSubmit = function(token) {
							var loginForm         = document.getElementById("loginform");
							var lostPasswordForm  = document.getElementById("lostpasswordform");
							var resetPasswordForm = document.getElementById("resetpassform");

							if (loginForm) {
								loginForm.submit();
							} else if (lostPasswordForm) {
								lostPasswordForm.submit();
							} else if (resetPasswordForm) {
								if (resetPasswordForm.id === "resetpassform") {
									var pass1Value = document.getElementById("pass1").value;
									document.getElementById("pass2").value = pass1Value;
								}
								resetPasswordForm.submit();
							}
						};

						var onloadV2Callback = function() {
							grecaptcha.render('wp-submit', {
								'sitekey': '<?php echo esc_attr( $cap_site_v2 ); ?>',
								'callback': onSubmit
							});
						};
					</script>
					<?php
				endif;// check $cap_site_v2 && $cap_secret_v2.
			}

			/**
			 * Enqueue Google reCaptcha V3 script.
			 *
			 * @since 2.5.0
			 */
			if ( 'v3' === $cap_type ) {

				if ( ! empty( $cap_site_v3 ) && ! empty( $cap_secret_v3 ) && isset( $_GET['action'] ) && $_GET['action'] === 'lostpassword' ) :
					$this->loginpress_pro_recaptcha_enqueue( 'lostpassword', 'lostpasswordform' );
				endif;// check $cap_site_v3 && $cap_secret_v3.

				if ( ! empty( $cap_site_v3 ) && ! empty( $cap_secret_v3 ) && isset( $_GET['action'] ) && $_GET['action'] === 'register' ) :
					$this->loginpress_pro_recaptcha_enqueue( 'register', 'registerform' );
				endif;

				if ( ! empty( $cap_site_v3 ) && ! empty( $cap_secret_v3 ) ) :
					$this->loginpress_pro_recaptcha_enqueue( 'loginpage', 'loginform' );
					$this->loginpress_pro_recaptcha_enqueue( 'loginpage', '', 'woocommerce-form-login' );
				endif;

			}
		}

		/**
		 * Google reCaptcha field Callback
		 *
		 * @version 2.1.2
		 */
		public function loginpress_recaptcha_field() {

			global $recaptcha;

			$cap_site   = isset( $this->loginpress_settings['site_key'] ) ? $this->loginpress_settings['site_key'] : '';
			$cap_secret = isset( $this->loginpress_settings['secret_key'] ) ? $this->loginpress_settings['secret_key'] : '';
			$cap_type   = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';

			if ( 'v2-robot' === $cap_type ) {

				$cap_theme       = isset( $this->loginpress_settings['captcha_theme'] ) ? $this->loginpress_settings['captcha_theme'] : 'light';
				$captcha_preview = '';

				if ( ! empty( $cap_site ) && ! empty( $cap_secret ) ) {

					$captcha_preview .= '<div class="loginpress_recaptcha_wrapper">';
					$captcha_preview .= '<div class="g-recaptcha" data-sitekey="' . htmlentities( trim( $cap_site ) ) . '" data-theme="' . $cap_theme . '"></div>';
					$captcha_preview .= '</div>';
				} // check $cap_site && $cap_secret.

				echo wp_kses_post( $captcha_preview );
			}
		}

		/**
		 * ReCAPTCHA Login Authentication.
		 *
		 * @param object $user The user object.
		 * @param string $username The username.
		 * @param string $password The password.
		 * @return object $user The user object.
		 *
		 * @version 3.0.3
		 */
		public function loginpress_recaptcha_auth( $user, $username, $password ) {

			$cap_type       = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';
			$cap_permission = isset( $this->loginpress_settings['enable_repatcha'] ) ? $this->loginpress_settings['enable_repatcha'] : 'off';

			if ( $cap_permission || (  isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) ) { // @codingStandardsIgnoreLine.

				if ( 'v3' === $cap_type ) {

					$good_score = $this->loginpress_settings['good_score'];
					$score      = $this->loginpress_v3_recaptcha_verifier();

					if ( $username && $password && $score < $good_score ) {
						return new WP_Error( 'recaptcha_error', $this->loginpress_recaptcha_error() );
					}
				} else {
					$response = $this->loginpress_recaptcha_verifier();
					if ( $response->isSuccess() ) {
						return $user;
					}
					if ( $username && $password && ! $response->isSuccess() ) {
						return new WP_Error( 'recaptcha_error', $this->loginpress_recaptcha_error() );
					}
				}
			}
			return $user;
		}

		/**
		 * Google reCaptcha on comments section authentication.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_recaptcha_comment() {

			$cap_type = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';

			if ( isset( $_POST['g-recaptcha-response'] ) ) {  // @codingStandardsIgnoreLine.
				$error = __( '<strong>ERROR:</strong> Please verify reCAPTCHA', 'loginpress-pro' );
				if ( 'v3' === $cap_type ) {
					$good_score = $this->loginpress_settings['good_score'];
					$score      = $this->loginpress_v3_recaptcha_verifier();
					if ( $score < $good_score ) {
						wp_die( $error );  // @codingStandardsIgnoreLine.
					}
				} else {
					$response = $this->loginpress_recaptcha_verifier();
					if ( ! $response->isSuccess() ) {
						wp_die( $error );  // @codingStandardsIgnoreLine.
					}
				}
			}
		}

		/**
		 * Google reCaptcha V2 server side verification.
		 *
		 * @since 2.1.2
		 * @version 2.5.0
		 */
		public function loginpress_recaptcha_verifier() {

			$cap_type = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';

			if ( 'v2-invisible' === $cap_type ) {
				$secret = isset( $this->loginpress_settings['secret_key_v2_invisible'] ) ? $this->loginpress_settings['secret_key_v2_invisible'] : false;
			} else {
				$secret = isset( $this->loginpress_settings['secret_key'] ) ? $this->loginpress_settings['secret_key'] : false;
			}

			include LOGINPRESS_PRO_ROOT_PATH . '/lib/recaptcha/src/autoload.php';

			if ( ini_get( 'allow_url_fopen' ) ) {
				$recaptcha = new \ReCaptcha\ReCaptcha( $secret );
			} else {
				$recaptcha = new \ReCaptcha\ReCaptcha( $secret, new \ReCaptcha\RequestMethod\CurlPost() );
			}
			$recaptcha_response = isset( $_POST['g-recaptcha-response'] ) ? wp_unslash( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : ''; // @codingStandardsIgnoreLine.
			$response = $recaptcha->verify( wp_unslash( $recaptcha_response ), $this->loginpress_get_remote_ip() ); // @codingStandardsIgnoreLine.

			return $response;
		}

		/**
		 * Google reCaptcha V3 server side verification.
		 *
		 * @since 2.1.2
		 * @version 2.5.0
		 */
		public function loginpress_v3_recaptcha_verifier() {

			if ( isset( $_POST['g-recaptcha-response'] ) ) { // @codingStandardsIgnoreLine.

				$v3_secret = isset( $this->loginpress_settings['secret_key_v3'] ) ? $this->loginpress_settings['secret_key_v3'] : false;

				// Build POST request:.
				$recaptcha_url      = 'https://www.google.com/recaptcha/api/siteverify';
				$recaptcha_response = $_POST['g-recaptcha-response']; // @codingStandardsIgnoreLine.

				// Make and decode POST request:.
				$recaptcha = file_get_contents( $recaptcha_url . '?secret=' . $v3_secret . '&response=' . $recaptcha_response );
				$response  = json_decode( $recaptcha );

				// Take action based on the score returned:.
				if ( isset( $response->score ) && $response->score ) {
					return $response->score;
				}
			}
			// otherwise, let the spammer think that they got their message through.
			return 0;
		}

		/**
		 * [loginpress_recaptcha_lostpassword_auth reCAPTCHA Lost Password Authentication.]
		 *
		 * @param bool $allow To allow user access.
		 * @return int $user_id User ID.
		 *
		 * @version 3.0.3
		 */
		public function loginpress_recaptcha_lostpassword_auth( $allow, $user_id ) {

			$cap_type = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';

			if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) { // @codingStandardsIgnoreLine.

				if ( 'v3' === $cap_type ) {
					$good_score = $this->loginpress_settings['good_score'];
					$score      = $this->loginpress_v3_recaptcha_verifier();

					if ( $score > $good_score ) {
						return $allow;
					}
				} else {
					$response = $this->loginpress_recaptcha_verifier();

					if ( $response->isSuccess() ) {
						return $allow;
					}
				}
			}
			return new WP_Error( 'recaptcha_error', $this->loginpress_recaptcha_error() );
		}

		/**
		 * [loginpress_recaptcha_registration_auth reCAPTCHA Registration Authentication.]
		 *
		 * @param array  $errors The Error/s.
		 * @param string $sanitized_user_login The sanitized user login.
		 * @param string $user_email The user email.
		 * @return array $errors The Error/s.
		 *
		 * @version 2.1.2
		 */
		public function loginpress_recaptcha_registration_auth( $errors, $sanitized_user_login, $user_email ) {

			$cap_type = isset( $this->loginpress_settings['recaptcha_type'] ) ? $this->loginpress_settings['recaptcha_type'] : 'v2-robot';

			if ( isset( $_POST['g-recaptcha-response'] ) ) { // @codingStandardsIgnoreLine.

				if ( 'v3' === $cap_type ) {

					$good_score = $this->loginpress_settings['good_score'];
					$score      = $this->loginpress_v3_recaptcha_verifier();

					if ( $score < $good_score ) {
						return new WP_Error( 'recaptcha_error', $this->loginpress_recaptcha_error() );
					}
				} else {

					$response = $this->loginpress_recaptcha_verifier();

					if ( ! $response->isSuccess() ) {
						return new WP_Error( 'recaptcha_error', $this->loginpress_recaptcha_error() );
					}
				}
			}

			return $errors;
		}

		/**
		 * [loginpress_get_remote_ip]
		 *
		 * @return [string] [remote address]
		 */
		public function loginpress_get_remote_ip() {

			return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		}

		/**
		 * [loginpress_recaptcha_error recaptcha error message]
		 *
		 * @return [string] [Custom error message]
		 * @version 2.1.2
		 */
		public function loginpress_recaptcha_error() {

			$loginpress_settings = get_option( 'loginpress_customization' );
			$recaptcha_message   = isset( $loginpress_settings['recaptcha_error_message'] ) ? $loginpress_settings['recaptcha_error_message'] : __( '<strong>ERROR:</strong> Please verify reCAPTCHA', 'loginpress-pro' );

			$allowed_html = array(
				'a'      => array(),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'i'      => array(),
			);
			return wp_kses( $recaptcha_message, $allowed_html );
		}
	}
}
?>
