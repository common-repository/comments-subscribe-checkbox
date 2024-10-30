<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 

// ================================================
// This function will allow us to send email to 
// the subscribe if the parent post comment exists
// on the database and was subscribed
// $parent_comment_id = the parent post id of the new comment
// the value will be set only when the comment is a reply for other comment
// ================================================

function yydev_comments_subscribe_send_mail_to_subscriber($parent_comment_id) {

    include('settings.php'); // getting the database info

    // allow us to if the if the comment reply was for someone who subscribed
    $reply_to_comment_id = $parent_comment_id; 

    // check to see if the reply comment is a subscribed user
    $database_content_output = $wpdb->get_results("SELECT * FROM " . $yydev_comments_subscribe_table_name . " WHERE comment_id = '{$reply_to_comment_id}'");

    foreach($database_content_output as $database_output) {

        // ------------------------------------------
        // Getting the subscribe data from the database
        // ------------------------------------------
        
        $db_id = intval($database_output->id); // the id of the comment in the database
        $active_subscriber = intval($database_output->active_subscriber); // checking if the subscriber is active, 1 = active, 0 = not active
        $comment_id = intval($database_output->comment_id); // comment id
        $subscribe_email = yydev_comments_subscribe_html_output($database_output->email); // the subscriber email
        $subscribe_name = yydev_comments_subscribe_html_output($database_output->name); // the subscriber name 
        $post_id = intval($database_output->post_id); // the blog post id
        $post_url = $database_output->post_url; // the blog post url
        $post_title = yydev_comments_subscribe_html_output($database_output->post_title); // the blog post title
        $strtotime = yydev_comments_subscribe_html_output($database_output->strtotime); // the time stamp 


        // creating the email that we will send the data from
        $urlparts = parse_url(site_url());
        $domain_name = $urlparts ['host'];
        $domain_name = str_replace('www.', '', $domain_name);
        $__mail_domain_name = 'no-reply@' . $domain_name;


        // if the domain dotn' contain ending like .com we will add it to avoide errors
        if( !strstr($domain_name, '.') ) {
            $__mail_domain_name = $__mail_domain_name . ".com";
        } // if( !strstr($domain_name, '.' ) {


        // getting the site url as the name we will send the datafrom
        $__site_name = get_bloginfo('name');

        // getting the site url path
        $site_url_path = site_url();


        // $comment_post_link = $post_url . '#comment-' . $comment_id; // the link that will alow the viewer to view the comment
        $comment_post_link = $site_url_path . "/?p=" . $post_id . '#comment-' . $comment_id; // alternative comment link without long url
        
        $__post_unsubscribe_link = $site_url_path . "/comments/" . "?c_subscribe=" . urlencode($db_id) . "&id=" . urlencode($strtotime) . "&remove=post"; // unsubscribe user for getting alert email for this post
        $blog_unsubscribe_link = $site_url_path . "/comments/" . "?c_subscribe=" . urlencode($db_id) . "&id=" . urlencode($strtotime) . "&remove=all"; // unsubscribe user for getting alert email for the posts
        // ------------------------------------------
        // Sending mail to subscriber
        // ------------------------------------------

        // making sure the subscriber is active
        if($active_subscriber == 1) {

            $send_email_to = $subscribe_email;
            $subject = __('New comment reply on post', 'comments-subscribe-checkbox') . ' "' . $post_title . '"';
            $sent_email_from = $__mail_domain_name; // the email that the message sent from

            $send_message = "";
            $send_message .= __('Hey', 'comments-subscribe-checkbox') . " {$subscribe_name}, \n";
            $send_message .= __('We happy to let you know someone replied to your comment on post', 'comments-subscribe-checkbox') . " - {$post_title} \n\n";
            $send_message .= __('You can view your comment reply by clicking on the link below:', 'comments-subscribe-checkbox') . " \n";
            $send_message .= "{$comment_post_link} \n\n\n\n\n\n";
            $send_message .= __('If you wish to stop getting notifications when someone replies to this comment click below:', 'comments-subscribe-checkbox') . " \n";
            $send_message .= $__post_unsubscribe_link . " \n\n";
            $send_message .= __('If you wish to stop getting notifications for all replies to your website comments click below:', 'comments-subscribe-checkbox') . " \n";
            $send_message .= $blog_unsubscribe_link . " \n\n";
            $send_message .= __('This message was sent from', 'comments-subscribe-checkbox') . " {$site_url_path}";


            $headers[] = "From: $__site_name <$sent_email_from>";
            // $headers[] = "Reply-To: $__site_name <$sent_email_from>";

            // wp_mail($to, $subject, $content, $headers);
            $sent_mail_status = wp_mail( $send_email_to, $subject, $send_message, $headers );
            
            // ----------------------------------------
            // update the datbase if the email was sent
            // ----------------------------------------
            if( $sent_mail_status ) {

                // the email was sent successfully
                $email_sent_amount = $database_output->sent_mail_amount; // the amount of emails that were already sent
                $new_sent_emails_amount = $email_sent_amount + 1;

                // updateing that the email was sent in the database
                $wpdb->update( $yydev_comments_subscribe_table_name, array('sent_mail_amount'=>$new_sent_emails_amount), array('id'=>$db_id), array('%d') );

            } // if( $sent_mail_status ) {


        } // if($active_subscriber == 1) {

    } // foreach($database_content_output as $database_output) {

} // function yydev_comments_subscribe_send_mail_to_subscriber($parent_comment_id) {


