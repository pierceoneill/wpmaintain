<?php

/**
 * Add notification when the number of total sessions is less than the previous 30 days.
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Traffic_Dropping extends MonsterInsights_Notification_Event {

	public $notification_id       = 'monsterinsights_notification_traffic_dropping';
	public $notification_interval = 30; // in days
	public $notification_type     = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_category = 'insight';
	public $notification_priority = 2;

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$data                             = array();
		$report                           = $this->get_report();
		$data['prev_sessions_difference'] = isset( $report['data']['infobox']['sessions']['prev'] ) ? $report['data']['infobox']['sessions']['prev'] : 0;

		if ( ! empty( $data ) && $data['prev_sessions_difference'] < 0 ) {

			$is_em = function_exists( 'ExactMetrics' );

			$learn_more_url = $is_em
				? 'https://www.exactmetrics.com/epic-list-of-marketing-hacks-for-explosive-traffic-growth/'
				: 'https://www.monsterinsights.com/marketing-hacks-guaranteed-to-grow-your-traffic/';

			$notification['title'] = __( 'Your Website Traffic is Dropping', 'ga-premium' );
			// Translators: Traffic dropping notification content
			$notification['content'] = sprintf( __( 'Your website traffic is decreasing and that’s a reason to take action now. Less traffic means less opportunities to make your brand known, make relationships and ultimately sell your service or product. <br><br>Follow the marketing hacks of %1$sthis article%2$s to start growing your traffic again.', 'ga-premium' ), '<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/marketing-hacks-guaranteed-to-grow-your-traffic/' ) . '" target="_blank">', '</a>' );
			$notification['btns']    = array(
				'learn_more'  => array(
					'url'         => $this->build_external_link( $learn_more_url ),
					'text'        => __( 'Learn More', 'ga-premium' ),
					'is_external' => true,
				),
				'view_report' => array(
					'url'  => $this->get_view_url( 'monsterinsights-report-overview', 'monsterinsights_reports' ),
					'text' => __( 'View Report', 'ga-premium' ),
				),
			);

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Traffic_Dropping();