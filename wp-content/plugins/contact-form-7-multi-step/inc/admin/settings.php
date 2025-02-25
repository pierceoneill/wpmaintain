<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_filter( 'wpcf7_editor_panels', 'cf7mls_admin_settings' );
function cf7mls_admin_settings( $panels ) {
	 $panels['cf7mls-settings-panel'] = array(
		 'title'    => __( 'Multi-Step Settings', 'cf7mls' ),
		 'callback' => 'cf7mls_settings_func',
	 );
	 $panels['cf7mls-progress-bar']   = array(
		 'title'    => __( 'Progress Bar', 'cf7mls' ),
		 'callback' => 'cf7mls_progress_bar_func',
	 );
	 return $panels;
}
function cf7mls_settings_func( $post ) {    ?>
	<div id="cf7mls_multi_step_wrap" class="cf7mls_multi_step_wrap">
		<h2 class="cf7mls-title cf7mls-title-color"><?php echo esc_html( __( 'Color', 'cf7mls' ) ); ?></h2>
				
		<fieldset class="cf7mls-group-color">
					<legend class="cf7mls-color-caption"><?php _e( 'You can change the background-color or text-color of Back, Next buttons here.', 'cf7mls' ); ?></legend>
					
					<div class="cf7mls-group-color-bt-back">
						<p class="cf7mls-title"><?php _e( 'Back Button', 'cf7mls' ); ?></p>

						<div class="cf7mls-wrap-bg-color">
							<p class="cf7mls-label"><?php _e( 'BG color', 'cf7mls' ); ?></p>
							<input type="text" class="cf7mls-color-field" name="back-btn-bg-color" value="<?php echo $post->prop( 'cf7mls_back_bg_color' ); ?>" />
						</div>

						<div class="cf7mls-wrap-text-color">
							<p class="cf7mls-label"><?php _e( 'Text color', 'cf7mls' ); ?></p>
							<input type="text" class="cf7mls-color-field" name="back-btn-text-color" value="<?php echo $post->prop( 'cf7mls_back_text_color' ); ?>" />
						</div>
					</div>
					
					<div class="cf7mls-group-color-bt-next">
						<p class="cf7mls-title"><?php _e( 'Next Button', 'cf7mls' ); ?></p>
						
						<div class="cf7mls-wrap-bg-color">
							<p class="cf7mls-label"><?php _e( 'BG color', 'cf7mls' ); ?></p>
							<input type="text" class="cf7mls-color-field" name="next-btn-bg-color" value="<?php echo $post->prop( 'cf7mls_next_bg_color' ); ?>" />
						</div>

						<div class="cf7mls-wrap-text-color">
							<p class="cf7mls-label"><?php _e( 'Text color', 'cf7mls' ); ?></p>
							<input type="text" class="cf7mls-color-field" name="next-btn-text-color" value="<?php echo $post->prop( 'cf7mls_next_text_color' ); ?>" />
						</div>
					</div>
				</fieldset>
				
				<div class="cf7mls-auto-scroll-wrap">
					<div class="cf7mls-auto-scroll-title-wrap">
						<h2 class="cf7mls-auto-scroll-title"><?php echo esc_html( __( 'Auto Scroll to Top', 'cf7mls' ) ); ?></h2>
						<a tooltip="It will be scrolled to the top of the form after each step." class="cf7mls-tooltip">
								<img src=<?php echo ( '"' . CF7MLS_PLUGIN_URL . 'assets/admin/img/help-circle.svg' . '"' ); ?> alt="help circle">
						</a>
					</div>
					<div class="cf7mls-wrap-switch cf7mls-wrap-switch-scroll">
						<label class="cf7mls_switch">
								<input class="cf7mls_scroll_animation" type="checkbox" name="auto-scroll-animation" <?php echo ( $post->prop( 'cf7_mls_auto_scroll_animation' ) ? 'checked' : '' ); ?> value="on" />
								<span class="cf7mls_slider cf7mls_round"></span>
						</label>
					</div>
				</div>

				<div class="cf7mls-auto-scroll-wrap">
					<div class="cf7mls-auto-scroll-title-wrap">
						<h2 class="cf7mls-auto-scroll-title"><?php echo esc_html( __( 'Return First Step After Submit', 'cf7mls' ) ); ?></h2>
						<a tooltip="It will be clear form and return to first step after submit form success" class="cf7mls-tooltip">
								<img src=<?php echo ( '"' . CF7MLS_PLUGIN_URL . 'assets/admin/img/help-circle.svg' . '"' ); ?> alt="help circle">
						</a>
					</div>
					<div class="cf7mls-wrap-switch cf7mls-wrap-switch-scroll">
						<label class="cf7mls_switch">
								<input class="cf7mls_scroll_animation" type="checkbox" name="auto-return-first-step" <?php echo ( $post->prop( 'cf7_mls_auto_return_first_step' ) ? 'checked' : '' ); ?> value="on" />
								<span class="cf7mls_slider cf7mls_round"></span>
						</label>
					</div>
				</div>
				
				<div class="cf7mls-transition-effects-wrap">
					<h2 class="cf7mls-transition-effects-wrap-title"><?php echo _e( 'Transition Effects', 'cf7mls' ); ?></h2>
					<div class="cf7mls-wrap-switch">
							<label class="cf7mls_switch">
									<input class="cf7mls_toggle_transition_effects" type="checkbox" name="auto-moving-animation" <?php echo ( $post->prop( 'cf7_mls_auto_moving_animation' ) ? 'checked' : '' ); ?> value="on" />
									<span class="cf7mls_slider cf7mls_round"></span>
							</label>
					</div>

					<div class="cf7mls-stype-transition-wrap">
						<h2 class="cf7mls_stype_transition_title"><?php echo _e( 'Animation', 'cf7mls' ); ?></h2>
						<div class="cf7mls_select_stype_transition">
								<select name="cf7mls_select_stype_transition" id="cf7mls_select_stype_transition">
									<?php
										$selected_style_tran = trim( $post->prop( 'cf7mls_select_stype_transition' ) );
										$stype_transitions   = array(
											'in_fadeIn next_fadeInRight back_fadeInLeft' => 'Fade In',
											'in_fadeInUp next_fadeInUp back_fadeInUp' => 'Fade Up',
										);
										foreach ( $stype_transitions as $key_transition => $stype_transition ) {
												echo sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key_transition, selected( trim( $key_transition ), $selected_style_tran, false ), esc_html( __( $stype_transition, 'cf7mls' ) ) );
										}
										?>
								</select>
						</div>
					</div>
				</div>
		</div>

	<?php
	if ( cf7mls_is_active_cf7db() ) {
		?>
		<h2><?php echo esc_html( __( 'Save to database', 'cf7mls' ) ); ?></h2>
		<fieldset>
			<p class="description">
				<label for="cf7mls_db_save_every_step">
					<?php _e( 'Save form\'s every step?', 'cf7mls' ); ?>
					<br />
					<input type="checkbox" name="cf7mls_db_save_every_step" value="yes" id="cf7mls_db_save_every_step" <?php echo checked( $post->prop( 'cf7mls_db_save_every_step' ), 'yes' ); ?> />
				</label>
			</p>
		</fieldset>
		<?php
	}
}
function cf7mls_progress_bar_func( $post ) {
	$cf7mls_step_name        = maybe_unserialize( $post->prop( 'cf7mls_step_name' ) );
	$selected_style          = $post->prop( 'cf7mls_progress_bar_style' );
	$selected_progress_style = '';
	$selected_icon_style     = '';
	if ( $selected_style == '' ) {
		$selected_style = 1;
	} else {
		if ( strpos( $selected_style, 'navigation_horizontal' ) !== false ) {
			$selected_progress_style = 'navigation_horizontal';
		} else {
			if ( strpos( $selected_style, 'largerSign' ) !== false ) {
				$selected_progress_style = 'largerSign';
			}
			if ( strpos( $selected_style, 'horizontal' ) !== false ) {
				$selected_progress_style = 'horizontal';
			}
			if ( strpos( $selected_style, 'box_vertical' ) !== false ) {
				$selected_progress_style = 'box_vertical';
			}
			if ( strpos( $selected_style, 'box_larerSign' ) !== false ) {
				$selected_progress_style = 'box_larerSign';
			}
		}
		if ( strpos( $selected_style, 'squaren' ) !== false ) {
			$selected_icon_style = 'squaren';
		} else {
			$selected_icon_style = 'round';
		}
	}

	if ( ( $cf7mls_step_name == '' ) || ( $cf7mls_step_name == '[]' ) ) {
			$cf7mls_step_name = array();
	}

	$styles = array(
		1 => 'navigation_horizontal_squaren',
		2 => 'largerSign_squaren',
		3 => 'horizontal_squaren',
		4 => 'navigation_horizontal_round',
		5 => 'largerSign_round',
		6 => 'horizontal_round',
		7 => 'box_vertical_squaren',
		8 => 'box_larerSign_squaren',
	);

	$progress_bar_styles = array(
		1 => 'navigation_horizontal',
		2 => 'horizontal',
		3 => 'largerSign',
		4 => 'box_vertical',
		5 => 'box_larerSign',
	);

	$progress_bar_titles = array(
		1 => 'Horizontal',
		2 => 'Vertical',
		3 => 'Arrow',
		4 => 'Box',
		5 => 'Box and Arrow',
	);

	$progress_bar_icon_styles = array(
		1 => 'squaren',
		2 => 'round',
	);

	$progress_bar_icon_titles = array(
		1 => 'Square',
		2 => 'Round',
	);

	$step_bar_stype = array(
		1 => 'Horizontal Navigation, Square Icon',
		2 => 'Larger Size, Square Icon',
		3 => 'Vertical Navigation, Square Icon',
		4 => 'Horizontal Navigation, Round Icon',
		5 => 'Larger Size, Round Icon',
		6 => 'Vertical Navigation, Round Icon',
		7 => 'Box, Square Icon',
		8 => 'Box, Larger Size, Square Icon',
	);
	// print_r($cf7mls_step_name);exit();
	// $cf7mls_step_name = array();
	?>
	<div class="cf7mls_pogress_bar_wrap">
		<div class="cf7mls-group-pogress-bar"> 
			<div class="cf7mls-pogress-bar">
				<h2 class="cf7mls-title-pogress-bar"><?php echo esc_html( __( 'Progress Bar', 'cf7mls' ) ); ?></h2>
				<div class="cf7mls-wrap-switch">
					<label class="cf7mls_switch cf7mls_progress_bars_witch">
						<input class="cf7mls_enable_progress_bar" type="checkbox" id="cf7_mls_enable_progress_bar" name="cf7_mls_enable_progress_bar" <?php checked( $post->prop( 'cf7_mls_enable_progress_bar' ), '1' ); ?> value="1" />
						<span class="cf7mls_slider cf7mls_round"></span>
					</label>
				</div>
			</div>

			<div class="cf7mls_bg_color_wrap cf7mls_bg_color_progress">
				<h2><?php _e( 'Background Color', 'cf7mls' ); ?></h2> 
				<input type="text" class="cf7mls_progress_bar_filter" name="progress-bar-bg-color" value="<?php echo ( $post->prop( 'cf7mls_progress_bar_bg_color' ) ? $post->prop( 'cf7mls_progress_bar_bg_color' ) : '#0073aa' ); ?>" />
			</div>
			
			<div class="cf7mls_progress_style_wrap">
				<h2><?php _e( 'Progress Bar Style', 'cf7mls' ); ?></h2>

				<select name="cf7mls_progress_bar_style" id="cf7mls_progress_bar_style">
					<?php
					foreach ( $progress_bar_styles as $k => $style ) {
						echo sprintf( '<option value="%1$s" %2$s>%3$s</option>', $style, selected( $style, $selected_progress_style, false ), esc_html( __( $progress_bar_titles[ $k ], 'cf7mls' ) ) );
					}
					?>
				</select>
				<h2><?php _e( 'Border Style', 'cf7mls' ); ?></h2>

				<select name="cf7mls_progress_bar_icon_style" id="cf7mls_progress_bar_icon_style" 
				<?php
				if ( $selected_progress_style == 'box_vertical' || $selected_progress_style == 'box_larerSign' ) {
					echo 'disabled="disabled"';}
				?>
				 >
				<?php
				foreach ( $progress_bar_icon_styles as $k => $style ) {
					echo sprintf( '<option value="%1$s" %2$s>%3$s</option>', $style, selected( $style, $selected_icon_style, false ), esc_html( __( $progress_bar_icon_titles[ $k ], 'cf7mls' ) ) );
				}
				?>
				</select>

			</div>

			<div class="title_options_wrap">
				<h2><?php _e( 'Title Options', 'cf7mls' ); ?></h2>
				<div class="cf7mls-select-style-text">
					<?php
						$style_text = '';
					if ( ! empty( $post->prop( 'cf7mls_style_text' ) ) ) {
						$style_text = $post->prop( 'cf7mls_style_text' );
					} else {
						$style_text = 'vertical';
					}

					?>
					<input value="<?php echo $style_text; ?>" name="cf7mls-style-text" type="text" class="cf7mls-style-text hidden" />

					<div data-style-text="horizontal" class="cf7mls-style-text-wrap <?php echo ( ( $style_text == 'horizontal' ) ? 'active' : '' ); ?>">
						<p class="cf7mls-style-text"><?php _e( 'Horizontal text', 'cf7mls' ); ?></p>
					</div>

					<div data-style-text="vertical" class="cf7mls-style-text-wrap <?php echo ( ( $style_text == 'vertical' ) ? 'active' : '' ); ?>">
						<p class="cf7mls-style-text"><?php _e( 'Vertical text', 'cf7mls' ); ?></p>
					</div>

					<div data-style-text="no" class="cf7mls-style-text-wrap <?php echo ( ( $style_text == 'no' ) ? 'active' : '' ); ?>">
						<p class="cf7mls-style-text"><?php _e( 'No text', 'cf7mls' ); ?></p>
					</div>
				</div>
			</div>

			<div class="cf7mls-pogress-bar-percent">
				<h2 class="cf7mls-title-pogress-bar-percent"><?php echo esc_html( __( 'Progress Bar Percent', 'cf7mls' ) ); ?></h2>
				<div class="cf7mls-wrap-switch">
					<label class="cf7mls_switch cf7mls_progress_bars_witch">
						<input class="cf7mls_enable_progress_bar_percent" type="checkbox" id="cf7_mls_enable_progress_bar_percent" name="cf7_mls_enable_progress_bar_percent" <?php checked( $post->prop( 'cf7_mls_enable_progress_bar_percent' ), '1' ); ?> value="1" />
						<span class="cf7mls_slider cf7mls_round"></span>
					</label>
				</div>
				<div class="cf7mls_bg_color_wrap cf7mls_bg_color_progress_percent">
					<h2><?php _e( 'Background Color', 'cf7mls' ); ?></h2> 
					<input type="text" class="cf7mls_progress_bar_percent_filter" name="progress-bar-percent-color" value="<?php echo $post->prop( 'cf7mls_progress_bar_percent_color' ); ?>" />
				</div>
			</div>

			<div class="cf7mls-allow-choose-step-wrap">
				<input id="cf7mls-allow-choose-step" type="checkbox" name="cf7mls-allow-choose-step" <?php checked( $post->prop( 'cf7mls_allow_choose_step' ), 'on' ); ?> value="on" />
				<label for="cf7mls-allow-choose-step" class="cf7mls-allow-choose-step-checkbox" data-checked="<?php echo $post->prop( 'cf7mls_allow_choose_step' ); ?>"></label>
				<span class="cf7mls-allow-choose-step-text">Allow Choose Step
					<a tooltip="User can click on each step to see its content before fill" class="cf7mls-tooltip">
						<img src=<?php echo ( '"' . CF7MLS_PLUGIN_URL . 'assets/admin/img/help-circle.svg' . '"' ); ?> alt="help circle">
					</a>
				</span>
			</div>

		</div>
	</div>
	<div class="cf7mls_preview">
		<div class="cf7mls_browser">   
			<div class="cf7mls_circle_wrap">
				<div class="cf7mls_circle cf7mls_red_circle"></div>
				<div class="cf7mls_circle cf7mls_yellow_circle"></div>
				<div class="cf7mls_circle cf7mls_green_circle"></div>
			</div> 
			
			<div class="cf7mls_block">
				
				<div class="cf7mls_check_step_progress_bar">

					<?php
					$width_progress_bar = '';
					if ( count( $cf7mls_step_name ) > 1 && count( $cf7mls_step_name ) == 2 ) {
						$width_progress_bar = ( 14 * 2 ) . '%';
					} elseif ( count( $cf7mls_step_name ) > 1 && count( $cf7mls_step_name ) >= 3 ) {
						$width_progress_bar = ( 14 * 3 ) . '%';
					}
					?>

					<ul id="cf7mls_progress_bar" data-bg-color="<?php echo ( $post->prop( 'cf7mls_progress_bar_bg_color' ) ? $post->prop( 'cf7mls_progress_bar_bg_color' ) : '#0073aa' ); ?>"
						class="cf7mls_progress_bar cf7mls_bar_style_<?php echo ( ( $selected_style != 1 ) ? $selected_style : 'navigation_horizontal_squaren' ); ?> cf7mls_bar_style_text_<?php echo ( ! empty( $style_text ) ? $style_text : '' ); ?>"
						style="<?php echo( ( ( $style_text == 'no' ) && ( count( $cf7mls_step_name ) > 1 ) ) ? ( 'width:80%' ) : '' ); ?>" 
						data-width-progress-bar="<?php echo( ( ( $style_text == 'no' ) && ( count( $cf7mls_step_name ) > 1 ) ) ? $width_progress_bar : '' ); ?>"
					>

					<?php
					if ( ! ( empty( $post->prop( 'cf7mls_style_text' ) ) ) ) {
						$style_text = $post->prop( 'cf7mls_style_text' );
					} else {
						$style_text = 'vertical';
					}

					$width_step_item = 'auto';
					if (
						( ( $style_text == 'horizontal' ) ||
						( $style_text == 'no' ) ) &&
						( ( $selected_style == 'horizontal_squaren' ) ||
						( $selected_style == 'horizontal_round' ) ||
						( $selected_style == 'box_vertical_squaren' ) ||
						( $selected_style == 'box_larerSign_squaren' ) )
					) {
						if ( count( $cf7mls_step_name ) >= 3 ) {
							$width_step_item = ( 100 / 3 ) . '%';
						} elseif ( count( $cf7mls_step_name ) == 2 ) {
							$width_step_item = ( 100 / 2 ) . '%';
						}
					}

					if ( $style_text == 'vertical' ) {
						$width_step_item = 'auto';
						if ( count( $cf7mls_step_name ) >= 3 ) {
							$width_step_item = ( 100 / 3 ) . '%';
						} else {
							$width_step_item = ( 100 / 2 ) . '%';
						}
					}

					foreach ( $cf7mls_step_name as $k => $v ) {
						if ( $k < 3 && count( $cf7mls_step_name ) > 1 ) {
							$activeClass = '';
							if ( ( $k + 1 ) == 2 ) {
								$activeClass = 'cf7mls_step_name';
							}
							$format_step  = '';
							$format_step .= '<li class="cf7_mls_steps_item ' . $activeClass . '" data-step="%3$d" style="width : %1$s">';
							$format_step .= '<div class="cf7_mls_steps_item_container">';
							$format_step .= '<div class="cf7_mls_steps_item_icon">';
							$format_step .= '<span class="cf7_mls_count_step">%3$d</span>';
							$format_step .= '<span class="cf7_mls_check">';
							$format_step .= '<i>';
							$format_step .= '<svg viewBox="64 64 896 896" data-icon="check" width="14px" height="14px" fill="currentColor" aria-hidden="true" focusable="false" class="">';
							$format_step .= '<path d="M912 190h-69.9c-9.8 0-19.1 4.5-25.1 12.2L404.7 724.5 207 474a32 32 0 0 0-25.1-12.2H112c-6.7 0-10.4 7.7-6.3 12.9l273.9 347c12.8 16.2 37.4 16.2 50.3 0l488.4-618.9c4.1-5.1.4-12.8-6.3-12.8z"></path>';
							$format_step .= '</svg>';
							$format_step .= '</i>';
							$format_step .= '</span>';
							$format_step .= '</div>';
							$format_step .= '<div class="cf7_mls_steps_item_content">';
							$format_step .= '<p class="cf7mls_progress_bar_title">%2$s</p>';
							$format_step .= '<span class="cf7_mls_arrow_point_to_righ">';
							$format_step .= '<i>';
							$format_step .= '<svg x="0px" y="0px" width="8px" height="14px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847; xml:space="preserve">';
							$format_step .= '<g>';
							$format_step .= '<path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744
											L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284
											c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>';
							$format_step .= '</g>';
							$format_step .= '</svg>';
							$format_step .= '</i>';
							$format_step .= '</span>';
							$format_step .= '</div>';
							$format_step .= '</div>';
							$format_step .= '</li>';
							echo sprintf( $format_step, ( ( ! empty( $width_step_item ) ) ? $width_step_item : 'auto' ), esc_html( ( strlen( $v ) > 7 ) ? wp_trim_words( $v, 7 ) . '...' : $v ), $k + 1 );
						} else {
								break;
						}
					}

					?>

					</ul>

					<!-- Show in ipad, mobie phone -->
					<?php
					if ( count( $cf7mls_step_name ) > 1 ) {
						?>
					
						<div class="cf7mls_number_step_wrap">
							<p class="cf7mls_number"><?php echo( ( count( $cf7mls_step_name ) > 3 ) ? '2/3' : '2/' . count( $cf7mls_step_name ) ); ?></p>
							<p class="cf7mls_step_current"></p>
							<div class="cf7mls_progress_percent">
								<div class="cf7mls_progress_bar_percent">
									<div class="cf7mls_progress_barinner" style="<?php echo( ( count( $cf7mls_step_name ) == 2 ) ? 'width: 100%' : 'width: 50%' ); ?>"></div>
								</div>
							</div>
						</div>

						<?php
					}
					?>
				</div>

				<div class="cf7mls_form_demo_one"></div>
				<div class="cf7mls_form_demo_two"></div>
				<div class="cf7mls_form_textarea_demo"></div>
				<div>
					<!-- Progress Bar percent on computer -->
					<!-- <div class="cf7mls_progress_bar_percent_wrap">
						<div class="cf7mls_progress_percent">
							<div class="cf7mls_progress_bar_percent">
								<div class="cf7mls_progress_barinner" style="<?php // echo((count($cf7mls_step_name) == 2)? 'width: 100%' : 'width: 50%') ?>"></div>
							</div>
						</div>
						<div>
							<p><?php // echo((count($cf7mls_step_name) == 2)? '100%' : '50%') ?></p>
						</div>
					</div> -->
					<div class="cf7mls_bt_wrap">
						<div class="cf7mls_back_demo"><?php _e( 'Back', 'cf7mls' ); ?></div>
						<div class="cf7mls_next_demo"><?php _e( 'Next', 'cf7mls' ); ?></div>
					</div>
				</div>
				<!-- Progress Bar percent on ipad, mobie, computer-->
				<div class="cf7mls_progress_bar_per_mobie_wrap">
					<div class="cf7mls_progress_percent">
						<div class="cf7mls_progress_bar_percent">
							<div class="cf7mls_progress_barinner" style="<?php echo( ( count( $cf7mls_step_name ) == 2 ) ? 'width: 100%' : 'width: 50%' ); ?>"></div>
						</div>
					</div>
					<div>
						<p><?php echo( ( count( $cf7mls_step_name ) == 2 ) ? '100%' : '50%' ); ?></p>
					</div>
				</div>
					
			</div>
		</div>
		<p class="cf7mls_note"><?php _e( 'Preview only shows 3 steps.', 'cf7mls' ); ?></p>
	</div>
	<?php
}
add_filter( 'wpcf7_pre_construct_contact_form_properties', 'cf7mls_register_property', 10, 2 );
function cf7mls_register_property( $properties, $contact_form ) {
	// Add variable cf7mls_back_button_title to check data old of old version when active plugin.
	$more_properties = array(
		'cf7mls_back_bg_color'                => '',
		'cf7mls_back_text_color'              => '',
		'cf7mls_next_bg_color'                => '',
		'cf7mls_next_text_color'              => '',
		'cf7mls_db_save_every_step'           => '',
		'cf7mls_step_name'                    => json_encode( array() ),
		'cf7mls_progress_bar_style'           => 1,
		'cf7_mls_enable_progress_bar'         => '0',
		'cf7mls_progress_bar_bg_color'        => '#0073aa',
		'cf7_mls_auto_scroll_animation'       => '',
		'cf7_mls_auto_return_first_step'      => 'on',
		'cf7_mls_auto_moving_animation'       => '',
		'cf7mls_select_stype_transition'      => '',
		'cf7mls_style_text'                   => 'vertical',
		'cf7_mls_enable_progress_bar_percent' => '0',
		'cf7mls_progress_bar_percent_color'   => '#0073aa',
		'cf7mls_allow_choose_step'            => 'off',
		'cf7mls_back_button_title'            => '',
	);
	return array_merge( $more_properties, $properties );
}
add_filter( 'wpcf7_contact_form_properties', 'cf7mls_form_properties' );
function cf7mls_form_properties( $properties ) {
	// Check data old of old version when active plugin.
	if ( is_array( maybe_unserialize( $properties ) ) && array_key_exists( 'cf7mls_step_name', $properties ) && array_key_exists( 'form', $properties ) ) {
		$cf7mls_step_name = maybe_unserialize( $properties['cf7mls_step_name'] );
		$manager          = WPCF7_FormTagsManager::get_instance();
		$scan             = $manager->scan( $properties['form'] );
		$checkData        = false;
		foreach ( $scan as $k => $v ) {
			if ( $v->type == 'cf7mls_step' ) {
				if ( count( $v->values ) == 1 ) {
						$checkData = true;
						break;
				}
			}
		}

		if ( $checkData ) {
			$forms = explode( ']', $properties['form'] );
			$n     = 0;
			if ( is_array( $forms ) ) {
				foreach ( $forms as $key => $form ) {
					if ( strstr( $form, 'cf7mls_step' ) ) {
						$forms[ $key ] = $forms[ $key ] . ' "' . $cf7mls_step_name[ $n ] . '"';
						$n++;
					}

					if ( strstr( $form, '[' ) ) {
						$forms[ $key ] = $forms[ $key ] . ']';
					}

					if ( ( count( $forms ) - 1 ) == $key ) {
						$stepLast      = '';
						$stepLast     .= '[cf7mls_step cf7mls_step-' . $key;
						$stepLast     .= ' "' . $properties['cf7mls_back_button_title'] . '"';
						$stepLast     .= ' "' . $cf7mls_step_name[ $n ] . '"]';
						$forms[ $key ] = $stepLast . $forms[ $key ];
					}
				}
				$forms              = implode( '', $forms );
				$properties['form'] = $forms;
			}
		}
	}

	// Add variable cf7mls_back_button_title to check data old of old version when active plugin.
	$more_properties = array(
		'cf7mls_back_bg_color'                => '',
		'cf7mls_back_text_color'              => '',
		'cf7mls_next_bg_color'                => '',
		'cf7mls_next_text_color'              => '',
		'cf7mls_db_save_every_step'           => '',
		'cf7mls_step_name'                    => json_encode( array() ),
		'cf7mls_progress_bar_style'           => 1,
		'cf7_mls_enable_progress_bar'         => '0',
		'cf7mls_progress_bar_bg_color'        => '#0073aa',
		'cf7_mls_auto_scroll_animation'       => '',
		'cf7_mls_auto_return_first_step'      => 'on',
		'cf7_mls_auto_moving_animation'       => '',
		'cf7mls_select_stype_transition'      => '',
		'cf7mls_style_text'                   => 'vertical',
		'cf7_mls_enable_progress_bar_percent' => '0',
		'cf7mls_progress_bar_percent_color'   => '#0073aa',
		'cf7mls_allow_choose_step'            => 'off',
		'cf7mls_back_button_title'            => '',
	);
	return array_merge( $more_properties, $properties );
}

