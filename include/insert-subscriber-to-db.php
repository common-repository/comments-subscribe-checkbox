<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ================================================
// If the user subscribed to the comment we will
// insert is info into the database
// ================================================

function yydev_comments_subscribe_show_message_function( $comment_ID, $comment_approved ) {

    include('settings.php'); // Load the files to get the databse info
    include_once('functions.php'); // Load the functions

    $active_subscriber = intval($_POST['subscribe_comments_box']); // checking if the user subscribed or not
    $comment_id = intval($comment_ID); // comment id
    $subscriber_email = sanitize_email($_POST['email']); // subscriber email
    $subscriber_name = sanitize_text_field($_POST['author']); // subscriber name 
    $visitor_ip = sanitize_text_field(getenv("REMOTE_ADDR")); // visitor ip

    $comment_parent = intval($_POST['comment_parent']); // if we replay to a comment there will be a commment id

    // getting the post info
    $this_post_id_number = intval($_POST['comments_subscribe_post_id']);
    $this_post_info = get_post($this_post_id_number);
    

    $post_id = intval($this_post_id_number); // subscriber name 
    $this_post_url = esc_url_raw( get_permalink($this_post_id_number) );
    $post_title = sanitize_text_field($this_post_info->post_title);

    $today_date = date("j.n.Y");
    $date = sanitize_text_field($today_date);
    $strtotime = sanitize_text_field(strtotime("NOW"));


    if( !empty($subscriber_email) ) {

        // ----------------------------------------
        // if the user subscribed to the comment we will
        // insert the data into the database
        // ----------------------------------------
        if( $active_subscriber == 1 ){
            
            // insert the subscribe data into the datbase
            global $wpdb;
            $wpdb->insert( $yydev_comments_subscribe_table_name,
                array(
                    'active_subscriber'=>$active_subscriber,
                    'comment_id'=>$comment_id,
                    'email'=>$subscriber_email,
                    'name'=>$subscriber_name,
                    'post_id'=>$post_id,
                    'post_url'=>$this_post_url,
                    'post_title'=>$post_title,
                    'date'=>$date,
                    'strtotime'=>$strtotime,
                    'cancel_date'=>'',
                    'ip'=>$visitor_ip,
                    'sent_mail_amount'=>'0',
                ), array('%d', '%d', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%d') );

        } // if( $active_subscriber == 1 ){

    } // if(!empty($subscriber_email)) {

} // function yydev_comments_subscribe_show_message_function( $comment_ID, $comment_approved ) {

add_action( 'comment_post', 'yydev_comments_subscribe_show_message_function', 10, 2 );

?>