<?php
if ( ! class_exists( 'AM_Deactivation_Survey' ) ) {
	/**
	 * Awesome Motive Deactivation Survey.
	 *
	 * This prompts the user for more details when they deactivate the plugin.
	 *
	 * @version    1.2.1
	 * @package    AwesomeMotive
	 * @author     Jared Atchison and Chris Christoff
	 * @license    GPL-2.0+
	 * @copyright  Copyright (c) 2018
	 */
	class AM_Deactivation_Survey {

		/**
		 * The API URL we are calling.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $api_url = 'https://api.awesomemotive.com/v1/deactivation-survey/';

		/**
		 * Name for this plugin.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $name;

		/**
		 * Unique slug for this plugin.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $plugin;

		/**
		 * Primary class constructor.
		 *
		 * @param string $name Plugin name.
		 * @param string $plugin Plugin slug.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $name = '', $plugin = '' ) {

			$this->name   = $name;
			$this->plugin = $plugin;

			// Don't run deactivation survey on dev sites.
			if ( $this->is_dev_url() ) {
				return;
			}

			add_action( 'admin_print_scripts', array( $this, 'js' ), 99 );
			add_action( 'admin_print_scripts', array( $this, 'css' ) );
			add_action( 'admin_footer', array( $this, 'modal' ) );
		}

		/**
		 * Checks if current site is a development one.
		 *
		 * @return bool
		 * @since 1.2.0
		 */
		public function is_dev_url() {
			// If it is an AM dev site, return false, so we can see them on our dev sites.
			if ( defined( 'AWESOMEMOTIVE_DEV_MODE' ) && AWESOMEMOTIVE_DEV_MODE ) {
				return false;
			}

			$url          = network_site_url( '/' );

			if ( ! function_exists( 'monsterinsights_is_dev_url' ) ) {
				return false;
			}

			$is_local_url = monsterinsights_is_dev_url( $url );

			return $is_local_url;
		}

		/**
		 * Checks if current admin screen is the plugins page.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_plugin_page() {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
			if ( empty( $screen ) ) {
				return false;
			}

			return ( ! empty( $screen->id ) && in_array( $screen->id, array( 'plugins', 'plugins-network' ), true ) );
		}

		/**
		 * Survey javascript.
		 *
		 * @since 1.0.0
		 */
		public function js() {

			if ( ! $this->is_plugin_page() ) {
				return;
			}
			?>
			<script type="text/javascript">
				jQuery(function ($) {
					var $deactivateLink = $('#the-list').find('[data-slug="<?php echo $this->plugin; ?>"] span.deactivate a'),
						$overlay = $('#am-deactivate-survey-<?php echo $this->plugin; ?>'),
						$form = $overlay.find('form'),
						formOpen = false;
					// Plugin listing table deactivate link.
					$deactivateLink.on('click', function (event) {
						event.preventDefault();
						$overlay.css('display', 'table');
						formOpen = true;
						$form.find('.am-deactivate-survey-option:first-of-type input[type=radio]').focus();
					});
					// Survey radio option selected.
					$form.on('change', 'input[type=radio]', function (event) {
						event.preventDefault();
						$form.find('input[type=text], .error').hide();
						$form.find('.am-deactivate-survey-option').removeClass('selected');
						$(this).closest('.am-deactivate-survey-option').addClass('selected').find('input[type=text]').show();
					});
					// Survey Skip & Deactivate.
					$form.on('click', '.am-deactivate-survey-deactivate', function (event) {
						event.preventDefault();
						location.href = $deactivateLink.attr('href');
					});
					// Survey submit.
					$form.submit(function (event) {
						event.preventDefault();
						if (!$form.find('input[type=radio]:checked').val()) {
							$form.find('.am-deactivate-survey-footer').prepend('<span class="error"><?php echo esc_js( __( 'Please select an option', 'ga-premium' ) ); ?></span>');
							return;
						}
						var data = {
							code: $form.find('.selected input[type=radio]').val(),
							reason: $form.find('.selected .am-deactivate-survey-option-reason').text(),
							details: $form.find('.selected input[type=text]').val(),
							site: '<?php echo esc_url( home_url() ); ?>',
							plugin: '<?php echo sanitize_key( $this->name ); ?>'
						}
						var submitSurvey = $.post('<?php echo $this->api_url; ?>', data);
						submitSurvey.always(function () {
							location.href = $deactivateLink.attr('href');
						});
					});
					// Exit key closes survey when open.
					$(document).keyup(function (event) {
						if (27 === event.keyCode && formOpen) {
							$overlay.hide();
							formOpen = false;
							$deactivateLink.focus();
						}
					});
				});
			</script>
			<?php
		}

		/**
		 * Survey CSS.
		 *
		 * @since 1.0.0
		 */
		public function css() {

			if ( ! $this->is_plugin_page() ) {
				return;
			}
			?>
			<style type="text/css">
				.am-deactivate-survey-modal {
					display: none;
					table-layout: fixed;
					position: fixed;
					z-index: 9999;
					width: 100%;
					height: 100%;
					text-align: center;
					font-size: 14px;
					top: 0;
					left: 0;
					background: rgba(0, 0, 0, 0.8);
				}

				.am-deactivate-survey-wrap {
					display: table-cell;
					vertical-align: middle;
				}

				.am-deactivate-survey {
					background-color: #fff;
					max-width: 550px;
					margin: 0 auto;
					padding: 30px;
					text-align: left;
				}

				.am-deactivate-survey .error {
					display: block;
					color: red;
					margin: 0 0 10px 0;
				}

				.am-deactivate-survey-title {
					display: block;
					font-size: 18px;
					font-weight: 700;
					text-transform: uppercase;
					border-bottom: 1px solid #ddd;
					padding: 0 0 18px 0;
					margin: 0 0 18px 0;
				}

				.am-deactivate-survey-title span {
					color: #999;
					margin-right: 10px;
				}

				.am-deactivate-survey-desc {
					display: block;
					font-weight: 600;
					margin: 0 0 18px 0;
				}

				.am-deactivate-survey-option {
					margin: 0 0 10px 0;
				}

				.am-deactivate-survey-option-input {
					margin-right: 10px !important;
				}

				.am-deactivate-survey-option-details {
					display: none;
					width: 90%;
					margin: 10px 0 0 30px;
				}

				.am-deactivate-survey-footer {
					margin-top: 18px;
				}

				.am-deactivate-survey-deactivate {
					float: right;
					font-size: 13px;
					color: #ccc;
					text-decoration: none;
					padding-top: 7px;
				}
			</style>
			<?php
		}

		/**
		 * Survey modal.
		 *
		 * @since 1.0.0
		 */
		public function modal() {

			if ( ! $this->is_plugin_page() ) {
				return;
			}

			$options = array(
				1 => array(
					'title' => esc_html__( 'I no longer need the plugin', 'ga-premium' ),
				),
				2 => array(
					'title'   => esc_html__( 'I\'m switching to a different plugin', 'ga-premium' ),
					'details' => esc_html__( 'Please share which plugin', 'ga-premium' ),
				),
				3 => array(
					'title' => esc_html__( 'I couldn\'t get the plugin to work', 'ga-premium' ),
				),
				4 => array(
					'title' => esc_html__( 'It\'s a temporary deactivation', 'ga-premium' ),
				),
				5 => array(
					'title'   => esc_html__( 'Other', 'ga-premium' ),
					'details' => esc_html__( 'Please share the reason', 'ga-premium' ),
				),
			);
			?>
			<div class="am-deactivate-survey-modal" id="am-deactivate-survey-<?php echo $this->plugin; ?>">
				<div class="am-deactivate-survey-wrap">
					<form class="am-deactivate-survey" method="post">
						<span class="am-deactivate-survey-title"><span
								class="dashicons dashicons-testimonial"></span><?php echo ' ' . esc_html__( 'Quick Feedback', 'ga-premium' ); ?></span>
						<span class="am-deactivate-survey-desc">
							<?php
							// Translators: Placeholder for the plugin name.
							echo sprintf( esc_html__( 'If you have a moment, please share why you are deactivating %s:', 'ga-premium' ), $this->name );
							?>
						</span>
						<div class="am-deactivate-survey-options">
							<?php foreach ( $options as $id => $option ) : ?>
								<div class="am-deactivate-survey-option">
									<label
										for="am-deactivate-survey-option-<?php echo $this->plugin; ?>-<?php echo $id; ?>"
										class="am-deactivate-survey-option-label">
										<input
											id="am-deactivate-survey-option-<?php echo $this->plugin; ?>-<?php echo $id; ?>"
											class="am-deactivate-survey-option-input" type="radio" name="code"
											value="<?php echo $id; ?>"/>
										<span
											class="am-deactivate-survey-option-reason"><?php echo $option['title']; ?></span>
									</label>
									<?php if ( ! empty( $option['details'] ) ) : ?>
										<input class="am-deactivate-survey-option-details" type="text"
											   placeholder="<?php echo $option['details']; ?>"/>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="am-deactivate-survey-footer">
							<button type="submit"
									class="am-deactivate-survey-submit button button-primary button-large">
								<?php
								// Translators: Adds an ampersand.
								echo sprintf( esc_html__( 'Submit %s Deactivate', 'ga-premium' ), '&amp;' );
								?>
							</button>
							<a href="#" class="am-deactivate-survey-deactivate">
								<?php
								// Translators: Adds an ampersand.
								echo sprintf( esc_html__( 'Skip %s Deactivate', 'ga-premium' ), '&amp;' );
								?>
							</a>
						</div>
					</form>
				</div>
			</div>
			<?php
		}
	}
} // End if().
