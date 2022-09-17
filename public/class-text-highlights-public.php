<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Text_Highlights
 * @subpackage Text_Highlights/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Text_Highlights
 * @subpackage Text_Highlights/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Text_Highlights_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/text-highlights-public.css', array(), $this->version, 'all' );

	}

	
	function the_content_filter($the_content){
		
		$saved_types = get_option( 'applied_post_types' );
		$saved_types = ((is_array($saved_types))? $saved_types: []);

		$phrases = get_post_meta(get_post()->ID, "key_phrases", true);
		$duplicate_contents = $the_content;

		if(sizeof($saved_types) > 0){
			foreach($saved_types as $type){
				if ( is_singular( $type ) ) {
					if(is_array($phrases) && sizeof($phrases)>0){
						// Keyword matching
						foreach($phrases as $phrase){
							$duplicate_contents = preg_replace("/\b$phrase\b/u", "<strong>$phrase</strong>", $duplicate_contents);
						}
					}

					$words = array();
					$n_words = preg_match_all('/([a-zA-Z]|\xC3[\x80-\x96\x98-\xB6\xB8-\xBF]|\xC5[\x92\x93\xA0\xA1\xB8\xBD\xBE]){4,}/', sanitize_text_field($duplicate_contents ), $match_arr);
					$words = $match_arr[0];
					$words = array_count_values($words);
					
					if(is_array($words) && sizeof($words) > 0){
						$max_phrases = ((get_post_meta(get_post()->ID, "max_phrases", true)) ? get_post_meta(get_post()->ID, "max_phrases", true): 0);

						if($max_phrases > 0){
							foreach($words as $phrase => $w){
								if($w >= $max_phrases){
									if($phrase !== "rsquo"){
										$duplicate_contents = preg_replace("/\b$phrase\b/u", "<strong>$phrase</strong>", $duplicate_contents);
									}
								}
							}
						}
					}
				}
			}

			$the_content = $duplicate_contents;
		}

		return $the_content;
	}

}
