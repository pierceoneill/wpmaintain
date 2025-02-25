<?php
class Echo_RSS_Feed
{
	public static $cacheExpire = '1 day';

	public static $cacheDir;

	public static $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36';

	protected $xml;

	public static function load($url, $user = null, $pass = null, $use_proxy = '0')
	{
		$xml = self::loadXml($url, $user, $pass, $use_proxy);
		if ($xml->channel) {
			return self::fromRss($xml);
		} else {
			return self::fromAtom($xml);
		}
	}

	public static function loadRss($url, $user = null, $pass = null, $use_proxy = '0')
	{
		return self::fromRss(self::loadXml($url, $user, $pass, $use_proxy));
	}

	public static function loadAtom($url, $user = null, $pass = null, $use_proxy = '0')
	{
		return self::fromAtom(self::loadXml($url, $user, $pass, $use_proxy));
	}

	private static function fromRss(SimpleXMLElement $xml)
	{
		if (!$xml->channel) {
			throw new Echo_FeedException('Invalid RSS feed.');
		}

		self::adjustNamespaces($xml);

		foreach ($xml->channel->item as $item) {
			// converts namespaces to dotted tags
			self::adjustNamespaces($item);

			// generate 'url' & 'timestamp' tags
			$item->url = (string) $item->link;
			if (isset($item->{'dc:date'})) {
				$item->timestamp = strtotime($item->{'dc:date'});
			} elseif (isset($item->pubDate)) {
				$item->timestamp = strtotime($item->pubDate);
			}
		}
		$feed = new self;
		$feed->xml = $xml->channel;
		return $feed;
	}

	private static function fromAtom(SimpleXMLElement $xml)
	{
		if (!in_array('http://www.w3.org/2005/Atom', $xml->getDocNamespaces(), true)
			&& !in_array('http://purl.org/atom/ns#', $xml->getDocNamespaces(), true)
		) {
			throw new Echo_FeedException('Invalid Atom feed.');
		}

		// generate 'url' & 'timestamp' tags
		foreach ($xml->entry as $entry) {
			$entry->url = (string) $entry->link['href'];
			$entry->timestamp = strtotime($entry->updated);
		}
		$feed = new self;
		$feed->xml = $xml;
		return $feed;
	}

	public function __get($name)
	{
		return $this->xml->{$name};
	}

	public function __set($name, $value)
	{
		throw new Exception("Cannot assign to a read-only property '$name'.");
	}

	public function toArray(SimpleXMLElement $xml = null)
	{
		if ($xml === null) {
			$xml = $this->xml;
		}

		if (!$xml->children()) {
			return (string) $xml;
		}

		$arr = [];
		foreach ($xml->children() as $tag => $child) {
			if (count($xml->$tag) === 1) {
				$arr[$tag] = $this->toArray($child);
			} else {
				$arr[$tag][] = $this->toArray($child);
			}
		}

		return $arr;
	}

	private static function loadXml($url, $user, $pass, $use_proxy)
	{
		$e = self::$cacheExpire;
		$cacheFile = self::$cacheDir . '/feed.' . md5(serialize(func_get_args())) . '.xml';
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
            include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
            wp_filesystem($creds);
        }
		error_reporting(0);
		if (self::$cacheDir && $wp_filesystem->exists($cacheFile)
			&& (time() - filemtime($cacheFile) <= (is_string($e) ? strtotime($e) - time() : $e))
			&& $data = $wp_filesystem->get_contents($cacheFile)
		) {
			// ok
		} elseif ($data = trim(self::httpRequest($url, $user, $pass, $use_proxy))) {
			if (self::$cacheDir) {
				$wp_filesystem->put_contents($cacheFile, $data);
			}
		} elseif (self::$cacheDir && $data = $wp_filesystem->get_contents($cacheFile)) {
			// ok
		} else {
            error_reporting(E_ALL);
			throw new Echo_FeedException('Cannot load feed.');
		}
		error_reporting(E_ALL);
		return new SimpleXMLElement($data, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA);
	}

	private static function httpRequest($url, $user, $pass, $use_proxy)
	{
		if (extension_loaded('curl')) {
			$curl = curl_init();
            if($curl === false)
            {
                throw new Echo_FeedException('Failed to initialize curl!');
            }
            $echo_Main_Settings = get_option('echo_Main_Settings', false);
            if ($use_proxy && isset($echo_Main_Settings['proxy_url']) && $echo_Main_Settings['proxy_url'] != '') {
                $prx = explode(',', $echo_Main_Settings['proxy_url']);
                $randomness = array_rand($prx);
                curl_setopt( $curl, CURLOPT_PROXY, trim($prx[$randomness]));
                if (isset($echo_Main_Settings['proxy_auth']) && $echo_Main_Settings['proxy_auth'] != '') 
                {
                    $prx_auth = explode(',', $echo_Main_Settings['proxy_auth']);
                    if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                    {
                        curl_setopt( $curl, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
                    }
                }
            }
            $user_agent_cust = get_transient( 'echo_user_agent_cust');
            if($user_agent_cust != '')
            {
                self::$userAgent = $user_agent_cust;
            }
            else
            {
                if (isset($echo_Main_Settings['clear_user_agent']) && $echo_Main_Settings['clear_user_agent'] == 'on') 
                {
                    self::$userAgent = '';
                }
            }
            $my_time = 20;
            if (isset($echo_Main_Settings['custom_feed_timeout']) && $echo_Main_Settings['custom_feed_timeout'] != '') {
                $my_time = $echo_Main_Settings['custom_feed_timeout'];
            }
                        
			curl_setopt($curl, CURLOPT_URL, $url);
			if ($user !== null || $pass !== null) {
				curl_setopt($curl, CURLOPT_USERPWD, "$user:$pass");
			}
			curl_setopt($curl, CURLOPT_USERAGENT, self::$userAgent);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, $my_time);
			curl_setopt($curl, CURLOPT_ENCODING, '');
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_REFERER, 'https://www.google.com/');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			$result = curl_exec($curl);
			if($result === false)
			{
				global $wp_filesystem;
				if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
					include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
					wp_filesystem($creds);
				}
				error_reporting(0);
				$httpr = $wp_filesystem->get_contents($url);
				error_reporting(E_ALL);
				return $httpr;
			}
            curl_close($curl);
			return $result;

		} else {
            global $wp_filesystem;
            if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                wp_filesystem($creds);
            }
            error_reporting(0);
			$httpr = $wp_filesystem->get_contents($url);
            error_reporting(E_ALL);
			return $httpr;
		}
	}

	private static function adjustNamespaces($el)
	{
		foreach ($el->getNamespaces(true) as $prefix => $ns) {
			$children = $el->children($ns);
			foreach ($children as $tag => $content) {
				$el->{$prefix . ':' . $tag} = $content;
			}
		}
	}
}

class Echo_FeedException extends Exception
{
}