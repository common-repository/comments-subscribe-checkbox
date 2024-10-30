<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$error_message = '';

// ====================================================
// Include the file that contains all the info
// ====================================================
include('settings.php');

// ====================================================
// Redirect the user the to main page
// if the data was not found
// ====================================================

$secondary_page_id = intval( $_GET['id'] );

if( isset($secondary_page_id) && !empty($secondary_page_id) ) {
    
    global $wpdb;
    $check_for_real_data_id = $wpdb->query("SELECT id FROM " . $yydev_comments_subscribe_table_name . " WHERE id = " . $secondary_page_id);
    
    if($check_for_real_data_id == 0 ) {
        $error_message = "The comments subscribe id you were looking for was not found";
        $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
        $new_page_link = $page_url . "&error-message=" . urlencode($error_message);
        // yydev_comments_subscribe_redirect_page($new_page_link);
    } // if($check_for_real_data_id < 1 ) {

} // if( (isset($secondary_page_id) && !empty($secondary_page_id)) || !isset($secondary_page_id) ) {
    
// ====================================================
// Update the data if it's changed
// ====================================================
    
if( isset($_POST['yydev_c_subscribe_nonce']) ) {

    if( wp_verify_nonce($_POST['yydev_c_subscribe_nonce'], 'yydev_c_subscribe_action') ) {

        if( isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id']) ) {

                // If there is no error insert the info to the database
                $id = intval($_POST['id']);
                $name = sanitize_text_field($_POST['name']);
                $email = sanitize_email($_POST['email']);
                $comment_id = intval($_POST['comment_id']);
                $post_id = intval($_POST['post_id']);
                $post_url = esc_url_raw($_POST['post_url']);
                $post_title = sanitize_text_field($_POST['post_title']);
                $strtotime = intval($_POST['strtotime']);
                $date = sanitize_text_field($_POST['date']);
                $cancel_date = sanitize_text_field($_POST['cancel_date']);
                $sent_mail_amount = intval($_POST['sent_mail_amount']);
                $visitor_ip = sanitize_text_field($_POST['ip']);
                $active_subscriber = intval($_POST['active_subscriber']);

                // update the data into the datbase
                $wpdb->update( $yydev_comments_subscribe_table_name,
                array(
                    'name'=>$name,
                    'email'=>$email,
                    'comment_id'=>$comment_id,
                    'post_id'=>$post_id,
                    'post_url'=>$post_url,
                    'post_title'=>$post_title,
                    'strtotime'=>$strtotime,
                    'date'=>$date,
                    'cancel_date'=>$cancel_date,
                    'sent_mail_amount'=>$sent_mail_amount,
                    'ip'=>$visitor_ip,
                    'active_subscriber'=>$active_subscriber,
                ), array('id'=>$id), array('%s', '%s', '%d', '%d', '%s', '%s', '%d', '%s', '%s', '%d', '%s', '%d') );
                

                // Creating page link and redirect the user to the new url page where he can edit the data
                $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
                $success_message = "The comments subscribe was updated successfully";
                $new_page_link = $page_url . "&view=secondary&id=" . $id . "&message=" . urlencode($success_message);

        } // if( isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id']) ) {
     
    } else { // if( wp_verify_nonce($_POST['yydev_c_subscribe_nonce'], 'yydev_c_subscribe_action') ) {
        $error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_c_subscribe_nonce'], 'yydev_c_subscribe_action') ) {

} // if( isset($_POST['yydev_c_subscribe_nonce']) ) {

?>

<div class="wrap yydev_comments_subscribe">
    <h2 class="display-inline">Edit Comments Subscribe</h2>
    <a href="<?php echo esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) ); ?>">Go Back</a>

    <?php yydev_comments_subscribe_echo_message_if_exists($success_message); ?>
    <?php yydev_comments_subscribe_echo_error_message_if_exists($error_message); ?>
    
    <div class="insert-new">
        
<?php

    $check_content_id = $wpdb->get_row("SELECT * FROM " . $yydev_comments_subscribe_table_name . " WHERE id = " . $secondary_page_id );

?>
        <br /><br />                
        <form class="edit-form-data" method="POST" action="">
           
            <span>ID: <?php echo intval($check_content_id->id); ?></span>
            <br />
           
            <label for="form_name">Name:</label>
            <input type="text" id="form_name" class="input-long" name="name" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->name); ?>" />
            
            <br />
            
            <label for="email">Email:</label>
            <input type="text" id="email" class="input-long" name="email" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->email); ?>" />

            <br />
            
            <label for="comment_id">Comment ID:</label>
            <input type="text" id="comment_id" class="input-long" name="comment_id" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->comment_id); ?>" />

            <br />

            <label for="post_id">Post ID:</label>
            <input type="text" id="post_id" class="input-long" name="post_id" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->post_id); ?>" />

            <br />

            <label for="post_url">Post URL:</label>
            <input type="text" id="post_url" class="input-long" name="post_url" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->post_url); ?>" />

            <br />

            <label for="post_title">Post Title:</label>
            <input type="text" id="post_title" class="input-long" name="post_title" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->post_title); ?>" />

            <br />

            <label for="strtotime">Time Stamp (strtotime):</label>
            <input type="text" id="strtotime" class="input-long" name="strtotime" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->strtotime); ?>" />

            <br />

            <label for="date">Date:</label>
            <input type="text" id="date" class="input-long" name="date" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->date); ?>" />

            <br />

            <label for="cancel_date">Cancel Date:</label>
            <input type="text" id="cancel_date" class="input-long" name="cancel_date" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->cancel_date); ?>" />

            <br />

            <label for="sent_mail_amount">Sent Mails Amount:</label>
            <input type="text" id="sent_mail_amount" class="input-long" name="sent_mail_amount" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->sent_mail_amount); ?>" />


            <br />

            <label for="visitor_ip">Visitor IP:</label>
            <input type="text" id="visitor_ip" class="input-long" name="ip" value="<?php echo yydev_comments_subscribe_html_output($check_content_id->ip); ?>" />

            <br />

            <label for="active_subscriber">Active Subscriber:</label>
            <select name="active_subscriber" id="active_subscriber">
                <option value="0" <?php if( $check_content_id->active_subscriber == '0' ) {echo "selected";} ?> >Not Active</option>
                <option value="1" <?php if( $check_content_id->active_subscriber == '1' ) {echo "selected";} ?> >Active</option>
            </select> 

            <br />

            <input type="hidden" name="id" value="<?php echo intval($secondary_page_id); ?>" />
            
            <?php
                // creating nonce to make sure the form was submitted correctly from the right page
                wp_nonce_field( 'yydev_c_subscribe_action', 'yydev_c_subscribe_nonce' ); 
            ?>

            <br /><br />
            <input type="submit" class="edit-form-data" name="edit-form-data" value="Edit Comments Subscribe" />
        </form>

<?php
    $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
    $page_url = $page_url . "&remove-form=1&id=" . $secondary_page_id; 
?>
<a class="remove-form remove-button" href="<?php echo esc_url($page_url); ?>">Delete Comments Subscribe</a>

</div><!--wrap-->

<br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/comments-subscribe-checkbox/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=comments-subscribe-checkbox">buy us a coffee</a>.</span>
</span>
</div><!--wrap-->