if ( ! function_exists( 'cf7mls_sanitize_arr' ) ) {
	function cf7mls_sanitize_arr( $arr ) {
		return is_array( $arr ) ? array_map( 'cf7mls_sanitize_arr', $arr ) : wp_kses_post( wp_unslash( $arr ) );
	}
}

add_action( 'wpcf7_save_contact_form', 'cf7mls_save_contact_form' );
function cf7mls_save_contact_form( $contact_form ) {
	$properties = $contact_form->get_properties();

	if ( isset( $_POST['back-btn-bg-color'] ) ) {
		$properties['cf7mls_back_bg_color'] = trim( sanitize_text_field( $_POST['back-btn-bg-color'] ) );
	}
	if ( isset( $_POST['back-btn-text-color'] ) ) {
		$properties['cf7mls_back_text_color'] = trim( sanitize_text_field( $_POST['back-btn-text-color'] ) );
	}
	if ( isset( $_POST['next-btn-bg-color'] ) ) {
		$properties['cf7mls_next_bg_color'] = trim( sanitize_text_field( $_POST['next-btn-bg-color'] ) );
	}
	if ( isset( $_POST['next-btn-text-color'] ) ) {
		$properties['cf7mls_next_text_color'] = trim( sanitize_text_field( $_POST['next-btn-text-color'] ) );
	}
	if ( isset( $_POST['cf7mls_db_save_every_step'] ) ) {
		$properties['cf7mls_db_save_every_step'] = 'yes';
	} else {
		$properties['cf7mls_db_save_every_step'] = 'no';
	}
	if ( isset( $_POST['cf7mls_step_name'] ) ) {
		$properties['cf7mls_step_name'] = cf7mls_sanitize_arr( $_POST['cf7mls_step_name'] );
	}
	if ( isset( $_POST['cf7mls_progress_bar_style'] ) ) {
		if ( sanitize_text_field( $_POST['cf7mls_progress_bar_style'] ) == 'box_vertical' || sanitize_text_field( $_POST['cf7mls_progress_bar_style'] ) == 'box_larerSign' ) {
			$properties['cf7mls_progress_bar_style'] = maybe_serialize( sanitize_text_field( $_POST['cf7mls_progress_bar_style'] ) . '_squaren' );
		} else {
			$properties['cf7mls_progress_bar_style'] = maybe_serialize( sanitize_text_field( $_POST['cf7mls_progress_bar_style'] ) . '_' . sanitize_text_field( $_POST['cf7mls_progress_bar_icon_style'] ) );
		}
	}
	if ( isset( $_POST['auto-scroll-animation'] ) ) {
		$properties['cf7_mls_auto_scroll_animation'] = trim( sanitize_text_field( $_POST['auto-scroll-animation'] ) );
	} else {
		$properties['cf7_mls_auto_scroll_animation'] = '';
	}
	if ( isset( $_POST['auto-return-first-step'] ) ) {
		$properties['cf7_mls_auto_return_first_step'] = trim( sanitize_text_field( $_POST['auto-return-first-step'] ) );
	} else {
		$properties['cf7_mls_auto_return_first_step'] = '';
	}
	if ( isset( $_POST['auto-moving-animation'] ) ) {
		$properties['cf7_mls_auto_moving_animation'] = trim( sanitize_text_field( $_POST['auto-moving-animation'] ) );
	} else {
		$properties['cf7_mls_auto_moving_animation'] = '';
	}
	if ( isset( $_POST['cf7mls_select_stype_transition'] ) ) {
		$properties['cf7mls_select_stype_transition'] = maybe_serialize( $_POST['cf7mls_select_stype_transition'] );
	}
	if ( isset( $_POST['cf7_mls_enable_progress_bar'] ) ) {
		$properties['cf7_mls_enable_progress_bar'] = intval( $_POST['cf7_mls_enable_progress_bar'] );
	} else {
		$properties['cf7_mls_enable_progress_bar'] = '0';
	}
	if ( isset( $_POST['progress-bar-bg-color'] ) ) {
		$properties['cf7mls_progress_bar_bg_color'] = trim( sanitize_text_field( $_POST['progress-bar-bg-color'] ) );
	}
	if ( isset( $_POST['cf7mls-style-text'] ) ) {
		$properties['cf7mls_style_text'] = trim( sanitize_text_field( $_POST['cf7mls-style-text'] ) );
	} else {
		$properties['cf7mls_style_text'] = 'vertical';
	}
	if ( isset( $_POST['cf7_mls_enable_progress_bar_percent'] ) ) {
			$properties['cf7_mls_enable_progress_bar_percent'] = intval( $_POST['cf7_mls_enable_progress_bar_percent'] );
	} else {
		$properties['cf7_mls_enable_progress_bar_percent'] = '0';
	}
	if ( isset( $_POST['progress-bar-percent-color'] ) ) {
		$properties['cf7mls_progress_bar_percent_color'] = trim( sanitize_text_field( $_POST['progress-bar-percent-color'] ) );
	}
	if ( isset( $_POST['cf7mls-allow-choose-step'] ) ) {
		$properties['cf7mls_allow_choose_step'] = trim( sanitize_text_field( $_POST['cf7mls-allow-choose-step'] ) );
	} else {
		$properties['cf7mls_allow_choose_step'] = 'off';
	}
	$contact_form->set_properties( $properties );
}
