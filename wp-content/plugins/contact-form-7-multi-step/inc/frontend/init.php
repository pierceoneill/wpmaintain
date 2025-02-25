<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add js, css
add_action( 'wp_enqueue_scripts', 'cf7mls_frontend_scripts_callback' );
function cf7mls_frontend_scripts_callback() {
	$cf7d_messages_error = '';
	$cf7mls_jquery       = apply_filters( 'cf7mls_register_jquery_filter', array( 'jquery' ) );
	wp_register_script( 'cf7mls', CF7MLS_PLUGIN_URL . 'assets/frontend/js/cf7mls.js', $cf7mls_jquery, CF7MLS_NTA_VERSION, true );
	wp_enqueue_script( 'cf7mls' );

	if ( apply_filters( 'is_using_cf7mls_css', true ) ) {
		wp_register_style( 'cf7mls', CF7MLS_PLUGIN_URL . 'assets/frontend/css/cf7mls.css', array(), CF7MLS_NTA_VERSION );
		wp_enqueue_style( 'cf7mls' );

		wp_register_style( 'cf7mls_progress_bar', CF7MLS_PLUGIN_URL . 'assets/frontend/css/progress_bar.css', array(), CF7MLS_NTA_VERSION );
		wp_enqueue_style( 'cf7mls_progress_bar' );

		wp_register_style( 'cf7mls_animate', CF7MLS_PLUGIN_URL . 'assets/frontend/animate/animate.min.css', array(), CF7MLS_NTA_VERSION );
		wp_enqueue_style( 'cf7mls_animate' );
	}
	wp_localize_script(
		'cf7mls',
		'cf7mls_object',
		array(
			'ajax_url'                 => get_rest_url(),
			'is_rtl'                   => apply_filters( 'cf7mls_is_rtl', is_rtl() ),
			'disable_submit'           => apply_filters( 'cf7mls_disable_submit', 'true' ),
			'cf7mls_error_message'     => $cf7d_messages_error,
			'scroll_step'              => apply_filters( 'cf7mls-scroll-step', 'true' ),
			'disable_enter_key'        => apply_filters( 'cf7mls-disable-enter-key', 'false' ),
			'check_step_before_submit' => apply_filters( 'cf7mls_check_step_before_submit', 'true' ),
		)
	);
}

/**
 * Wpcf7 shortcode.
 */
