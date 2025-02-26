<?php

//  TODO Go through this file and remove UA and dual tracking references/usages

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monsterinsights_add_analytics_options( $options ) {
	if ( monsterinsights_get_option( 'userid', false ) && is_user_logged_in() ) {
		$options['userid'] = "'set', 'userId', '" . get_current_user_id() . "'";
	}

	return $options;
}

add_filter( 'monsterinsights_frontend_tracking_options_analytics_before_scripts', 'monsterinsights_add_analytics_options' );

// For gtag
function monsterinsights_add_analytics_options_gtag( $options ) {
	if ( monsterinsights_get_option( 'userid', false ) && is_user_logged_in() ) {
		$options['user_id'] = get_current_user_id();
	}

	return $options;
}

add_filter( 'monsterinsights_frontend_tracking_options_persistent_gtag_before_pageview', 'monsterinsights_add_analytics_options_gtag' );

function monsterinsights_scroll_tracking_maybe_v4() {
	$v4_id = monsterinsights_get_v4_id_to_output();
	if ( ! $v4_id ) {
		return;
	}
	?>
	var paramName = action.toLowerCase();
	var fieldsArray = {
	send_to: '<?php echo esc_js( $v4_id ); ?>',
	non_interaction: true
	};
	fieldsArray[paramName] = label;

	if (arguments.length > 3) {
	fieldsArray.scroll_timing = timing
	MonsterInsightsObject.sendEvent('event', 'scroll_depth', fieldsArray);
	} else {
	MonsterInsightsObject.sendEvent('event', 'scroll_depth', fieldsArray);
	}
	<?php
}