// ================================================
// Send mail to subscribers if there are new reply
// comment that was approved.
// the only approved comments on reply are the
// ones who sent by the admin
// ================================================

function yydev_comments_subscribe_checking_if_to_send_mail_on_new_comment( $comment_ID, $comment_approved ) {

    if( 1 === $comment_approved ){
            
        // ---------------------------------------------------
        // getting this comment parent_id from the database
        // ---------------------------------------------------
        global $wpdb;
        $this_comment_id = $comment_ID; // the ID of the comment that changed the status
        $comment_db_table = $wpdb->prefix . "comments";
        $get_comment_data = $wpdb->get_row("SELECT * FROM " . $comment_db_table . " WHERE comment_ID = '{$this_comment_id}'");

        // getting the comment parent id
        $comment_parent = intval($get_comment_data->comment_parent);

        // ---------------------------------------------------
        // this function will send email to subscriber if 
        // the parent comment exists in the databse
        // ---------------------------------------------------
        if( !empty($comment_parent) ) {
            yydev_comments_subscribe_send_mail_to_subscriber($comment_parent);
        } // if( !empty($comment_parent) ) {

    } // if( 1 === $comment_approved ){

} // function sending_mail_for_subscribers( $comment_field ) {

add_action( 'comment_post', 'yydev_comments_subscribe_checking_if_to_send_mail_on_new_comment', 10, 2);


// ================================================
// Function that will run if the comment status will change.
// Normally when someone post a comment it's pending 
// until it will be approved by the admin.
// Whne it's approved we will try to run the subscribe 
// function and send email to the subscriber
// ================================================

function yydev_comments_subscribe_check_for_new_comment_status($comment_ID, $status){
    
    include('settings.php'); // getting the database info

    $comment_status = esc_attr($status); // the comment status can be 'hold', 'approve', 'spam', or 'trash'.
    $this_comment_id = intval($comment_ID); // the ID of the comment that changed the status

    // checking if the comment was approved
    if($comment_status === "approve") {

        // getting this comment parent_id from the database
        $comment_db_table = $wpdb->prefix . "comments";
        $get_comment_data = $wpdb->get_row("SELECT * FROM " . $comment_db_table . " WHERE comment_ID = '{$this_comment_id}'");

        // the parent comment id of the changed comment
        $comment_parent_id = intval($get_comment_data->comment_parent);

        if(!empty($comment_parent_id)) {
            yydev_comments_subscribe_send_mail_to_subscriber($comment_parent_id);
        } // if(!empty($comment_parent_id)) {

    } // if($comment_status === "approve") {

} // function yydev_comments_subscribe_check_for_new_comment_status($comment_ID, $status){

add_action('wp_set_comment_status', 'yydev_comments_subscribe_check_for_new_comment_status', 10, 3);

// ================================================
// Remove subscribe comments from database if they
// were deleted from the site 
// ================================================

function yydev_comments_subscribe_checking_remove_comment_from_db( $comment_ID ) {

        include('settings.php'); // getting the database info

        global $wpdb;
        $this_comment_id = intval($comment_ID); // the ID of the comment
        $get_comment_data = $wpdb->delete( $yydev_comments_subscribe_table_name, array('comment_id'=>$this_comment_id) );

} // function yydev_comments_subscribe_checking_remove_comment_from_db( $comment_ID ) {

add_action('deleted_comment', 'yydev_comments_subscribe_checking_remove_comment_from_db');

?>