function cf7mls_add_shortcode_step() {
	wpcf7_add_form_tag( array( 'cf7mls_step', 'cf7mls_step*' ), 'cf7mls_multistep_shortcode_callback', true );
	wpcf7_add_form_tag( 'cf7mls_preview_step', 'cf7mls_multistep_preview_shortcode_callback' );
	wpcf7_add_form_tag( 'cf7_recall', 'cf7_recall_shortcode_callback', true );
}
add_action( 'wpcf7_init', 'cf7mls_add_shortcode_step' );
function cf7mls_multistep_shortcode_callback( $tag ) {
	$tag        = new WPCF7_FormTag( $tag );
	$name       = $tag->name;
	$numberStep = (int) explode( '-', $name )[1];
	$back       = $next = false;

	// Check button back last in step.
	$checkBackLast = false;
	if ( count( $tag->values ) == 2 ) {
		if ( $numberStep === 1 ) {
			$next = $tag->values[0];
		} else {
			$checkBackLast = true;
			$back          = $tag->values[0];
		}
	} elseif ( count( $tag->values ) > 2 ) {
		$back = $tag->values[0];
		$next = $tag->values[1];
	}

	$html = '<div class="cf7mls-btns">';
	// TODO add form id to btn to prevent duplicate
	if ( $checkBackLast === true && $back ) {
		$html  = '';
		$html .= apply_filters( 'cf7_step_before_back_btn', '', $name );
		$html .= '<button type="button" class="cf7mls_back action-button" name="cf7mls_back" id="cf7mls-back-btn-' . $name . '">' . $back . '</button>';
		$html .= apply_filters( 'cf7_step_after_back_btn', '', $name );
	} elseif ( $back ) {
		$html .= apply_filters( 'cf7_step_before_back_btn', '', $name );
		$html .= '<button type="button" class="cf7mls_back action-button" name="cf7mls_back" id="cf7mls-back-btn-' . $name . '">' . $back . '</button>';
		$html .= apply_filters( 'cf7_step_after_back_btn', '', $name );
	}

	// TODO add form id to btn to prevent duplicate
	if ( $next ) {
		$loader = apply_filters( 'cf7mls_loader_img', CF7MLS_PLUGIN_URL . 'assets/frontend/img/loader.svg' );
		$html  .= apply_filters( 'cf7_step_before_next_btn', '', $name );

		$html .= '<button type="button" class="cf7mls_next cf7mls_btn action-button" name="cf7mls_next" id="cf7mls-next-btn-' . $name . '">' . $next . '<img src="' . $loader . '" alt="" data-lazy-src="' . $loader . '" style="display: none;" /></button>';
		$html .= apply_filters( 'cf7_step_after_next_btn', '', $name );
	} else {
		$loader = apply_filters( 'cf7mls_loader_img', CF7MLS_PLUGIN_URL . 'assets/frontend/img/loader.svg' );
		$html  .= apply_filters( 'cf7_step_before_next_btn', '', $name );

		$html .= '<button type="button" style="display: none;" class="cf7mls_next cf7mls_btn action-button" name="cf7mls_next" id="cf7mls-next-btn-' . $name . '">' . $next . '<img src="' . $loader . '" alt="" data-lazy-src="' . $loader . '" style="display: none;" /></button>';
		$html .= apply_filters( 'cf7_step_after_next_btn', '', $name );
	}
	$progress_bar_percent = '';
	$contact_form         = wpcf7_get_current_contact_form();
	if ( $contact_form && get_post_meta( $contact_form->id(), '_cf7_mls_enable_progress_bar_percent', true ) == '1' ) {
		$step_names = get_post_meta( $contact_form->id(), '_cf7mls_step_name', true );
		// $step_names = maybe_unserialize($step_names);
		$bar_precent_color = '';
		$bar_precent_color = trim( get_post_meta( $contact_form->id(), '_cf7mls_progress_bar_percent_color', true ) );
		if ( is_array( $step_names ) ) {
			$progress_bar_percent .= '<div class="cf7mls_progress_bar_percent_wrap" data-number-step="';
			$progress_bar_percent .= count( $step_names );
			$progress_bar_percent .= '">';
			$progress_bar_percent .= '<div class="cf7mls_progress_percent">';
			$progress_bar_percent .= '<div class="cf7mls_progress_bar_percent">';
			$progress_bar_percent .= '<div class="cf7mls_progress_barinner" style="width:';
			$progress_bar_percent .= ( ( 100 / ( count( $step_names ) - 1 ) ) * ( $numberStep - 1 ) ) . '%;';
			$progress_bar_percent .= 'background:' . $bar_precent_color;
			$progress_bar_percent .= '"></div>';
			$progress_bar_percent .= '</div>';
			$progress_bar_percent .= '</div>';
			$progress_bar_percent .= '<div>';
			$progress_bar_percent .= '<p>' . ( intval( ( ( 100 / ( count( $step_names ) - 1 ) ) * ( $numberStep - 1 ) ) ) . '%' ) . '</p>';
			$progress_bar_percent .= '</div>';
			$progress_bar_percent .= '</div>';
			$html                 .= $progress_bar_percent;
		}
	}

	if ( $checkBackLast === false ) {
		$html .= '</div><p></p></fieldset><fieldset class="fieldset-cf7mls">';
	} else {
		if ( '' === $back ) {
			$html .= '</div>';
		}
	}
	return $html;
}

function cf7mls_multistep_preview_shortcode_callback( $tag ) {
	$tag = new WPCF7_FormTag( $tag );

	$class = wpcf7_form_controls_class( $tag->type );

	$atts = array();

	$atts['class']    = $tag->get_class_option( $class );
	$atts['id']       = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

	if ( empty( $value ) ) {
		$value = __( 'Preview', 'cf7mls' );
	}
	$atts['value'] = $value;
	$atts['type']  = 'button';

	$atts = wpcf7_format_atts( $atts );
	$html = sprintf( '<div><input %1$s /></div>', $atts, $value );

	return $html;
}

