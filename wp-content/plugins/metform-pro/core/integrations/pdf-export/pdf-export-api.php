<?php

namespace MetForm_Pro\Core\Integrations\Pdf_Export;

use MetForm_Pro\Traits\Singleton;

class Pdf_Export_Api {

	use Singleton;

	public function init() {
		add_action('wp_ajax_metform_pdf_get_entry', [$this, 'pdf_get_entry']);
	}

	/**
	 * Callback for ajax call from pdf exporting option
	 *
	 * @since      2.5.0
	 * @access     public
	 * @return     void
	 */
	public function pdf_get_entry() {

		if( !current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash($_POST['_wpnonce'])), 'metform-pdf-export' ) ) {			
			return;
		}

		$entry_id = isset( $_POST['entry_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['entry_id'] ) ) ) : "";

		if(!empty($entry_id)) {

			$form_id = get_post_meta($entry_id, 'metform_entries__form_id', true);

            $form_inputs = \MetForm\Core\Entries\Action::instance()->get_fields($form_id);

			$form_data = get_post_meta($entry_id, 'metform_entries__form_data', true);

			$files = get_post_meta($entry_id, 'metform_entries__file_upload', true);

			if(is_array($files) && is_array($form_data)) {
				foreach($files as $key => $data) {
					$form_data[$key] = $data['url'];
				}
			}

			echo wp_kses_post($this->createTableRow($form_data, $form_inputs)); exit;
		}

		return;
	}

	/**
	 * This method will generate a html table with entries data
	 *
	 * @since      2.5.0
	 * @access     public
	 * @param      array $form_data
	 * @param      array $form_inputs
	 * @return	   string	
	 */
	public function createTableRow($form_data, $form_inputs) {
		
		$form_inputs = is_array($form_inputs) ? $form_inputs : [];
	
		$data = '<table class="table">
					<thead>
						<tr>
							<th>Label</th>
							<th>Value</th>
						</tr>
					</thead>';
	
		foreach ($form_inputs as $widget) {
			
			$widgetType = $widget->widgetType;
			$mf_input_label = $widget->mf_input_label;
			$mf_input_name = $widget->mf_input_name;
	
			if (isset($form_data[$mf_input_name])) {
				if ($widgetType !== 'mf-signature' && $widgetType !== 'mf-credit-card') {
					$data .= 
						'<tr>
							<td>' . $mf_input_label . '</td>
							<td>' . $form_data[$mf_input_name] . '</td>
						</tr>';
				} elseif ($widgetType === 'mf-credit-card') {
					$data .= 
						'<tr>
							<td>' . $mf_input_label . '</td>
							<td>' . $form_data[$mf_input_name] . '</td>
						</tr>
						<tr>
							<td>' . $mf_input_label . ' Type</td>
							<td>' . $form_data[$mf_input_name . '--type'] . '</td>
						</tr>';
				} else {
					$signature[] = array($mf_input_label, $form_data[$mf_input_name]);
					$data .= 
					'<tr>
						<td>' . $mf_input_label . '</td>
						<td><img src="' . $form_data[$mf_input_name] . '"></td>
					</tr>';					
				}
			}
		}

		$data .= '</table>';
	
		return $data;
	}
}
