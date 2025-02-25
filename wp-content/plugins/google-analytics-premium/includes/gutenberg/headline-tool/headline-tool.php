<?php

namespace MonsterInsightsHeadlineToolPlugin;

// setup defines
define( 'MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Headline Tool
 *
 * @since      0.1
 * @author     Debjit Saha
 */
class MonsterInsightsHeadlineToolPlugin {

	/**
	 * Class Variables.
	 */
	private $emotion_power_words = array();
	private $power_words = array();
	private $common_words = array();
	private $uncommon_words = array();

	/**
	 * Constructor
	 *
	 * @return   none
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * Add the necessary hooks and filters
	 */
	function init() {
		add_action( 'wp_ajax_monsterinsights_gutenberg_headline_analyzer_get_results', array( $this, 'get_result' ) );
	}

	/**
	 * Ajax request endpoint for the uptime check
	 */
	function get_result() {

		// csrf check
		if ( check_ajax_referer( 'monsterinsights_gutenberg_headline_nonce', false, false ) === false ) {
			$content = self::output_template( 'results-error.php' );
			wp_send_json_error(
				array(
					'html' => $content
				)
			);
		}

		// get whether or not the website is up
		$result = $this->get_headline_scores();

		if ( ! empty( $result->err ) ) {
			$content = self::output_template( 'results-error.php', $result );
			wp_send_json_error(
				array( 'html' => $content, 'analysed' => false )
			);
		} else {
			if(!isset($_REQUEST['q'])){
				wp_send_json_error(
					array( 'html' => '', 'analysed' => false )
				);
			}
			$q = (isset($_REQUEST['q'])) ? sanitize_text_field($_REQUEST['q']) : '';
			// send the response
			wp_send_json_success(
				array(
					'result'   => $result,
					'analysed' => ! $result->err,
					'sentence' => ucwords( wp_unslash( $q ) ),
					'score'    => ( isset( $result->score ) && ! empty( $result->score ) ) ? $result->score : 0
				)
			);

		}
	}

	/**
	 * function to match words from sentence
	 * @return Object
	 */
	function match_words( $sentence, $sentence_split, $words ) {
		$ret = array();
		foreach ( $words as $wrd ) {
			// check if $wrd is a phrase
			if ( strpos( $wrd, ' ' ) !== false ) {
				$word_position = strpos( $sentence, $wrd );

				// Word not found in the sentence.
				if ( $word_position === false ) {
					continue;
				}

				// Check this is the end of the sentence.
				$is_end = strlen( $sentence ) === $word_position + 1;

				// Check the next character is a space.
				$is_space = " " === substr( $sentence, $word_position + strlen( $wrd ), 1 );

				// If it is a phrase then the next character must end of sentence or a space.
				if ( $is_end || $is_space ) {
					$ret[] = $wrd;
				}
			} // if $wrd is a single word
			else {
				if ( in_array( $wrd, $sentence_split ) ) {
					$ret[] = $wrd;
				}
			}
		}

		return $ret;
	}

	/**
	 * main function to calculate headline scores
	 * @return Object
	 */
	function get_headline_scores() {
		$input = (isset($_REQUEST['q'])) ? sanitize_text_field($_REQUEST['q']) : '';

		// init the result array
		$result                   = new \stdClass();
		$result->input_array_orig = explode( ' ', wp_unslash( $input ) );

		// strip useless characters
		$input = preg_replace( '/[^A-Za-z0-9 ]/', '', $input );

		// strip whitespace
		$input = preg_replace( '!\s+!', ' ', $input );

		// lower case
		$input = strtolower( $input );

		$result->input = $input;

		// bad input
		if ( ! $input || $input == ' ' || trim( $input ) == '' ) {
			$result->err = true;
			$result->msg = __( 'Bad Input', 'ga-premium' );

			return $result;
		}

		// overall score;
		$scoret = 0;

		// headline array
		$input_array = explode( ' ', $input );

		$result->input_array = $input_array;

		// all okay, start analysis
		$result->err = false;

		// Length - 55 chars. optimal
		$result->length = strlen( str_replace( ' ', '', $input ) );
		$scoret         = $scoret + 3;

		if ( $result->length <= 19 ) {
			$scoret += 5;
		} elseif ( $result->length >= 20 && $result->length <= 34 ) {
			$scoret += 8;
		} elseif ( $result->length >= 35 && $result->length <= 66 ) {
			$scoret += 11;
		} elseif ( $result->length >= 67 && $result->length <= 79 ) {
			$scoret += 8;
		} elseif ( $result->length >= 80 ) {
			$scoret += 5;
		}

		// Count - typically 6-7 words
		$result->word_count = count( $input_array );
		$scoret             = $scoret + 3;

		if ( $result->word_count == 0 ) {
			$scoret = 0;
		} else if ( $result->word_count >= 2 && $result->word_count <= 4 ) {
			$scoret += 5;
		} elseif ( $result->word_count >= 5 && $result->word_count <= 9 ) {
			$scoret += 11;
		} elseif ( $result->word_count >= 10 && $result->word_count <= 11 ) {
			$scoret += 8;
		} elseif ( $result->word_count >= 12 ) {
			$scoret += 5;
		}

		// Calculate word match counts
		$result->power_words        = $this->match_words( $result->input, $result->input_array, $this->power_words() );
		$result->power_words_per    = count( $result->power_words ) / $result->word_count;
		$result->emotion_words      = $this->match_words( $result->input, $result->input_array, $this->emotion_power_words() );
		$result->emotion_words_per  = count( $result->emotion_words ) / $result->word_count;
		$result->common_words       = $this->match_words( $result->input, $result->input_array, $this->common_words() );
		$result->common_words_per   = count( $result->common_words ) / $result->word_count;
		$result->uncommon_words     = $this->match_words( $result->input, $result->input_array, $this->uncommon_words() );
		$result->uncommon_words_per = count( $result->uncommon_words ) / $result->word_count;
		$result->word_balance       = __( 'Can Be Improved', 'ga-premium' );
		$result->word_balance_use   = array();

		if ( $result->emotion_words_per < 0.1 ) {
			$result->word_balance_use[] = __( 'emotion', 'ga-premium' );
		} else {
			$scoret = $scoret + 15;
		}

		if ( $result->common_words_per < 0.2 ) {
			$result->word_balance_use[] = __( 'common', 'ga-premium' );
		} else {
			$scoret = $scoret + 11;
		}

		if ( $result->uncommon_words_per < 0.1 ) {
			$result->word_balance_use[] = __( 'uncommon', 'ga-premium' );
		} else {
			$scoret = $scoret + 15;
		}

		if ( count( $result->power_words ) < 1 ) {
			$result->word_balance_use[] = __( 'power', 'ga-premium' );
		} else {
			$scoret = $scoret + 19;
		}

		if (
			$result->emotion_words_per >= 0.1 &&
			$result->common_words_per >= 0.2 &&
			$result->uncommon_words_per >= 0.1 &&
			count( $result->power_words ) >= 1 ) {
			$result->word_balance = __( 'Perfect', 'ga-premium' );
			$scoret               = $scoret + 3;
		}

		// Sentiment analysis also look - https://github.com/yooper/php-text-analysis

		// Emotion of the headline - sentiment analysis
		// Credits - https://github.com/JWHennessey/phpInsight/
		require_once MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH . '/phpinsight/autoload.php';
		$sentiment         = new \PHPInsight\Sentiment();
		$class_senti       = $sentiment->categorise( $input );
		$result->sentiment = $class_senti;

		$scoret = $scoret + ( $result->sentiment === 'pos' ? 10 : ( $result->sentiment === 'neg' ? 10 : 7 ) );

		// Headline types
		$headline_types = array();

		// HDL type: how to, how-to, howto
		if ( strpos( $input, __( 'how to', 'ga-premium' ) ) !== false || strpos( $input, __( 'howto', 'ga-premium' ) ) !== false ) {
			$headline_types[] = __( 'How-To', 'ga-premium' );
			$scoret           = $scoret + 7;
		}

		// HDL type: numbers - numeric and alpha
		$num_quantifiers = array(
			__( 'one', 'ga-premium' ),
			__( 'two', 'ga-premium' ),
			__( 'three', 'ga-premium' ),
			__( 'four', 'ga-premium' ),
			__( 'five', 'ga-premium' ),
			__( 'six', 'ga-premium' ),
			__( 'seven', 'ga-premium' ),
			__( 'eight', 'ga-premium' ),
			__( 'nine', 'ga-premium' ),
			__( 'eleven', 'ga-premium' ),
			__( 'twelve', 'ga-premium' ),
			__( 'thirt', 'ga-premium' ),
			__( 'fift', 'ga-premium' ),
			__( 'hundred', 'ga-premium' ),
			__( 'thousand', 'ga-premium' ),
		);

		$list_words = array_intersect( $input_array, $num_quantifiers );
		if ( preg_match( '~[0-9]+~', $input ) || ! empty ( $list_words ) ) {
			$headline_types[] = __( 'List', 'ga-premium' );
			$scoret           = $scoret + 7;
		}

		// HDL type: Question
		$qn_quantifiers     = array(
			__( 'where', 'ga-premium' ),
			__( 'when', 'ga-premium' ),
			__( 'how', 'ga-premium' ),
			__( 'what', 'ga-premium' ),
			__( 'have', 'ga-premium' ),
			__( 'has', 'ga-premium' ),
			__( 'does', 'ga-premium' ),
			__( 'do', 'ga-premium' ),
			__( 'can', 'ga-premium' ),
			__( 'are', 'ga-premium' ),
			__( 'will', 'ga-premium' ),
		);
		$qn_quantifiers_sub = array(
			__( 'you', 'ga-premium' ),
			__( 'they', 'ga-premium' ),
			__( 'he', 'ga-premium' ),
			__( 'she', 'ga-premium' ),
			__( 'your', 'ga-premium' ),
			__( 'it', 'ga-premium' ),
			__( 'they', 'ga-premium' ),
			__( 'my', 'ga-premium' ),
			__( 'have', 'ga-premium' ),
			__( 'has', 'ga-premium' ),
			__( 'does', 'ga-premium' ),
			__( 'do', 'ga-premium' ),
			__( 'can', 'ga-premium' ),
			__( 'are', 'ga-premium' ),
			__( 'will', 'ga-premium' ),
		);
		if ( in_array( $input_array[0], $qn_quantifiers ) ) {
			if ( in_array( $input_array[1], $qn_quantifiers_sub ) ) {
				$headline_types[] = __( 'Question', 'ga-premium' );
				$scoret           = $scoret + 7;
			}
		}

		// General headline type
		if ( empty( $headline_types ) ) {
			$headline_types[] = __( 'General', 'ga-premium' );
			$scoret           = $scoret + 5;
		}

		// put to result
		$result->headline_types = $headline_types;

		// Resources for more reading:
		// https://kopywritingkourse.com/copywriting-headlines-that-sell/
		// How To _______ That Will Help You ______
		// https://coschedule.com/blog/how-to-write-the-best-headlines-that-will-increase-traffic/

		$result->score = $scoret >= 93 ? 93 : $scoret;

		return $result;
	}

	/**
	 * Output template contents
	 *
	 * @param $template String template file name
	 *
	 * @return String template content
	 */
	static function output_template( $template, $result = '', $theme = '' ) {
		ob_start();
		require MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH . '' . $template;
		$tmp = ob_get_contents();
		ob_end_clean();

		return $tmp;
	}

	/**
	 * Get User IP
	 *
	 * Returns the IP address of the current visitor
	 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/blob/904db487f6c07a3a46903202d31d4e8ea2b30808/includes/misc-functions.php#L163
	 * @return string $ip User's IP address
	 */
	static function get_ip() {

		$ip = '127.0.0.1';

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
		}

		// Fix potential CSV returned from $_SERVER variables
		$ip_array = explode( ',', $ip );
		$ip_array = array_map( 'trim', $ip_array );

		return $ip_array[0];
	}

	/**
	 * Emotional power words
	 *
	 * @return array emotional power words
	 */
	function emotion_power_words() {
		if ( isset( $this->emotion_power_words ) && ! empty( $this->emotion_power_words ) ) {
			return $this->emotion_power_words;
		}

		$this->emotion_power_words = array(
			__( "destroy", "ga-premium" ),
			__( "extra", "ga-premium" ),
			__( "in a", "ga-premium" ),
			__( "devastating", "ga-premium" ),
			__( "eye-opening", "ga-premium" ),
			__( "gift", "ga-premium" ),
			__( "in the world", "ga-premium" ),
			__( "devoted", "ga-premium" ),
			__( "fail", "ga-premium" ),
			__( "in the", "ga-premium" ),
			__( "faith", "ga-premium" ),
			__( "grateful", "ga-premium" ),
			__( "inexpensive", "ga-premium" ),
			__( "dirty", "ga-premium" ),
			__( "famous", "ga-premium" ),
			__( "disastrous", "ga-premium" ),
			__( "fantastic", "ga-premium" ),
			__( "greed", "ga-premium" ),
			__( "grit", "ga-premium" ),
			__( "insanely", "ga-premium" ),
			__( "disgusting", "ga-premium" ),
			__( "fearless", "ga-premium" ),
			__( "disinformation", "ga-premium" ),
			__( "feast", "ga-premium" ),
			__( "insidious", "ga-premium" ),
			__( "dollar", "ga-premium" ),
			__( "feeble", "ga-premium" ),
			__( "gullible", "ga-premium" ),
			__( "double", "ga-premium" ),
			__( "fire", "ga-premium" ),
			__( "hack", "ga-premium" ),
			__( "fleece", "ga-premium" ),
			__( "had enough", "ga-premium" ),
			__( "invasion", "ga-premium" ),
			__( "drowning", "ga-premium" ),
			__( "floundering", "ga-premium" ),
			__( "happy", "ga-premium" ),
			__( "ironclad", "ga-premium" ),
			__( "dumb", "ga-premium" ),
			__( "flush", "ga-premium" ),
			__( "hate", "ga-premium" ),
			__( "irresistibly", "ga-premium" ),
			__( "hazardous", "ga-premium" ),
			__( "is the", "ga-premium" ),
			__( "fool", "ga-premium" ),
			__( "is what happens when", "ga-premium" ),
			__( "fooled", "ga-premium" ),
			__( "helpless", "ga-premium" ),
			__( "it looks like a", "ga-premium" ),
			__( "embarrass", "ga-premium" ),
			__( "for the first time", "ga-premium" ),
			__( "help are the", "ga-premium" ),
			__( "jackpot", "ga-premium" ),
			__( "forbidden", "ga-premium" ),
			__( "hidden", "ga-premium" ),
			__( "jail", "ga-premium" ),
			__( "empower", "ga-premium" ),
			__( "force-fed", "ga-premium" ),
			__( "high", "ga-premium" ),
			__( "jaw-dropping", "ga-premium" ),
			__( "forgotten", "ga-premium" ),
			__( "jeopardy", "ga-premium" ),
			__( "energize", "ga-premium" ),
			__( "hoax", "ga-premium" ),
			__( "jubilant", "ga-premium" ),
			__( "foul", "ga-premium" ),
			__( "hope", "ga-premium" ),
			__( "killer", "ga-premium" ),
			__( "frantic", "ga-premium" ),
			__( "horrific", "ga-premium" ),
			__( "know it all", "ga-premium" ),
			__( "epic", "ga-premium" ),
			__( "how to make", "ga-premium" ),
			__( "evil", "ga-premium" ),
			__( "freebie", "ga-premium" ),
			__( "frenzy", "ga-premium" ),
			__( "hurricane", "ga-premium" ),
			__( "excited", "ga-premium" ),
			__( "fresh on the mind", "ga-premium" ),
			__( "frightening", "ga-premium" ),
			__( "hypnotic", "ga-premium" ),
			__( "lawsuit", "ga-premium" ),
			__( "frugal", "ga-premium" ),
			__( "illegal", "ga-premium" ),
			__( "fulfill", "ga-premium" ),
			__( "lick", "ga-premium" ),
			__( "explode", "ga-premium" ),
			__( "lies", "ga-premium" ),
			__( "exposed", "ga-premium" ),
			__( "gambling", "ga-premium" ),
			__( "like a normal", "ga-premium" ),
			__( "nightmare", "ga-premium" ),
			__( "results", "ga-premium" ),
			__( "line", "ga-premium" ),
			__( "no good", "ga-premium" ),
			__( "pound", "ga-premium" ),
			__( "loathsome", "ga-premium" ),
			__( "no questions asked", "ga-premium" ),
			__( "revenge", "ga-premium" ),
			__( "lonely", "ga-premium" ),
			__( "looks like a", "ga-premium" ),
			__( "obnoxious", "ga-premium" ),
			__( "preposterous", "ga-premium" ),
			__( "revolting", "ga-premium" ),
			__( "looming", "ga-premium" ),
			__( "priced", "ga-premium" ),
			__( "lost", "ga-premium" ),
			__( "prison", "ga-premium" ),
			__( "lowest", "ga-premium" ),
			__( "of the", "ga-premium" ),
			__( "privacy", "ga-premium" ),
			__( "rich", "ga-premium" ),
			__( "lunatic", "ga-premium" ),
			__( "off-limits", "ga-premium" ),
			__( "private", "ga-premium" ),
			__( "risky", "ga-premium" ),
			__( "lurking", "ga-premium" ),
			__( "offer", "ga-premium" ),
			__( "prize", "ga-premium" ),
			__( "ruthless", "ga-premium" ),
			__( "lust", "ga-premium" ),
			__( "official", "ga-premium" ),
			__( "luxurious", "ga-premium" ),
			__( "on the", "ga-premium" ),
			__( "profit", "ga-premium" ),
			__( "scary", "ga-premium" ),
			__( "lying", "ga-premium" ),
			__( "outlawed", "ga-premium" ),
			__( "protected", "ga-premium" ),
			__( "scream", "ga-premium" ),
			__( "searing", "ga-premium" ),
			__( "overcome", "ga-premium" ),
			__( "provocative", "ga-premium" ),
			__( "make you", "ga-premium" ),
			__( "painful", "ga-premium" ),
			__( "pummel", "ga-premium" ),
			__( "secure", "ga-premium" ),
			__( "pale", "ga-premium" ),
			__( "punish", "ga-premium" ),
			__( "marked down", "ga-premium" ),
			__( "panic", "ga-premium" ),
			__( "quadruple", "ga-premium" ),
			__( "secutively", "ga-premium" ),
			__( "massive", "ga-premium" ),
			__( "pay zero", "ga-premium" ),
			__( "seize", "ga-premium" ),
			__( "meltdown", "ga-premium" ),
			__( "payback", "ga-premium" ),
			__( "might look like a", "ga-premium" ),
			__( "peril", "ga-premium" ),
			__( "mind-blowing", "ga-premium" ),
			__( "shameless", "ga-premium" ),
			__( "minute", "ga-premium" ),
			__( "rave", "ga-premium" ),
			__( "shatter", "ga-premium" ),
			__( "piranha", "ga-premium" ),
			__( "reckoning", "ga-premium" ),
			__( "shellacking", "ga-premium" ),
			__( "mired", "ga-premium" ),
			__( "pitfall", "ga-premium" ),
			__( "reclaim", "ga-premium" ),
			__( "mistakes", "ga-premium" ),
			__( "plague", "ga-premium" ),
			__( "sick and tired", "ga-premium" ),
			__( "money", "ga-premium" ),
			__( "played", "ga-premium" ),
			__( "refugee", "ga-premium" ),
			__( "silly", "ga-premium" ),
			__( "money-grubbing", "ga-premium" ),
			__( "pluck", "ga-premium" ),
			__( "refund", "ga-premium" ),
			__( "moneyback", "ga-premium" ),
			__( "plummet", "ga-premium" ),
			__( "plunge", "ga-premium" ),
			__( "murder", "ga-premium" ),
			__( "pointless", "ga-premium" ),
			__( "sinful", "ga-premium" ),
			__( "myths", "ga-premium" ),
			__( "poor", "ga-premium" ),
			__( "remarkably", "ga-premium" ),
			__( "six-figure", "ga-premium" ),
			__( "never again", "ga-premium" ),
			__( "research", "ga-premium" ),
			__( "surrender", "ga-premium" ),
			__( "to the", "ga-premium" ),
			__( "varify", "ga-premium" ),
			__( "skyrocket", "ga-premium" ),
			__( "toxic", "ga-premium" ),
			__( "vibrant", "ga-premium" ),
			__( "slaughter", "ga-premium" ),
			__( "swindle", "ga-premium" ),
			__( "trap", "ga-premium" ),
			__( "victim", "ga-premium" ),
			__( "sleazy", "ga-premium" ),
			__( "taboo", "ga-premium" ),
			__( "treasure", "ga-premium" ),
			__( "victory", "ga-premium" ),
			__( "smash", "ga-premium" ),
			__( "tailspin", "ga-premium" ),
			__( "vindication", "ga-premium" ),
			__( "smug", "ga-premium" ),
			__( "tank", "ga-premium" ),
			__( "triple", "ga-premium" ),
			__( "viral", "ga-premium" ),
			__( "smuggled", "ga-premium" ),
			__( "tantalizing", "ga-premium" ),
			__( "triumph", "ga-premium" ),
			__( "volatile", "ga-premium" ),
			__( "sniveling", "ga-premium" ),
			__( "targeted", "ga-premium" ),
			__( "truth", "ga-premium" ),
			__( "vulnerable", "ga-premium" ),
			__( "snob", "ga-premium" ),
			__( "tawdry", "ga-premium" ),
			__( "try before you buy", "ga-premium" ),
			__( "tech", "ga-premium" ),
			__( "turn the tables", "ga-premium" ),
			__( "wanton", "ga-premium" ),
			__( "soaring", "ga-premium" ),
			__( "warning", "ga-premium" ),
			__( "teetering", "ga-premium" ),
			__( "unauthorized", "ga-premium" ),
			__( "spectacular", "ga-premium" ),
			__( "temporary fix", "ga-premium" ),
			__( "unbelievably", "ga-premium" ),
			__( "spine", "ga-premium" ),
			__( "tempting", "ga-premium" ),
			__( "uncommonly", "ga-premium" ),
			__( "what happened", "ga-premium" ),
			__( "spirit", "ga-premium" ),
			__( "what happens when", "ga-premium" ),
			__( "terror", "ga-premium" ),
			__( "under", "ga-premium" ),
			__( "what happens", "ga-premium" ),
			__( "staggering", "ga-premium" ),
			__( "underhanded", "ga-premium" ),
			__( "what this", "ga-premium" ),
			__( "that will make you", "ga-premium" ),
			__( "undo", "when you see", "ga-premium" ),
			__( "that will make", "ga-premium" ),
			__( "unexpected", "ga-premium" ),
			__( "when you", "ga-premium" ),
			__( "strangle", "ga-premium" ),
			__( "that will", "ga-premium" ),
			__( "whip", "ga-premium" ),
			__( "the best", "ga-premium" ),
			__( "whopping", "ga-premium" ),
			__( "stuck up", "ga-premium" ),
			__( "the ranking of", "ga-premium" ),
			__( "wicked", "ga-premium" ),
			__( "stunning", "ga-premium" ),
			__( "the most", "ga-premium" ),
			__( "will make you", "ga-premium" ),
			__( "stupid", "ga-premium" ),
			__( "the reason why is", "ga-premium" ),
			__( "unscrupulous", "ga-premium" ),
			__( "thing ive ever seen", "ga-premium" ),
			__( "withheld", "ga-premium" ),
			__( "this is the", "ga-premium" ),
			__( "this is what happens", "ga-premium" ),
			__( "unusually", "ga-premium" ),
			__( "wondrous", "ga-premium" ),
			__( "this is what", "ga-premium" ),
			__( "uplifting", "ga-premium" ),
			__( "worry", "ga-premium" ),
			__( "sure", "ga-premium" ),
			__( "this is", "ga-premium" ),
			__( "wounded", "ga-premium" ),
			__( "surge", "ga-premium" ),
			__( "thrilled", "ga-premium" ),
			__( "you need to know", "ga-premium" ),
			__( "thrilling", "ga-premium" ),
			__( "valor", "ga-premium" ),
			__( "you need to", "ga-premium" ),
			__( "you see what", "ga-premium" ),
			__( "surprising", "ga-premium" ),
			__( "tired", "ga-premium" ),
			__( "you see", "ga-premium" ),
			__( "surprisingly", "ga-premium" ),
			__( "to be", "ga-premium" ),
			__( "vaporize", "ga-premium" ),
		);

		return $this->emotion_power_words;
	}

	/**
	 * Power words
	 *
	 * @return array power words
	 */
	function power_words() {
		if ( isset( $this->power_words ) && ! empty( $this->power_words ) ) {
			return $this->power_words;
		}

		$this->power_words = array(
			__( "great", "ga-premium" ),
			__( "free", "ga-premium" ),
			__( "focus", "ga-premium" ),
			__( "remarkable", "ga-premium" ),
			__( "confidential", "ga-premium" ),
			__( "sale", "ga-premium" ),
			__( "wanted", "ga-premium" ),
			__( "obsession", "ga-premium" ),
			__( "sizable", "ga-premium" ),
			__( "new", "ga-premium" ),
			__( "absolutely lowest", "ga-premium" ),
			__( "surging", "ga-premium" ),
			__( "wonderful", "ga-premium" ),
			__( "professional", "ga-premium" ),
			__( "interesting", "ga-premium" ),
			__( "revisited", "ga-premium" ),
			__( "delivered", "ga-premium" ),
			__( "guaranteed", "ga-premium" ),
			__( "challenge", "ga-premium" ),
			__( "unique", "ga-premium" ),
			__( "secrets", "ga-premium" ),
			__( "special", "ga-premium" ),
			__( "lifetime", "ga-premium" ),
			__( "bargain", "ga-premium" ),
			__( "scarce", "ga-premium" ),
			__( "tested", "ga-premium" ),
			__( "highest", "ga-premium" ),
			__( "hurry", "ga-premium" ),
			__( "alert famous", "ga-premium" ),
			__( "improved", "ga-premium" ),
			__( "expert", "ga-premium" ),
			__( "daring", "ga-premium" ),
			__( "strong", "ga-premium" ),
			__( "immediately", "ga-premium" ),
			__( "advice", "ga-premium" ),
			__( "pioneering", "ga-premium" ),
			__( "unusual", "ga-premium" ),
			__( "limited", "ga-premium" ),
			__( "the truth about", "ga-premium" ),
			__( "destiny", "ga-premium" ),
			__( "outstanding", "ga-premium" ),
			__( "simplistic", "ga-premium" ),
			__( "compare", "ga-premium" ),
			__( "unsurpassed", "ga-premium" ),
			__( "energy", "ga-premium" ),
			__( "powerful", "ga-premium" ),
			__( "colorful", "ga-premium" ),
			__( "genuine", "ga-premium" ),
			__( "instructive", "ga-premium" ),
			__( "big", "ga-premium" ),
			__( "affordable", "ga-premium" ),
			__( "informative", "ga-premium" ),
			__( "liberal", "ga-premium" ),
			__( "popular", "ga-premium" ),
			__( "ultimate", "ga-premium" ),
			__( "mainstream", "ga-premium" ),
			__( "rare", "ga-premium" ),
			__( "exclusive", "ga-premium" ),
			__( "willpower", "ga-premium" ),
			__( "complete", "ga-premium" ),
			__( "edge", "ga-premium" ),
			__( "valuable", "ga-premium" ),
			__( "attractive", "ga-premium" ),
			__( "last chance", "ga-premium" ),
			__( "superior", "ga-premium" ),
			__( "how to", "ga-premium" ),
			__( "easily", "ga-premium" ),
			__( "exploit", "ga-premium" ),
			__( "unparalleled", "ga-premium" ),
			__( "endorsed", "ga-premium" ),
			__( "approved", "ga-premium" ),
			__( "quality", "ga-premium" ),
			__( "fascinating", "ga-premium" ),
			__( "unlimited", "ga-premium" ),
			__( "competitive", "ga-premium" ),
			__( "gigantic", "ga-premium" ),
			__( "compromise", "ga-premium" ),
			__( "discount", "ga-premium" ),
			__( "full", "ga-premium" ),
			__( "love", "ga-premium" ),
			__( "odd", "ga-premium" ),
			__( "fundamentals", "ga-premium" ),
			__( "mammoth", "ga-premium" ),
			__( "lavishly", "ga-premium" ),
			__( "bottom line", "ga-premium" ),
			__( "under priced", "ga-premium" ),
			__( "innovative", "ga-premium" ),
			__( "reliable", "ga-premium" ),
			__( "zinger", "ga-premium" ),
			__( "suddenly", "ga-premium" ),
			__( "it's here", "ga-premium" ),
			__( "terrific", "ga-premium" ),
			__( "simplified", "ga-premium" ),
			__( "perspective", "ga-premium" ),
			__( "just arrived", "ga-premium" ),
			__( "breakthrough", "ga-premium" ),
			__( "tremendous", "ga-premium" ),
			__( "launching", "ga-premium" ),
			__( "sure fire", "ga-premium" ),
			__( "emerging", "ga-premium" ),
			__( "helpful", "ga-premium" ),
			__( "skill", "ga-premium" ),
			__( "soar", "ga-premium" ),
			__( "profitable", "ga-premium" ),
			__( "special offer", "ga-premium" ),
			__( "reduced", "ga-premium" ),
			__( "beautiful", "ga-premium" ),
			__( "sampler", "ga-premium" ),
			__( "technology", "ga-premium" ),
			__( "better", "ga-premium" ),
			__( "crammed", "ga-premium" ),
			__( "noted", "ga-premium" ),
			__( "selected", "ga-premium" ),
			__( "shrewd", "ga-premium" ),
			__( "growth", "ga-premium" ),
			__( "luxury", "ga-premium" ),
			__( "sturdy", "ga-premium" ),
			__( "enormous", "ga-premium" ),
			__( "promising", "ga-premium" ),
			__( "unconditional", "ga-premium" ),
			__( "wealth", "ga-premium" ),
			__( "spotlight", "ga-premium" ),
			__( "astonishing", "ga-premium" ),
			__( "timely", "ga-premium" ),
			__( "successful", "ga-premium" ),
			__( "useful", "ga-premium" ),
			__( "imagination", "ga-premium" ),
			__( "bonanza", "ga-premium" ),
			__( "opportunities", "ga-premium" ),
			__( "survival", "ga-premium" ),
			__( "greatest", "ga-premium" ),
			__( "security", "ga-premium" ),
			__( "last minute", "ga-premium" ),
			__( "largest", "ga-premium" ),
			__( "high tech", "ga-premium" ),
			__( "refundable", "ga-premium" ),
			__( "monumental", "ga-premium" ),
			__( "colossal", "ga-premium" ),
			__( "latest", "ga-premium" ),
			__( "quickly", "ga-premium" ),
			__( "startling", "ga-premium" ),
			__( "now", "ga-premium" ),
			__( "important", "ga-premium" ),
			__( "revolutionary", "ga-premium" ),
			__( "quick", "ga-premium" ),
			__( "unlock", "ga-premium" ),
			__( "urgent", "ga-premium" ),
			__( "miracle", "ga-premium" ),
			__( "easy", "ga-premium" ),
			__( "fortune", "ga-premium" ),
			__( "amazing", "ga-premium" ),
			__( "magic", "ga-premium" ),
			__( "direct", "ga-premium" ),
			__( "authentic", "ga-premium" ),
			__( "exciting", "ga-premium" ),
			__( "proven", "ga-premium" ),
			__( "simple", "ga-premium" ),
			__( "announcing", "ga-premium" ),
			__( "portfolio", "ga-premium" ),
			__( "reward", "ga-premium" ),
			__( "strange", "ga-premium" ),
			__( "huge gift", "ga-premium" ),
			__( "revealing", "ga-premium" ),
			__( "weird", "ga-premium" ),
			__( "value", "ga-premium" ),
			__( "introducing", "ga-premium" ),
			__( "sensational", "ga-premium" ),
			__( "surprise", "ga-premium" ),
			__( "insider", "ga-premium" ),
			__( "practical", "ga-premium" ),
			__( "excellent", "ga-premium" ),
			__( "delighted", "ga-premium" ),
			__( "download", "ga-premium" ),
		);

		return $this->power_words;
	}

	/**
	 * Common words
	 *
	 * @return array common words
	 */
	function common_words() {
		if ( isset( $this->common_words ) && ! empty( $this->common_words ) ) {
			return $this->common_words;
		}

		$this->common_words = array(
			__( "a", "ga-premium" ),
			__( "for", "ga-premium" ),
			__( "about", "ga-premium" ),
			__( "from", "ga-premium" ),
			__( "after", "ga-premium" ),
			__( "get", "ga-premium" ),
			__( "all", "ga-premium" ),
			__( "has", "ga-premium" ),
			__( "an", "ga-premium" ),
			__( "have", "ga-premium" ),
			__( "and", "ga-premium" ),
			__( "he", "ga-premium" ),
			__( "are", "ga-premium" ),
			__( "her", "ga-premium" ),
			__( "as", "ga-premium" ),
			__( "his", "ga-premium" ),
			__( "at", "ga-premium" ),
			__( "how", "ga-premium" ),
			__( "be", "ga-premium" ),
			__( "I", "ga-premium" ),
			__( "but", "ga-premium" ),
			__( "if", "ga-premium" ),
			__( "by", "ga-premium" ),
			__( "in", "ga-premium" ),
			__( "can", "ga-premium" ),
			__( "is", "ga-premium" ),
			__( "did", "ga-premium" ),
			__( "it", "ga-premium" ),
			__( "do", "ga-premium" ),
			__( "just", "ga-premium" ),
			__( "ever", "ga-premium" ),
			__( "like", "ga-premium" ),
			__( "ll", "ga-premium" ),
			__( "these", "ga-premium" ),
			__( "me", "ga-premium" ),
			__( "they", "ga-premium" ),
			__( "most", "ga-premium" ),
			__( "things", "ga-premium" ),
			__( "my", "ga-premium" ),
			__( "this", "ga-premium" ),
			__( "no", "ga-premium" ),
			__( "to", "ga-premium" ),
			__( "not", "ga-premium" ),
			__( "up", "ga-premium" ),
			__( "of", "ga-premium" ),
			__( "was", "ga-premium" ),
			__( "on", "ga-premium" ),
			__( "what", "ga-premium" ),
			__( "re", "ga-premium" ),
			__( "when", "ga-premium" ),
			__( "she", "ga-premium" ),
			__( "who", "ga-premium" ),
			__( "sould", "ga-premium" ),
			__( "why", "ga-premium" ),
			__( "so", "ga-premium" ),
			__( "will", "ga-premium" ),
			__( "that", "ga-premium" ),
			__( "with", "ga-premium" ),
			__( "the", "ga-premium" ),
			__( "you", "ga-premium" ),
			__( "their", "ga-premium" ),
			__( "your", "ga-premium" ),
			__( "there", "ga-premium" ),
		);

		return $this->common_words;
	}


	/**
	 * Uncommon words
	 *
	 * @return array uncommon words
	 */
	function uncommon_words() {
		if ( isset( $this->uncommon_words ) && ! empty( $this->uncommon_words ) ) {
			return $this->uncommon_words;
		}

		$this->uncommon_words = array(
			__( "actually", "ga-premium" ),
			__( "happened", "ga-premium" ),
			__( "need", "ga-premium" ),
			__( "thing", "ga-premium" ),
			__( "awesome", "ga-premium" ),
			__( "heart", "ga-premium" ),
			__( "never", "ga-premium" ),
			__( "think", "ga-premium" ),
			__( "baby", "ga-premium" ),
			__( "here", "ga-premium" ),
			__( "new", "ga-premium" ),
			__( "time", "ga-premium" ),
			__( "beautiful", "ga-premium" ),
			__( "its", "ga-premium" ),
			__( "now", "ga-premium" ),
			__( "valentines", "ga-premium" ),
			__( "being", "ga-premium" ),
			__( "know", "ga-premium" ),
			__( "old", "ga-premium" ),
			__( "video", "ga-premium" ),
			__( "best", "ga-premium" ),
			__( "life", "ga-premium" ),
			__( "one", "ga-premium" ),
			__( "want", "ga-premium" ),
			__( "better", "ga-premium" ),
			__( "little", "ga-premium" ),
			__( "out", "ga-premium" ),
			__( "watch", "ga-premium" ),
			__( "boy", "ga-premium" ),
			__( "look", "ga-premium" ),
			__( "people", "ga-premium" ),
			__( "way", "ga-premium" ),
			__( "dog", "ga-premium" ),
			__( "love", "ga-premium" ),
			__( "photos", "ga-premium" ),
			__( "ways", "ga-premium" ),
			__( "down", "ga-premium" ),
			__( "made", "ga-premium" ),
			__( "really", "ga-premium" ),
			__( "world", "ga-premium" ),
			__( "facebook", "ga-premium" ),
			__( "make", "ga-premium" ),
			__( "reasons", "ga-premium" ),
			__( "year", "ga-premium" ),
			__( "first", "ga-premium" ),
			__( "makes", "ga-premium" ),
			__( "right", "ga-premium" ),
			__( "years", "ga-premium" ),
			__( "found", "ga-premium" ),
			__( "man", "ga-premium" ),
			__( "see", "ga-premium" ),
			__( "you'll", "ga-premium" ),
			__( "girl", "ga-premium" ),
			__( "media", "ga-premium" ),
			__( "seen", "ga-premium" ),
			__( "good", "ga-premium" ),
			__( "mind", "ga-premium" ),
			__( "social", "ga-premium" ),
			__( "guy", "ga-premium" ),
			__( "more", "ga-premium" ),
			__( "something", "ga-premium" ),
		);

		return $this->uncommon_words;
	}
}

new MonsterInsightsHeadlineToolPlugin();
