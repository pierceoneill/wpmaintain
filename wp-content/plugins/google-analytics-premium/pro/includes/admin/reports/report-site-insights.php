<?php
/**
 * Site Insights Report
 *
 * @since 8.24.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Andrei Lupu
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Report_SiteInsights extends MonsterInsights_Report {

	public $title;
	public $class = 'MonsterInsights_Report_SiteInsights';
	public $name = 'site-insights';
	public $version = '1.0.0';
	public $level = 'basic';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.11.0
	 */
	public function __construct() {
		$this->title = __( 'Site Insights', 'ga-premium' );
		parent::__construct();
	}

	/**
	 * Prepare report-specific data for output.
	 *
	 * @param array $data The data from the report before it gets sent to the frontend.
	 *
	 * @return mixed
	 */
	public function prepare_report_data( $data ) {
		// Add flags to the countries report.
		if ( ! empty( $data['data']['countries'] ) ) {
			$country_names = monsterinsights_get_country_list( true );
			foreach ( $data['data']['countries'] as $key => $country ) {
				$data['data']['countries'][ $key ]['name'] = isset( $country_names[ $country['iso'] ] ) ? $country_names[ $country['iso'] ] : $country['iso'];
			}
		}

		// Escape urls for the top pages report.
		if ( ! empty( $data['data']['toppages'] ) ) {
			foreach ( $data['data']['toppages'] as $key => $page ) {
				$title = $data['data']['toppages'][ $key ]['title'];
				$url   = '(not set)' === $title ? '' : esc_url( $data['data']['toppages'][ $key ]['hostname'] );

				$data['data']['toppages'][ $key ]['hostname'] = $url;
			}
		}

		// Add logged in user name
		$user_info                 = wp_get_current_user();
		$data['data']['user_name'] = '';

		if ( ! empty( $user_info->user_firstname ) ) {
			$first_name = $user_info->user_firstname;

			$data['data']['user_name'] = $first_name;
		}

		return $data;
	}

	/**
	 * This start date.
	 *
	 * @return string
	 */
	public function default_start_date() {
		return date( 'Y-m-d', strtotime( '-30 days' ) );
	}

	/**
	 * The end date.
	 *
	 * @return string
	 */
	public function default_end_date() {
		return date( 'Y-m-d', strtotime( 'yesterday' ) );
	}
}
