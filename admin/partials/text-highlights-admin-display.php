<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Text_Highlights
 * @subpackage Text_Highlights/admin/partials
 */
?>

<?php
if(isset($_POST['save_phrase_settings'])){
    $post_types = ((isset($_POST['applied_post_types']))? $_POST['applied_post_types']: []);
    update_option( 'applied_post_types', $post_types );
}
?>

<h3>Settings </h3>
<hr>

<form method="post" class="th_wrapp">
    <div class="th_input">
        <div class="leftbar">
            <label for="applied_post_types">Post types to apply</label>
        </div>
        <div class="rightbar">
            <select multiple name="applied_post_types[]" id="applied_post_types">
                <?php
                $args = array(
                    'public'   => true
                );
                
                $output = 'names';
                
                $post_types = get_post_types( $args, $output );
                $saved_types = get_option( 'applied_post_types' );
                $saved_types = ((is_array($saved_types))? $saved_types: []);
                
                
                if(is_array($post_types) && sizeof($post_types)>0){
                    foreach($post_types as $type){
                        if($type !== 'attachment')
                            echo '<option '.((in_array($type, $saved_types) ? 'selected': '')).' value="'.$type.'">'.ucfirst($type).'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>

    <button class="save_phrase_settings button-primary" name="save_phrase_settings" type="submit">Save Settings</button>
</form>