function cf7_recall_shortcode_callback( $tag ) {
	$tag = new WPCF7_FormTag( $tag );

	$class = wpcf7_form_controls_class( $tag->type );

	$atts = array();

	$question_field = isset( $tag->values[0] ) ? $tag->values[0] : '';

	$atts['class']    = $tag->get_class_option( $class ) . '_' . $question_field;
	$atts['id']       = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

	$atts['data-qf']     = $question_field;
	$atts['data-f_name'] = $tag->name;

	$html = sprintf( '<span %1$s></span>', wpcf7_format_atts( $atts ) );

	$atts['name']  = $tag->name;
	$atts['type']  = 'hidden';
	$atts['class'] = $tag->get_class_option( $class );
	unset( $atts['data-f_name'] );

	$html .= sprintf( '<input %1$s>', wpcf7_format_atts( $atts ) );
	return $html;
}
/**
 * Wrap form
 */
add_filter( 'wpcf7_form_elements', 'cf7mls_wrap_form_elements_func', 10 );
function cf7mls_wrap_form_elements_func( $code ) {
	if ( $contact_form = wpcf7_get_current_contact_form() ) {
		/* If the form has multistep's shortcode */
		if ( strpos( $code, '<fieldset class="fieldset-cf7mls' ) ) {
			if ( defined( 'WPCF7_AUTOP' ) && ( WPCF7_AUTOP == true ) ) {
				$code = preg_replace( '#<p>(.*?)<\/fieldset><fieldset class=\"fieldset-cf7mls\"><\/p>#', '$1</fieldset><fieldset class="fieldset-cf7mls">', $code );
			}
			// progress bar
			$progress_bar = '';
			if ( get_post_meta( $contact_form->id(), '_cf7_mls_enable_progress_bar', true ) == '1' ) {
				$bar_class = array();
				if ( is_rtl() ) {
					$bar_class[] = 'is_rtl';
				}
				$style_bar = get_post_meta( $contact_form->id(), '_cf7mls_progress_bar_style', true );
				if ( empty( $style_bar ) ) {
					$style_bar = 'navigation_horizontal_squaren';
				}

				$style_text = '';
				if ( ! empty( get_post_meta( $contact_form->id(), '_cf7mls_style_text', true ) ) ) {
					$style_text = get_post_meta( $contact_form->id(), '_cf7mls_style_text', true );
				} else {
					$style_text = 'vertical';
				}

				$bar_class[]           = 'cf7mls_bar_style_' . $style_bar . ' cf7mls_bar_style_text_' . $style_text;
				$bar_class             = apply_filters( 'cf7mls-progress-bar-class', $bar_class, $contact_form->id() );
				$progress_bar_bg_color = '';
				if ( ! empty( get_post_meta( $contact_form->id(), '_cf7mls_progress_bar_bg_color', true ) ) ) {
					$progress_bar_bg_color = trim( get_post_meta( $contact_form->id(), '_cf7mls_progress_bar_bg_color', true ) );
				} else {
					$progress_bar_bg_color = '#0073aa';
				}
				$step_names = get_post_meta( $contact_form->id(), '_cf7mls_step_name', true );
				// $step_names = maybe_unserialize($step_names);
				$allow_choose_step = get_post_meta( $contact_form->id(), '_cf7mls_allow_choose_step', true );

				$width_progress_bar  = '';
				$margin_progress_bar = '';
				if ( $style_text == 'no' ) {
					$width_progress_bar = 80;
					if ( $width_progress_bar > 100 ) {
						$width_progress_bar = '100%';
					} else {
						$width_progress_bar = $width_progress_bar . '%';
					}
				}
				if ( is_array( $step_names ) || is_object( $step_names ) ) {
					if ( count( $step_names ) == 2 ) {
						$width_progress_bar  = '60%';
						$margin_progress_bar = '42px auto';
					}
				}

				$progress_bar = sprintf(
					'<ul data-id-form="%8$d" data-bg-color="%2$s" data-bg-style-bar="%4$s" data-style-text="%5$s" data-allow-choose-step="%7$s" class="cf7mls_progress_bar %1$s" style="%3$s; %6$s">',
					implode( ' ', $bar_class ),
					$progress_bar_bg_color,
					( ! empty( $width_progress_bar ) ? 'width:' . $width_progress_bar : '' ),
					$style_bar,
					$style_text,
					( ! empty( $margin_progress_bar ) ? 'margin:' . $margin_progress_bar : '' ),
					$allow_choose_step,
					$contact_form->id()
				);

				$i = 0;
				if ( is_array( $step_names ) ) {
					$width_step_item = 'width: auto';
					if (
						( ( $style_text == 'horizontal' ) ||
						( $style_text == 'no' ) ) &&
						( ( $style_bar == 'horizontal_squaren' ) ||
						( $style_bar == 'horizontal_round' ) ||
						( $style_bar == 'box_vertical_squaren' ) ||
						( $style_bar == 'box_larerSign_squaren' ) )
					) {
						$width_step_item = 'width: ' . ( 100 / count( $step_names ) ) . '%';
					}
					if ( $style_text == 'vertical' ) {
						$width_step_item = 'width: ' . ( 100 / count( $step_names ) ) . '%';
					}

					$c = count( $step_names );
					foreach ( $step_names as $k => $v ) {
						$class = '';
						if ( $i === 0 ) {
							$class = 'active current';
						}

						$format_step   = '';
						$format_step  .= '<li data-counter="%4$d" data-counter_rtl="%5$d" style="%3$s" class="cf7_mls_steps_item %2$s">';
						$format_step  .= '<div class="cf7_mls_steps_item_container">';
						$format_step  .= '<div class="cf7_mls_steps_item_icon">';
						$format_step  .= '<span class="cf7_mls_count_step">%4$d</span>';
						$format_step  .= '<span class="cf7_mls_check">';
						$format_step  .= '<i>';
						$format_step  .= '<svg viewBox="64 64 896 896" data-icon="check" width="14px" height="14px" fill="currentColor" aria-hidden="true" focusable="false" class="">';
						$format_step  .= '<path d="M912 190h-69.9c-9.8 0-19.1 4.5-25.1 12.2L404.7 724.5 207 474a32 32 0 0 0-25.1-12.2H112c-6.7 0-10.4 7.7-6.3 12.9l273.9 347c12.8 16.2 37.4 16.2 50.3 0l488.4-618.9c4.1-5.1.4-12.8-6.3-12.8z"></path>';
						$format_step  .= '</svg>';
						$format_step  .= '</i>';
						$format_step  .= '</span>';
						$format_step  .= '<span class="cf7_mls_invalid">';
						$format_step  .= '<i>';
						$format_step  .= '<svg viewBox="64 64 896 896" data-icon="close" width="14px" height="14px" fill="currentColor" aria-hidden="true" focusable="false" class="">';
						$format_step  .= '<path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 00203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>';
						$format_step  .= '</svg>';
						$format_step  .= '</i>';
						$format_step  .= '</span>';
						$format_step  .= '</div>';
						$format_step  .= '<div class="cf7_mls_steps_item_content">';
						$format_step  .= '<p class="cf7mls_progress_bar_title">%1$s</p>';
						$format_step  .= '<span class="cf7_mls_arrow_point_to_righ">';
						$format_step  .= '<i>';
						$format_step  .= '<svg x="0px" y="0px" width="8px" height="14px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847; xml:space="preserve">';
						$format_step  .= '<g>';
						$format_step  .= '<path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744
                                        L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284
                                        c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>';
						$format_step  .= '</g>';
						$format_step  .= '</svg>';
						$format_step  .= '</i>';
						$format_step  .= '</span>';
						$format_step  .= '</div>';
						$format_step  .= '</div>';
						$format_step  .= '</li>';
						$progress_bar .= sprintf( $format_step, __( $v, 'cf7mls' ), $class, $width_step_item, $i + 1, $c );
						$i++;
						$c--;
					}
				}
				$progress_bar .= '</ul>';

				// Show in ipad, mobie phone
				if ( is_array( $step_names ) ) {
					$number_step   = '';
					$number_step  .= '<div class="cf7mls_number_step_wrap" data-bg-color="%2$s" data-number-step="%3$s">';
					$number_step  .= '<p class="cf7mls_number">%1$s</p>';
					$number_step  .= '<p class="cf7mls_step_current">%4$s</p>';
					$number_step  .= '<div class="cf7mls_progress_percent">';
					$number_step  .= '<div class="cf7mls_progress_bar_percent">';
					$number_step  .= '<div class="cf7mls_progress_barinner"></div>';
					$number_step  .= '</div>';
					$number_step  .= '</div>';
					$number_step  .= '</div>';
					$progress_bar .= sprintf( $number_step, '1/' . count( $step_names ), $progress_bar_bg_color, count( $step_names ), $step_names[0] );
				}
			}

			if ( get_post_meta( $contact_form->id(), '_cf7mls_select_stype_transition', true ) &&
				( get_post_meta( $contact_form->id(), '_cf7_mls_auto_moving_animation', true ) == 'on' )
			) {
				$stype_transition = get_post_meta( $contact_form->id(), '_cf7mls_select_stype_transition', true );
			} else {
				$stype_transition = '';
			}
			$code = $progress_bar . sprintf( '<div class="fieldset-cf7mls-wrapper" data-transition-effects="%1$s"><fieldset class="fieldset-cf7mls">', $stype_transition ) . $code;

			$code .= '</fieldset></div>';
			// $code .= '</fieldset>';
		}
	}
	$ex = explode( '<fieldset class="fieldset-cf7mls">', $code );
	if ( count( $ex ) > 1 ) {
		$code = '';
		foreach ( $ex as $k => $v ) {
			$code .= $v;
			if ( $k == 0 ) {
				$code .= '<fieldset class="fieldset-cf7mls cf7mls_current_fs">';
			} elseif ( $k < ( count( $ex ) - 1 ) ) {
				$code .= '<fieldset class="fieldset-cf7mls">';
			}
		}
	}
	return $code;
}

