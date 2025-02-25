<?php
add_shortcode( 'echo-live-rss', 'echo_display_rss_func' );

function echo_exception_error( $message, $always_diplay = false ){
    if (current_user_can('editor') || current_user_can('administrator') || $always_diplay === true) 
	{
        throw new Exception($message);
    } 
	else 
	{
        throw new Exception("");
    }
}
add_filter( 'widget_text', 'do_shortcode' );
function echo_display_rss_func( $atts ){
    if ( is_admin() ) 
    {
        return;
    }
    $parsed_attributes = shortcode_atts( array(
        'url' => '#',
        'items' => '10',
        'orderby' => 'default',
        'title' => 'true',
        'excerpt' => '20',
        'read_more' => 'true',
        'new_window' => 'true',
        'thumbnail' => 'true',
        'source' => 'true',
        'date' => 'true',
        'cache' => '43200',
        'dofollow' => 'false',
        'ajax' => 'true',
		'keep_content_tags' => '',
        'layout' => 'thumbnail,title,content,postdata',
		'append_url' => '',
		'default_text' => 'Sorry, there is a problem with this page right now. We are working on fixing it as soon as possible.',
		'read_more_text' => 'Read more'
    ), $atts );
    wp_enqueue_style('echo-live-rss', plugins_url('/../styles/echo-live-rss.css', __FILE__), false, '1.0.0');
    wp_enqueue_script('jquery');
    wp_register_script('echo-live-rss-ajax', plugins_url('/../scripts/echo-live-rss-ajax.js', __FILE__), array('jquery'), '1.0.0', true);
    wp_enqueue_script('echo-live-rss-ajax');
    wp_localize_script( 'echo-live-rss-ajax', 'echo_live_rss',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    try {
        $feed = new Echo_RSS_Feed_Display($parsed_attributes);
        return $feed->display_feed();
    } catch (Exception $e) {
        return $e->getMessage() . "\n";
    }
}

function echo_live_rss_ajax_request() 
{ 
    if ( isset($_REQUEST) ) 
	{
        wp_enqueue_style('echo-live-rss', plugins_url('/../styles/echo-live-rss.css', __FILE__), false, '1.0.0');
        wp_enqueue_script('jquery');
        wp_register_script('echo-live-rss-ajax', plugins_url('/../scripts/echo-live-rss-ajax.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_enqueue_script('echo-live-rss-ajax');
        wp_localize_script( 'echo-live-rss-ajax', 'echo_live_rss', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        $settings = isset($_REQUEST['settings']) ? $_REQUEST['settings'] : null; 
        $settings['ajax'] = 'false';
        try 
		{
            $feed = new Echo_RSS_Feed_Display($settings);
            $output = $feed->display_feed();
        } 
		catch (Exception $e) 
		{
            $output = $e->getMessage() . "\n";
        }
        echo json_encode($output);
    }
    die();
}
 
add_action( 'wp_ajax_echo_live_rss_ajax_request', 'echo_live_rss_ajax_request' );
add_action( 'wp_ajax_nopriv_echo_live_rss_ajax_request', 'echo_live_rss_ajax_request' );

class Echo_RSS_Feed_Display 
{
	private $feed_items 	= array();
	private $settings 		= array(); 
	private $transient_key 	= '';
	private $is_cached 		= false;

	function __construct($data) 
	{
		if(!is_array($data)) 
		{
			if(!empty($this->settings['default_text']))
			{
				echo_exception_error($this->settings['default_text'], true);
			}
			else
			{
				echo_exception_error("Unable to construct " . get_class($this) . " with variable type: " . gettype($data), false);
			}
		} 
		else if (count($data) > 0) 
		{
			$this->set_transient_key($data);

			if (!$this->get_cached_feed()) {
				foreach ($data as $name => $value) {
					$this->settings[$name] = $value;
				}
				if ($this->settings['ajax'] === 'false') {
					$this->validate_settings();
					$this->retrieve_feed();
				}
			}
		} 
		else 
		{
			if(!empty($this->settings['default_text']))
			{
				echo_exception_error($this->settings['default_text'], true);
			}
			else
			{
				echo_exception_error("Not enough data.", false);
			}
		}
	}

	private function retrieve_feed() 
	{
        try
        {
            if(!class_exists('SimplePie_Autoloader', false))
            {
                require_once(dirname(__FILE__) . "/../res/simplepie/autoloader.php");
            }
        }
        catch(Exception $e) 
        {
			if(!empty($this->settings['default_text']))
			{
				echo_exception_error($this->settings['default_text'], true);
			}
			else
			{
            	echo_exception_error('Exception thrown in SimplePie autoloader: ' . $e->getMessage(), false);
			}
        }
		$rss = new SimplePie();
		$rss->set_feed_url($this->settings['url']);	
		$rss->set_useragent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36');	
		$rss->force_feed(true);
		$rss->enable_cache(false);
		$rss->enable_order_by_date(false);
		$rss->init();
    	$rss->set_output_encoding( get_option( 'blog_charset' ) );
		$rss->handle_content_type();

    	if (!$rss || !is_wp_error( $rss )) {
    		// if the feed doesn't work, try the WP built-in method fetch_feed
    		if (@$rss->get_item_quantity( $this->settings['items'] ) == 0) {
    			// reset the built-in cache to 1 second as we'll be using our own caching
    			add_filter( 'wp_feed_cache_transient_lifetime' , array(__CLASS__, 'return_1') );
				if(is_array($this->settings['url']))
				{
					$reversedUrls = array_reverse($this->settings['url']);
					$ffedd = array_pop($reversedUrls);
				}
				else
				{
					$ffedd = $this->settings['url'];
				}
    			$rss = fetch_feed($ffedd);
    			remove_filter( 'wp_feed_cache_transient_lifetime' , array(__CLASS__, 'return_1') );
    		}
    		// use original feed order
    		if ($this->settings['orderby'] === 'date' || $this->settings['orderby'] === 'date_reverse') {
                $rss->enable_order_by_date(true);
            }
            // suppress "non-numeric value warning for Date"
		    if (!is_wp_error( $rss ) && !@$rss->get_item_quantity( $this->settings['items'] ) == 0) {
		    	$rss_items = $rss->get_items( 0, $this->settings['items'] );
		    	$rss_items = $this->orderby_sort($rss_items);

		    	foreach($rss_items as $item) {
		    		$item_object = new Echo_RSS_Feed_Display_Item($item, $this->settings);
		    		$this->feed_items[] = $item_object;
		    	}

		    	$this->set_cached_feed();
		    } else {
		    	// include the rss error message from the object if it exists
		    	if (isset($rss->error[0])) {
		    		$more_details = ' More details: ' . $rss->error[0];
		    	} else {
		    		$more_details = '';
		    	}
				if(!empty($this->settings['default_text']))
				{
					echo_exception_error($this->settings['default_text'], true);
				}
				else
				{
		    		echo_exception_error('No RSS items found with URL: <strong>' . implode(',', $this->settings['url']) . '</strong>.' . $more_details, false);
				}
		    }
		} else {
			if(!empty($this->settings['default_text']))
			{
				echo_exception_error($this->settings['default_text'], true);
			}
			else
			{
				echo_exception_error('Unable to fetch RSS feed with URL: <strong>' . implode(',', $this->settings['url']) . '</strong>.', false);
			}
		}
	}

	public static function return_1() {
		return 1;
	}

	private function set_transient_key($data) {
		// always reset ajax setting to false, this way the data is cached regardless of the request method
		$data['ajax'] = 'false';
		// set the transient to an encrypted key (ex. echo_feed_96f040501c24f0cbe83a95ec2b148b62)
		$this->transient_key = 'echo_feed_' . md5(json_encode($data));
	}

	private function get_cached_feed() {
		// check if a cached version of the feed exists
		if ($cache = get_transient($this->transient_key)) {
			$this->feed_items = $cache->feed_items;
			$this->settings = $cache->settings;
			$this->is_cached = true;
			return true;
		} else {
			return false;
		}
	}

	private function set_cached_feed() {
		// store the feed into transient cache
		set_transient( $this->transient_key, $value = $this, $expires = $this->settings['cache'] );
	}

	public function display_feed() {
		if (!$this->settings['ajax'] || $this->is_cached) :
	        $output = '<div' . $this->get_wrapper_classes() . '>';
	            $output .= '<ul class="echo_rss_list">';
	            	foreach($this->feed_items as $item) {
	                    $output .= '<li' . $this->get_item_inline_css() . ' class="echo_rss_item">';
		                    $output .= '<div class="echo_rss_item_wrapper">';
		                    	if(isset($this->settings['layout']) && $this->settings['layout'] != '')
                                {
                                    $default_layout = explode(',', $this->settings['layout']);
                                    $default_layout = array_map('trim', $default_layout);
                                }
                                else
                                {
                                    $default_layout = array(
                                        'title', 
                                        'thumbnail', 
                                        'content', 
                                        'postdata'
                                    );
                                }
		                    	$layout = apply_filters( 'echo_rss_layout',  $default_layout);
		                    	// set the default layout values (title, thumbnail, content, postdata)
		                    	foreach($default_layout as $default_layout_item => $value) {
		                    		if (false !== $key = array_search($value, $layout)) {
		                    			$func_name = 'get_' . $value;
		                    			if (method_exists($this, $func_name)) {
			                    			$layout[$key] = $this->$func_name($item);
			                    		}
		                    		}
		                    	}
		                    	// add layout items to the output
		                    	for ($i=0; $i < count($layout); $i++) { 
		                    		$output .= $layout[$i];
		                    	}
		            		$output .= '</div>';
	            		$output .= '</li>';
	            	}
	            $output .= '</ul>';
	        $output .= '</div>';
	    else :
	    	$id = 'rss' . substr(str_shuffle(MD5(microtime())), 0, 10);
	    	$loading_gif = plugins_url('img/ajax-loader.gif', __FILE__);

	    	$output  = '<div class="echo-ajax-class" data-id="' . $id . '">';
	    		$output .= '<img src="' . $loading_gif . '" alt="' . esc_html__( 'Loading RSS Feed', 'rss-feed-post-generator-echo' ) . '" width="16" height="16">';
	    	$output .= '</div>';
	    	wp_localize_script( 'echo-live-rss-ajax', $id, $this->settings );

	    	wp_enqueue_script('echo-live-rss-ajax');
	    endif;

		return $output;
	}

	private function orderby_sort($items) {
		if ($this->settings['orderby'] == 'date_reverse') {
		    $items = array_reverse($items);
		}

		if ($this->settings['orderby'] == 'random') {
		    shuffle($items);
		}

		return $items;
	}

	private function get_title($item) {
		if ($this->settings['title']) {
		    $output = '<a class="echo_rss_title"' . $this->get_link_target() . ' href="' . esc_url_raw($item->permalink) . '"' .
		        $this->get_link_dofollow() .
		        'title="' . esc_attr($item->title) . '">';
		        $output .= wp_specialchars_decode(apply_filters( 'echo_rss_title', esc_html($item->title)));
		    $output .= '</a>'; 

		    return $output;  
		} else {
			return null;
		}
	}

	private function get_thumbnail($item) {
		if (property_exists($item, 'thumbnail') && $this->settings['thumbnail'] && strlen($item->thumbnail) > 0) {
			$output = '<a class="echo_rss_image"' . $this->get_thumbnail_inline_styles() . $this->get_link_target()  . $this->get_link_dofollow() . ' href="' . esc_url_raw($item->permalink) . '">';
				$output .= '<img class="portrait" src="' . esc_attr($item->thumbnail) . '" alt="' . esc_attr($item->title) . '" onerror="this.parentNode.style.display=\'none\'"/>';
			$output .= '</a>'; 
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_content($item) {
		if ($this->settings['excerpt']) {
			$output = '<div class="echo_rss_container">';
			    $output .= $item->content;

			    // read more link
			    if($this->settings['read_more']) {
			        $output .= ' <a class="echo_rss_readmore"' . $this->get_link_target() . 
			        	' href="' . esc_url_raw($item->permalink) . '"' .
			            $this->get_link_dofollow() .
			            'title="' . esc_attr($item->title) . '">';
					if(!empty($this->settings['read_more_text']))
					{
						$output .= $this->settings['read_more_text'];
					}
					else
					{
						$output .= esc_html__( 'Read more', 'rss-feed-post-generator-echo' );
					}
					$output .= '&nbsp;&raquo;';
			        $output .= '</a>';
			    }
			$output .= '</div>';
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_postdata($item) {
		if ($this->settings['source'] || $this->settings['date']) {
		    $output = '<div class="echo_rss_metadata' .'">';
		        // source
		        if ($this->settings['source'] && $item->source) {
		            $label = esc_html__( 'Source', 'rss-feed-post-generator-echo' ) . ': ';
		            $output .= '<span class="echo_rss_source">' . $label . '<span>' . esc_html($item->source) . '</span></span>';
		        }
		        // separator
		        if ($this->settings['source'] && $this->settings['date']) {
		            $output .= ' | ';
		        }
		        // date
		        if ($this->settings['date'] && $item->date) {
		            $label = esc_html__( 'Published', 'rss-feed-post-generator-echo' ) . ': ';
		            $output .= '<span class="echo_rss_date">' . $label . '<span>' . esc_html($item->date) . '</span></span>';
		        }
		    $output .= '</div>';
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_thumbnail_inline_styles() {
	    if ($this->settings['thumbnail']){
	        $output = ' style="width:' . esc_attr($this->settings['thumbnail']['width']) . '; height:' . esc_attr($this->settings['thumbnail']['height']) . ';"';
	    } else {
	        $output = '';
	    }
	    return $output;
	}

	private function get_link_target() {
		if ($this->settings['new_window']) {
			return ' target="_blank"';
		} else {
			return null;
		}
	}

	private function get_link_dofollow() {
		if (!$this->settings['dofollow']) {
			return ' rel="nofollow"';
		} else {
			return null;
		}
	}

	private function get_wrapper_classes() {
		$layout_classes = '';
		$output = ' class="echo_live_rss' . $layout_classes . '"';
		
		return $output;
	}

	private function get_item_inline_css() {
		$output = '';

		return $output;	
	}

	/**
	 * Checks each shortcode attribute for validation and displays
	 * errors if the input is invalid. Converts each attribute into 
	 * an appropriate data type (ie. boolean, array, integer, etc.)
	 */
	private function validate_settings() {
		// convert open/closing double quotes
		foreach($this->settings as $attr => $value) {
			$remove_characters = array('???', '???', '???', '???', '???');
			$value = str_replace($remove_characters, '', $value);
			$this->settings[$attr] = $value;
		}

		// Setting: URL
		// convert comma separated urls into array
		if( strpos($this->settings['url'], ',') !== false ) {
			$urls = explode(',', $this->settings['url']);
			foreach($urls as $url) {
				$this->validate_url($url);
			}
			$this->settings['url'] = $urls;
		} else {
			$this->validate_url($this->settings['url']);
			// always use an array
			$this->settings['url'] = array($this->settings['url']);
		}

		// Setting: ITEMS
		if (!is_numeric($this->settings['items']) || intval($this->settings['items']) < 1) { 
			$this->validation_error('items');
		} else {
			$this->settings['items'] = intval($this->settings['items']);
		}

		// Setting: ORDERBY
		$acceptable_values = array(
			'default',
			'date',
			'date_reverse',
			'random'
		);
		if (!in_array($this->settings['orderby'], $acceptable_values)) {
			$this->validation_error('orderby');
		}

		// Setting: TITLE
		$this->validate_str_bool_setting('title');

		// Setting: EXCERPT
		if (!is_numeric($this->settings['excerpt']) && $this->settings['excerpt'] !== 'none') {
			$this->validation_error('excerpt');
		} else {
			if (is_numeric($this->settings['excerpt'])) {
				// convert to integer
				$this->settings['excerpt'] = intval($this->settings['excerpt']);
			} else {
				$this->settings['excerpt'] = false;
			}
		}

		// Setting: READ_MORE
		$this->validate_str_bool_setting('read_more');

		// Setting: NEW_WINDOW
		$this->validate_str_bool_setting('new_window');

		// Setting: THUMBNAIL
		// always remove 'px' from the input
		$this->settings['thumbnail'] = str_replace('px', '', $this->settings['thumbnail']);
		if (!$this->is_str_bool($this->settings['thumbnail']) && 
			!is_numeric(str_replace(array('x', '%'), '', $this->settings['thumbnail']))) {
			$this->validation_error('thumbnail');
		} else {
			// "true" or "false"
			if ($this->is_str_bool($this->settings['thumbnail'])) {
				$this->validate_str_bool_setting('thumbnail');

				if ($this->settings['thumbnail'] === true) {
					$this->settings['thumbnail'] = array (
						'width' => '150px',
						'height' => '150px'
					);
				}
			} else {
				// setting has an x (ie 100x200)
				if (strpos($this->settings['thumbnail'], 'x') !== false) {
					$size = explode('x', $this->settings['thumbnail']);
					// make sure both values exist (x99, 99x will fail)
					if (count($size) > 1 && strlen($size[0]) > 0 && strlen($size[1]) > 0) {
						$width = $size[0];
						$height = $size[1];
					} else {
						$this->validation_error('thumbnail');
					}
				} else {
					$width = $this->settings['thumbnail'];
					$height = $this->settings['thumbnail'];
				}

				// add 'px' if '%' is missing
				$width 	= (strpos($width, '%')) ? $width : $width . 'px';
				$height = (strpos($height, '%')) ? $height : $height . 'px';

				$this->settings['thumbnail'] = array (
					'width' => $width,
					'height' => $height
				);
			}
		}

		// Setting: SOURCE
		$this->validate_str_bool_setting('source');

		// Setting: DATE
		$this->validate_str_bool_setting('date');

		// Setting: CACHE
		if (!is_numeric(strtotime($this->settings['cache'], 0)) && !is_numeric($this->settings['cache'])) {
			$this->validation_error('cache');
		} else {
			// convert the cache to seconds if it is a string (ie. 1 hour, 1 day, etc.)
			if (!is_numeric($this->settings['cache'])) {
				$this->settings['cache'] = strtotime($this->settings['cache'], 0);
			}

			if ($this->settings['cache'] === 0) {
				// Do not allow 0 seconds, this will create a cached feed that never expires
				$this->settings['cache'] = -1;
			}
		}

		// Setting: DOFOLLOW
		$this->validate_str_bool_setting('dofollow');

		// Setting: AJAX
		$this->validate_str_bool_setting('ajax');

	}

	private function validate_str_bool_setting($setting) {
		if (!$this->is_str_bool($this->settings[$setting])) {
			// not 'true' or 'false'
			$this->validation_error($setting);
		} else {
			// convert the value to an actual boolean
			$this->settings[$setting] = ($this->settings[$setting] === 'true') ? true : false;
		}
	}

	private function is_str_bool($value) {
		if ($value !== 'true' && $value !== 'false') {
			return false;
		} else {
			return true;
		}
	}

	private function validate_url($url) {
		$url = filter_var($url, FILTER_SANITIZE_URL);
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;
		} else {
			$this->validation_error('url');
		}
	}

	private function validation_error($setting) {
		echo_exception_error('Invalid <strong>' . $setting . '</strong> value: <strong>' . $this->settings[$setting] . '</strong>. Please check your shortcode.', false);
	}
}

class Echo_RSS_Feed_Display_Item {	
	public $title 		= ''; // title
	public $permalink 	= ''; // url of post
	public $date 		= ''; // date
	public $content 	= ''; // excerpt
	public $thumbnail 	= ''; // image src url
	public $source 		= ''; // feed source title


	function __construct($item, $settings) {
		if(!is_object($item)) {
			echo_exception_error("Unable to construct " . get_class($this) . " with variable type: " . gettype($item), false);
		} else if (!empty($item)) {
			$this->get_data_from_feed_object($item, $settings);
		} else {
			echo_exception_error("Not enough data.", false);
		}
	}

	// construct all of the pertinent item data
	private function get_data_from_feed_object($item, $settings) {
		$this->title 		= $item->get_title();
		$this->permalink 	= $item->get_permalink();
		if(!empty($settings['append_url']))
		{
			if (strpos($this->permalink, "?"))
			{
				$this->permalink .= "&" . $settings['append_url']; 
			}
			else
			{
				$this->permalink .= "?" . $settings['append_url'];
			}
		}
		$dates = $item->get_date();
		if(!empty($dates))
		{
			$this->date 		= $this->convert_timezone($dates); // suppress "non-numeric value warning for Date"
		}
		else
		{
			$this->date 		= '';
		}
		$this->content 		= $this->format_content($item->get_description(), $settings);
		$this->thumbnail 	= $this->get_thumbnail($item);
		$this->source 		= $this->get_source($item);
	}

	private function get_source($item) {
		return $item->get_feed()->get_title();
	}

	private function get_thumbnail($item) {
		$enclosure = $item->get_enclosure();
		$content = $item->get_content();

		if ($enclosure->get_thumbnail()) {
			return $enclosure->get_thumbnail();
		} elseif ($this->get_first_image($content)) {
			return $this->get_first_image($content);
		} elseif ($this->get_image_tag_data($item)) {
			return $this->get_image_tag_data($item);
		// special case for itunes:image
		} elseif ($item->data['child'][SIMPLEPIE_NAMESPACE_ITUNES]['image'][0]['attribs']['']['href']) {
			return $item->data['child'][SIMPLEPIE_NAMESPACE_ITUNES]['image'][0]['attribs']['']['href'];
		} elseif ($enclosure->get_link()) {
			return $enclosure->get_link();
		} else {
			return null;
		}
	}

	private function get_image_tag_data($item) {
		$image = $item->get_item_tags('', 'image');

		if (isset($image[0]['data'])) {
			return $image[0]['data'];
		} else {
			return false;
		}
	}

	private function get_first_image($content) {
		require_once (dirname(__FILE__) . "/../res/simple_html_dom.php");
		$post_html = echo_str_get_html($content);
		if ($post_html) 
		{
			$first_img = $post_html->find('img', 0);
			if($first_img !== null) {
				return $first_img->src;
			}
		}
		else
		{
			$htmlDom = new DOMDocument;
			$internalErrors = libxml_use_internal_errors(true);
			$htmlDom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
			libxml_use_internal_errors($internalErrors);
			$links = $htmlDom->getElementsByTagName('img');
			foreach($links as $link)
			{
				return $link->getAttribute('src');
			}
		}
		return null;
	}

	private function format_content($content, $settings) 
	{
		$allowed_tags = array();
		if(!empty($settings['keep_content_tags']))
		{
			$allowed = explode(',', $settings['keep_content_tags']);
			$allowed = array_map('trim', $allowed);
			$allowed = array_filter($allowed);
			if(!empty($allowed))
			{
				foreach($allowed as $alw)
				{
					$allowed_tags[$alw] = array();
				}
			}
		}
		$content_no_tags = wp_kses($content, $allowed_tags);
		$content_no_tags = str_replace(array("\r", "\n"), '', $content_no_tags);
		if (isset($settings['excerpt']) && $settings['excerpt'] > 0) 
		{
			$text_with_placeholders = $content_no_tags;
			foreach($allowed_tags as $alw => $empt)
			{
				$placeholder = '[' . $alw . ']';
				$text_with_placeholders = str_replace('<' . $alw . '>', $placeholder, $text_with_placeholders);
			}
			$truncated_text_with_placeholders = wp_trim_words($text_with_placeholders, $settings['excerpt']);
			$final_text = $truncated_text_with_placeholders;
			foreach($allowed_tags as $alw => $empt)
			{
				$placeholder = '[' . $alw . ']';
				$final_text = str_replace($placeholder, '<' . $alw . '>', $final_text);
			}
			return $final_text;
		} else {
			return $content_no_tags;
		}
	}

	private function convert_timezone($timestamp) {
	    $date = new DateTime($timestamp);

	    // Timezone string set (ie: America/New York)
	    if (get_option('timezone_string')) {
	        $timezone = get_option('timezone_string');
	    // GMT offset string set (ie: -5). Convert value to timezone string
	    } elseif (get_option('gmt_offset')) {
	        $timezone = timezone_name_from_abbr('', get_option('gmt_offset') * 3600, 0 );
	    } else {
	        $timezone = 'GMT';
	    }

	    try {
	        $date->setTimezone(new DateTimeZone($timezone)); 
	    } catch (Exception $e) {
	        $date->setTimezone(new DateTimeZone('GMT')); 
	    }

	    return date_i18n(get_option('date_format') .' - ' . get_option('time_format'), strtotime($date->format('Y-m-d H:i:s')));
	}
}
?>