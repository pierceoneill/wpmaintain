<?php

/**
 * Add notification when percentage of visitors from mobile device is less than 10%
 * Recurrence: 15 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Mobile_Device_Low_Traffic extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_mobile_device';
	public $notification_interval = 15; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_category = 'insight';
	public $notification_priority = 2;

	/**
	 * Prepare Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$data                                  = array();
		$report                                = $this->get_report( 'overview', $this->report_start_from, $this->report_end_to );
		$data['percentage_of_mobile_visitors'] = isset( $report['data']['devices']['mobile'] ) ? $report['data']['devices']['mobile'] : 0;

		if ( ! empty( $data ) && $data['percentage_of_mobile_visitors'] < 10 ) {
			// Translators: Mobile device notification title
			$notification['title'] = sprintf( __( 'Traffic From Mobile Devices %s%%', 'ga-premium' ), $data['percentage_of_mobile_visitors'] );
			/* translators: Placeholders add a link to an article. */
			$notification['content'] = sprintf( __( 'Traffic from mobile devices is considerably lower on your site compared to desktop devices. This could be an indicator that your site is not optimised for mobile devices.<br><br>Take a look now at %1$sshow your site looks%2$s on mobile and make sure all your content can be accessed correctly.', 'ga-premium' ), '<a href="' . $this->build_external_link( 'https://www.wpbeginner.com/beginners-guide/how-to-preview-the-mobile-layout-of-your-site/' ) . '" target="_blank">', '</a>' );
			$notification['btns']    = array(
				"view_report" => array(
					'url'  => $this->get_view_url( 'devices', 'monsterinsights_reports' ),
					'text' => __( 'View Report', 'ga-premium' )
				),
				"learn_more"  => array(
					'url'         => $this->build_external_link( 'https://www.wpbeginner.com/beginners-guide/how-to-preview-the-mobile-layout-of-your-site/' ),
					'text'        => __( 'Learn More', 'ga-premium' ),
					'is_external' => true,
				),
			);

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Mobile_Device_Low_Traffic();
