<?php defined('ABSPATH') or die("you do not have access to this page!");
if (!class_exists('cmplz_admin_statistics')) {
	class cmplz_admin_statistics
	{
		private static $_this;
		public $prefix;

		function __construct()
		{
			if (isset(self::$_this))
				wp_die(sprintf('%s is a singleton class and you cannot create a second instance.', get_class($this)));
			self::$_this = $this;
			add_action( 'cmplz_install_tables', array( $this, 'update_db_check' ) );
			add_action( 'cmplz_before_save_option', array($this, 'init_statistics_on_settings_change'), 10, 4);
			add_filter( "cmplz_do_action", array( $this, 'statistics_data' ), 10, 3 );
		}

		static function this()
		{
			return self::$_this;
		}

		/**
		 * Override free function to enable a/b testing when active
		 * @param $enabled
		 * @return bool $enabled
		 *
		 */

		public function ab_testing_enabled($enabled): bool {
			return cmplz_get_option('a_b_testing');
		}

		/**
		 * Initialize the statistics if the ab setting is changed
		 * This ensures the data is cleared on disabling, and sets the start time on enabling.
		 * @param string $name
		 * @param mixed $value
		 * @param mixed $prev_value
		 * @param string $type
		 */

		public function init_statistics_on_settings_change($name, $value, $prev_value, $type)
		{
			if ($value === $prev_value){
				return;
			}
			if ($name === 'a_b_testing' && $value ) {
				$this->init_statistics();
			}
		}

		/**
		 * Restart or init statistics
		 */
		public function init_statistics()
		{
			if (!cmplz_user_can_manage()) {
				return;
			}
			cmplz_update_all_banners();
			update_option('cmplz_tracking_ab_started', time(), false );
			update_option('cmplz_enabled_best_performer', false, false );
		}

		/**
		 * If ab testing is enabled, and the plugin has been tracking for more than a month, the best performing banner will get selected as default banner.
		 *
		 *
		 * */

		public function cron_maybe_enable_best_performer()
		{
			if (!cmplz_ab_testing_enabled()) return;

			if ($this->seconds_left_ab_tracking()>0) {
				return;
			}

			//testing is currently enabled, and we have been testing more than a month. Time to set the best performing one, and disable tracking.
			$best_performer = $this->best_performing_cookiebanner();
			if ($best_performer) {
				$banner = cmplz_get_cookiebanner($best_performer);
				$banner->default = true;
				$banner->save();
				$this->init_statistics();
			}

			//disable tracking
			cmplz_update_option_no_hooks('a_b_testing', false);

			//store this change
			update_option('cmplz_enabled_best_performer', true, false );
		}

		/**
		 * Run database upgrade if necessary
		 */
		public function update_db_check()
		{
			//only load on front-end if it's a cron job
			if ( !is_admin() && !wp_doing_cron() ) {
				return;
			}

			if ( !wp_doing_cron() && !cmplz_user_can_manage() ) {
				return;
			}

			if ( get_option('cmplz_statsdb_version') != cmplz_version ) {

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();

				$table_name = $wpdb->prefix . 'cmplz_statistics';
				$sql = "CREATE TABLE $table_name (
                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                  `region` varchar(255) NOT NULL,
                  `pageviews` int(11) NOT NULL,
                  `consenttype` varchar(255) NOT NULL,
                  `ip` varchar(255) NOT NULL,
                  `time` varchar(255) NOT NULL,
                  `do_not_track` int(11) NOT NULL,
                  `no_choice` int(11) NOT NULL,
                  `no_warning` int(11) NOT NULL,
                  `functional` int(11) NOT NULL,
                  `preferences` int(11) NOT NULL,
                  `statistics` int(11) NOT NULL,
                  `marketing` int(11) NOT NULL,
                  `services` text NOT NULL,
                  `poc_url` text NOT NULL,
                  `cookiebanner_id` int(11) NOT NULL,
                  PRIMARY KEY  (ID)
                ) $charset_collate;";

				dbDelta($sql);

				update_option('cmplz_statsdb_version', cmplz_version, false );
			}
		}

		/**
		 * Get the best performing cookiebanner
		 * @return bool
		 */

		public function best_performing_cookiebanner(): bool {
			$banners = cmplz_get_cookiebanners();
			$best_performer_percentage = 0;
			$best_performer = false;
			foreach ($banners as $banner) {
				$banner = cmplz_get_cookiebanner($banner->ID);
				$p = $banner->conversion_percentage('all');
				if ($p > $best_performer_percentage) {
					$best_performer_percentage = $p;
					$best_performer = $banner->ID;
				}
			}
			return $best_performer;
		}

		/**
		 * Get the total number of seconds still left in this ab tracking test
		 * @return int
		 * @since 2.0.0
		 *
		 */

		public function seconds_left_ab_tracking()
		{
			if ($this->best_performer_enabled()) {
				return 0;
			}

			$start_date = get_option('cmplz_tracking_ab_started');
			$testing_duration = apply_filters('cmplz_ab_testing_duration', cmplz_get_option('a_b_testing_duration')) * DAY_IN_SECONDS;
			$now = time();
			$time_since = $now - $start_date;
			$seconds_left = $testing_duration - $time_since;
			if ( $seconds_left <0 ) {
				$seconds_left = 0;
			}

			return $seconds_left;
		}

		/**
		 * Get the time left in the current A/B test, human readable format.
		 * @since 2.0
		 * @return array|int
		 */

		public function time_left_ab_tracking()
		{
			if ( get_option('cmplz_enabled_best_performer') ) {
				return 0;
			}

			$start_date = get_option('cmplz_tracking_ab_started');
			$testing_duration = apply_filters('cmplz_ab_testing_duration', cmplz_get_option('a_b_testing_duration'));
			$now = time();
			$current_duration_days = round(($now - $start_date) / DAY_IN_SECONDS, 2);
			$days_left = $testing_duration - $current_duration_days;
			$days = round($days_left - 0.499);
			$hours = (($days_left - $days) * DAY_IN_SECONDS) / HOUR_IN_SECONDS;
			return array( 'days' => $days, "hours" => $hours);
		}

		/**
		 * Check if the best performer is enabled
		 *
		 * @since 2.0
		 * @return bool $enabled;
		 */

		public function best_performer_enabled()
		{
			return get_option('cmplz_enabled_best_performer');
		}

		/**
		 * @param $data
		 * @param $action
		 * @param $request
		 *
		 * @return array
		 */

		public function statistics_data($data, $action, $request){
			if ( $action === 'get_statistics_data' ) {
				$consent_types= cmplz_get_used_consenttypes(true);
				$regions = cmplz_get_regions( false , 'full' );
				$regions_flat = cmplz_format_as_javascript_array( $regions );
				$consent_types_data = [];
				$ab_tracking_completed = ! ( $this->seconds_left_ab_tracking() > 0 );
				$days_string  = '';
				if ( !$ab_tracking_completed ) {
					$time = $this->time_left_ab_tracking();
					$days_string = $time['days'];
				}
				$statistics_data = [];

				foreach ($consent_types as $consenttype => $label) {
					$statistics_data[$consenttype] = $this->get_statistics_data($consenttype);
					$consent_types_data[] = [
						'id' => $consenttype,
						'label' => $label,
					];
				}
				$data = [
					'statisticsData' => $statistics_data,
					'consentTypes' => $consent_types_data,
					'regions' => $regions_flat,
					'defaultConsentType' => COMPLIANZ::$company->get_default_consenttype(),
					'bestPerformerEnabled' => $this->best_performer_enabled(),
					'abTrackingCompleted' => $ab_tracking_completed,
					'daysLeft' => $days_string,
				];

			}
			return $data;
		}

		/**
		 * Get graph data
		 *
		 * @return array
		 */

		public function get_statistics_data($consenttype): array {
			if ( ! cmplz_user_can_manage() ) {
				return [];
			}
			$data = array();
			$range = apply_filters('cmplz_ab_testing_duration', cmplz_get_option('a_b_testing_duration')) * DAY_IN_SECONDS;

			//for each day, counting back from "now" to the first day, get the date.
			$now = time();
			$start_time = $now - $range;

			//generate a dataset for each category
			$cookiebanners = cmplz_get_cookiebanners();
			$data['labels'] = [];
			$data['categories'] = [];
			$category_keys = [];//we make sure the indexes of keys and labels are the same
			$i = 1;
			foreach ($cookiebanners as $cookiebanner ) {
				$i++;
				$cookiebanner = cmplz_get_cookiebanner( $cookiebanner->ID);
				$categories = $cookiebanner->get_available_categories(true);

				foreach ($categories as $key => $label ) {
					if (!in_array($label,  $data['labels'] )) {
						$data['labels'][] = $label;
						$data['categories'][] = $key;
						$category_keys[] = $key;
					}
				}
				$borderDash = array(0,0);
				$title = empty($cookiebanner->title) ? 'banner_'.$cookiebanner->position.'_'.$i : $cookiebanner->title;

				if ( !$cookiebanner->default ) {
					$borderDash = array(10,10);
				}else {
					$title .= " (".__("Default", "complianz-gdpr").")";
				}

				$no_consent = array(
					'no_warning',
					'do_not_track',
					'no_choice',
					'functional',
				);

				$full_consent = array(
					'preferences',
					'statistics',
					'marketing',
				);

				//get hits grouped per timeslot. default day
				$hits = $this->get_consent_per_category($cookiebanner->ID, $category_keys, $consenttype, $start_time );
				$no_consent = $this->get_consent_per_category($cookiebanner->ID, $no_consent, $consenttype, $start_time );
				$full_consent = $this->get_consent_per_category($cookiebanner->ID, $full_consent, $consenttype, $start_time );
				$data['datasets'][] = array(
					'data' => $hits,
					'backgroundColor' => $this->get_graph_color($i, 'background'),
					'borderColor' => $this->get_graph_color($i),
					'label' => $title,
					'fill' => 'false',
					'borderDash' => $borderDash,
					'default' =>$cookiebanner->default,
					'no_consent' => array_sum($no_consent),
					'full_consent' => array_sum($full_consent),
				);
			}
			if ( isset($data['datasets']) ) {
				//get highest hit count for max value
				$max = max( array_map('max',array_column( $data['datasets'], 'data' )));
				$data['max'] = max( $max, 5 );
			} else {
				$data['datasets'][] = array(
					'data' => array(0),
					'backgroundColor' => $this->get_graph_color(0, 'background'),
					'borderColor' => $this->get_graph_color(0),
					'label' => __("No data for this selection", "complianz-gdpr"),
					'fill' => 'false',
				);
				$data['max'] = 5;
			}
			return $data;
		}

		/**
		 * Get color for a graph
		 * @param int     $index
		 * @param string $type
		 *
		 * @return string
		 */

		public function get_graph_color( $index , $type = 'default' ) {
			$o = $type = 'background' ? '1' : '1';
			switch ($index) {
				case 0:
					return "rgba(46, 138, 55, 1)";
				case 1:
					return "rgba(244, 191, 62, 1)";
				case 2:
					return "rgba(46, 138, 55, 1)";
				case 3:
					return "rgba(244, 191, 62, 1)";
				default:
					return "rgba(46, 138, 55, 1)";

			}
		}

		/**
		 * @param string $period
		 * @param int $start_time
		 *
		 * @return float
		 */

		public function get_nr_of_periods($period, $start_time ): float {
			$range_in_seconds = time() - $start_time;
			$period_in_seconds = constant(strtoupper($period).'_IN_SECONDS' );
			return ROUND($range_in_seconds/$period_in_seconds);
		}

		/**
		 * @param int    $cookie_banner_id
		 * @param array  $categories
		 * @param string $consenttype
		 * @param int    $start_time
		 *
		 * @return array
		 */

		public function get_consent_per_category( $cookie_banner_id, $categories, $consenttype, $start_time ) {
			global $wpdb;
			$consenttype_sql = '';
			if ( $consenttype !== 'all' ) {
				$consenttype = in_array($consenttype, cmplz_get_used_consenttypes() ) ? $consenttype : 'optin';
				$consenttype_sql = $wpdb->prepare(" AND consenttype = %s", $consenttype);
			}

			$cookie_banner_id = (int) $cookie_banner_id;
			$start_time = (int) $start_time;
			$data = [];

			foreach ( $categories as $category ) {
				$sql = "SELECT COUNT(*) as hit_count FROM {$wpdb->prefix}cmplz_statistics where $category=1 AND cookiebanner_id = $cookie_banner_id AND time>$start_time $consenttype_sql";
				$data[] = $wpdb->get_var($sql);
			}
			return $data;
		}

	} //class closure
}