// Scroll tracking.
function monsterinsights_scroll_tracking_output_after_script() {
	if ( monsterinsights_skip_tracking() ) {
		return;
	}

	$track_user   = monsterinsights_track_user();
	$tracking_id = monsterinsights_get_v4_id();

	if ( $track_user && ! empty( $tracking_id ) ) {
		ob_start();
		echo PHP_EOL;
		?>
		/* MonsterInsights Scroll Tracking */
		if ( typeof(jQuery) !== 'undefined' ) {
		jQuery( document ).ready(function(){
		function monsterinsights_scroll_tracking_load() {
		if ( ( typeof(__gaTracker) !== 'undefined' && __gaTracker && __gaTracker.hasOwnProperty( "loaded" ) && __gaTracker.loaded == true ) || ( typeof(__gtagTracker) !== 'undefined' && __gtagTracker ) ) {
		(function(factory) {
		factory(jQuery);
		}(function($) {

		/* Scroll Depth */
		"use strict";
		var defaults = {
		percentage: true
		};

		var $window = $(window),
		cache = [],
		scrollEventBound = false,
		lastPixelDepth = 0;

		/*
		* Plugin
		*/

		$.scrollDepth = function(options) {

		var startTime = +new Date();

		options = $.extend({}, defaults, options);

		/*
		* Functions
		*/

		function sendEvent(action, label, scrollDistance, timing) {
		if ( 'undefined' === typeof MonsterInsightsObject || 'undefined' === typeof MonsterInsightsObject.sendEvent ) {
		return;
		}
		<?php
		monsterinsights_scroll_tracking_maybe_v4();
		?>
		}

		function calculateMarks(docHeight) {
		return {
		'25%' : parseInt(docHeight * 0.25, 10),
		'50%' : parseInt(docHeight * 0.50, 10),
		'75%' : parseInt(docHeight * 0.75, 10),
		/* Cushion to trigger 100% event in iOS */
		'100%': docHeight - 5
		};
		}

		function checkMarks(marks, scrollDistance, timing) {
		/* Check each active mark */
		$.each(marks, function(key, val) {
		if ( $.inArray(key, cache) === -1 && scrollDistance >= val ) {
		sendEvent('Percentage', key, scrollDistance, timing);
		cache.push(key);
		}
		});
		}

		function rounded(scrollDistance) {
		/* Returns String */
		return (Math.floor(scrollDistance/250) * 250).toString();
		}

		function init() {
		bindScrollDepth();
		}

		/*
		* Public Methods
		*/

		/* Reset Scroll Depth with the originally initialized options */
		$.scrollDepth.reset = function() {
		cache = [];
		lastPixelDepth = 0;
		$window.off('scroll.scrollDepth');
		bindScrollDepth();
		};

		/* Add DOM elements to be tracked */
		$.scrollDepth.addElements = function(elems) {

		if (typeof elems == "undefined" || !$.isArray(elems)) {
		return;
		}

		$.merge(options.elements, elems);

		/* If scroll event has been unbound from window, rebind */
		if (!scrollEventBound) {
		bindScrollDepth();
		}

		};

		/* Remove DOM elements currently tracked */
		$.scrollDepth.removeElements = function(elems) {

		if (typeof elems == "undefined" || !$.isArray(elems)) {
		return;
		}

		$.each(elems, function(index, elem) {

		var inElementsArray = $.inArray(elem, options.elements);
		var inCacheArray = $.inArray(elem, cache);

		if (inElementsArray != -1) {
		options.elements.splice(inElementsArray, 1);
		}

		if (inCacheArray != -1) {
		cache.splice(inCacheArray, 1);
		}

		});

		};

		/*
		* Throttle function borrowed from:
		* Underscore.js 1.5.2
		* http://underscorejs.org
		* (c) 2009-2013 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
		* Underscore may be freely distributed under the MIT license.
		*/

		function throttle(func, wait) {
		var context, args, result;
		var timeout = null;
		var previous = 0;
		var later = function() {
		previous = new Date;
		timeout = null;
		result = func.apply(context, args);
		};
		return function() {
		var now = new Date;
		if (!previous) previous = now;
		var remaining = wait - (now - previous);
		context = this;
		args = arguments;
		if (remaining <= 0) {
		clearTimeout(timeout);
		timeout = null;
		previous = now;
		result = func.apply(context, args);
		} else if (!timeout) {
		timeout = setTimeout(later, remaining);
		}
		return result;
		};
		}

		/*
		* Scroll Event
		*/

		function bindScrollDepth() {

		scrollEventBound = true;

		$window.on('scroll.scrollDepth', throttle(function() {
		/*
		* We calculate document and window height on each scroll event to
		* account for dynamic DOM changes.
		*/

		var docHeight = $(document).height(),
		winHeight = window.innerHeight ? window.innerHeight : $window.height(),
		scrollDistance = $window.scrollTop() + winHeight,

		/* Recalculate percentage marks */
		marks = calculateMarks(docHeight),

		/* Timing */
		timing = +new Date - startTime;

		checkMarks(marks, scrollDistance, timing);
		}, 500));

		}

		init();
		};

		/* UMD export */
		return $.scrollDepth;

		}));

		jQuery.scrollDepth();
		} else {
		setTimeout(monsterinsights_scroll_tracking_load, 200);
		}
		}
		monsterinsights_scroll_tracking_load();
		});
		}
		/* End MonsterInsights Scroll Tracking */
		<?php
		echo PHP_EOL;
		$scroll_script = ob_get_clean();

		if ( wp_script_is( 'jquery', 'enqueued' ) ) {
			echo '<script type="text/javascript">' . $scroll_script . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			wp_enqueue_script( 'jquery' );
			wp_add_inline_script( 'jquery', $scroll_script );
		}
	}

}

add_action( 'wp_footer', 'monsterinsights_scroll_tracking_output_after_script', 11 );

/**
 * Skip page tracking based on the meta field.
 *
 * @param bool $skipped status of page tracking skip.
 *
 * @return bool
 */
function monsterinsights_skip_page_tracking( $skipped ) {
	if ( ! is_singular() ) {
		return $skipped;
	}

	global $post;

	return (bool) get_post_meta( $post->ID, '_monsterinsights_skip_tracking', true );
}

add_filter( 'monsterinsights_skip_tracking', 'monsterinsights_skip_page_tracking' );