// add css to wp_head
add_action( 'wp_head', 'cf7mls_css_to_wp_head' );
function cf7mls_css_to_wp_head() {
	$query = new \WP_Query(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);
	$posts = $query->get_posts();

	// global $wpdb;
	// $query     = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'wpcf7_contact_form' and post_status = 'publish'" );
	echo '<style type="text/css">';
	foreach ( $posts as $key => $post ) {
		$id              = $post->ID;
		$next_bg_color   = get_post_meta( $id, '_cf7mls_next_bg_color', true );
		$next_text_color = get_post_meta( $id, '_cf7mls_next_text_color', true );

		$back_bg_color   = get_post_meta( $id, '_cf7mls_back_bg_color', true );
		$back_text_color = get_post_meta( $id, '_cf7mls_back_text_color', true );

		echo 'div[id^="wpcf7-f' . $id . '"] button.cf7mls_next { ' . ( ( ! empty( $next_bg_color ) ) ? 'background-color: ' . $next_bg_color . ';' : '' ) . ' ' . ( ( ! empty( $next_text_color ) ) ? 'color: ' . $next_text_color : '' ) . ' }';
		echo 'div[id^="wpcf7-f' . $id . '"] button.cf7mls_back { ' . ( ( ! empty( $back_bg_color ) ) ? 'background-color: ' . $back_bg_color . ';' : '' ) . ' ' . ( ( ! empty( $back_text_color ) ) ? 'color: ' . $back_text_color : '' ) . ' }';
	}
	echo '</style>';
}
add_filter( 'wpcf7_form_class_attr', 'cf7mls_add_auto_scroll_class' );
function cf7mls_add_auto_scroll_class( $class ) {
	if ( $contact_form = wpcf7_get_current_contact_form() ) {
		if ( empty( trim( ( get_post_meta( $contact_form->id(), '_cf7_mls_auto_scroll_animation', true ) ) ) ) ) {
			$class .= ' cf7mls-no-scroll';
		}
		if ( ! empty( trim( ( get_post_meta( $contact_form->id(), '_cf7_mls_auto_return_first_step', true ) ) ) ) ) {
			$class .= ' cf7mls-auto-return-first-step';
		}
		$class .= ' cf7mls-no-moving-animation';
	}
	return $class;
}
