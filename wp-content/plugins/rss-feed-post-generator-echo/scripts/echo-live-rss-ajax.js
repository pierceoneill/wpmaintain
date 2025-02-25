'use strict';
document.addEventListener('DOMContentLoaded', function() {
	const rss_feeds = document.querySelectorAll('.echo-ajax-class');
	rss_feeds.forEach(function(rss_feed) {
		const feed_settings = window[rss_feed.dataset.id];

		echo_live_rss_fetch_feed(feed_settings)
		  	.then(data => {
		    	rss_feed.innerHTML = data;
		  	})
		  	.catch(error => {
		    	console.log(error);
		  	});
	});
	function echo_live_rss_fetch_feed(feed_settings) {
	  return new Promise((resolve, reject) => {
	    jQuery.ajax({
			type: "post",
			dataType: "json",
			url: echo_live_rss.ajax_url,
			data: {'action':'echo_live_rss_ajax_request', 'settings' : feed_settings},
	      success: function(data) {
	        resolve(data);
	      },
	      error: function(error) {
	        reject(error);
	      },
	    })
	  })
	};
});
