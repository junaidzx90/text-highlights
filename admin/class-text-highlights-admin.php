<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Text_Highlights
 * @subpackage Text_Highlights/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Text_Highlights
 * @subpackage Text_Highlights/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Text_Highlights_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/text-highlights-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/text-highlights-admin.js', array( 'jquery' ), $this->version, false );

	}

	function admin_menu_pages(){
		add_menu_page("Text Highlight","Text Highlight","manage_options","text-highlights",[$this, "text_hightlight_admin_page"],"dashicons-editor-bold",45 );
	}

	function text_hightlight_admin_page(){
		require_once plugin_dir_path(__FILE__ )."partials/text-highlights-admin-display.php";
	}

	function add_meta_boxes(){
		$saved_types = get_option( 'applied_post_types' );
		$saved_types = ((is_array($saved_types))? $saved_types: []);
		
		
		if(is_array($saved_types) && sizeof($saved_types)>0){
			foreach($saved_types as $type){
				if($type !== 'attachment'){
					add_meta_box("phrases","Phrases",[$this, "phrases_meta"], $type, "side");
					add_meta_box("max_phrases","Maximum match phrases",[$this, "max_phrases_meta"], $type, "side");
				}
			}
		}
		
	}

	function phrases_meta($post){
		?>
		<div class="rightbar">
            <div id="key_phrases_area">
                <div class="key_phrases">
                    <?php
                    $phrases = get_post_meta($post->ID, "key_phrases", true);
                    
                    if(is_array($phrases) && sizeof($phrases)>0){
                        foreach($phrases as $phrase){
                            ?>
                            <div class="textInput"> <input class="widefat text__input" placeholder="text" type="text" name="key_phrases[]" value="<?php echo $phrase ?>"> </div> 
                            <?php
                        }
                        ?>
                        <div class="textInput"> <input class="widefat text__input" placeholder="text" type="text" name="key_phrases[]" value=""> </div> 
                        <?php
                    }elseif(!$phrases){
                        ?>
                        <div class="textInput"> <input class="widefat text__input" placeholder="text" type="text" name="key_phrases[]" value=""> </div> 
                        <?php
                    }else{
                        ?>
                        <div class="textInput"> <input class="widefat text__input" placeholder="text" type="text" name="key_phrases[]" value=""> </div> 
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
		<?php
	}

	function max_phrases_meta($post){
		$max = get_post_meta($post->ID, "max_phrases", true);
		?>
		<input type="number" value="<?php echo $max ?>" placeholder="10" name="max_phrases" class="widefat">
		<?php
	}

	function save_post_phrases($post_id){
		$key_phrases = ((isset($_POST['key_phrases']))? $_POST['key_phrases']: []);
		$key_phrases = array_filter($key_phrases);
		update_post_meta($post_id, 'key_phrases', $key_phrases );
		
		$max_phrases = ((isset($_POST['max_phrases']))? $_POST['max_phrases']: '');
		update_post_meta($post_id, 'max_phrases', $max_phrases );
	}

}
