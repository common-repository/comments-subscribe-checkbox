<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// getting intial data from the link
if( isset($_GET['c_subscribe']) ) {
    $comments_subscribe_id = intval( $_GET['c_subscribe'] );
} // if( isset($_GET['c_subscribe']) ) {

if( isset($_GET['id']) ) {
    $get_id = intval( $_GET['id'] );
} // if( isset($_GET['id']) ) {

if( isset($_GET['remove']) && !empty($_GET['remove']) ) {
    $remove_comments = sanitize_text_field( $_GET['remove'] );
} // if( isset($_GET['remove']) && !empty($_GET['remove']) ) {

// ================================================
// Removing the subscriber from the systesm
// ================================================

// making sure the $_GET parameter exists
if( isset($comments_subscribe_id) ) {
    
    $page_url = trim(strtolower($_SERVER["REQUEST_URI"])); // getting the page url
    
    // making sure the word /comments/ is inside the url
    if( strstr($page_url, "/comments/") ) {

        // making sure all the parameters are set correctly 
        if( isset($comments_subscribe_id) && isset($get_id) && isset($_GET['remove']) ) {

            $subscribe_id = trim(strtolower($comments_subscribe_id));
            $strtotime = trim(strtolower($get_id));
            $remove_element = trim(strtolower($_GET['remove']));


            include('settings.php'); // including the database info

            // checking if the subscriber exists in the database
            $subscriber_comment_data = $wpdb->get_row("SELECT * FROM " . $yydev_comments_subscribe_table_name . " WHERE id = '{$subscribe_id}' && strtotime = '{$strtotime}' ");
            
            // if there is a subscriber in the database
            if( $wpdb->num_rows > 0) {

                // making sure the mail was sent to user to avoid wp_mail opening the $_GET link
                if( intval($subscriber_comment_data->sent_mail_amount) > 0 ) {

                    $comment_id = $subscriber_comment_data->id;
                    $comment_email = $subscriber_comment_data->email;
                    $comment_post_title = $subscriber_comment_data->post_title;
                    $comment_post_url = $subscriber_comment_data->post_url;
                    $today_date = date("j.n.Y");

                    // ----------------------------------------
                    // unsubscribe the user only for one post
                    // ----------------------------------------
                    if( $_GET['remove'] === "post" ) {

                        // update the data into the datbase and remove subscriber
                        $wpdb->update( $yydev_comments_subscribe_table_name, array('active_subscriber'=>0, 'cancel_date'=>$today_date), array('id'=>$comment_id), array('%d', '%s') );

                        $yy_comments_page_title = __('Unsubscribe from comments newsletter', 'comments-subscribe-checkbox');

                        $yy_comments_page_content = "";
                        $yy_comments_page_content .= __('Your email address was successfully removed from the post', 'comments-subscribe-checkbox');
                        $yy_comments_page_content .= " <a href='" . $comment_post_url . "'>" . $comment_post_title . "</a>.";

                    } // if( $_GET['remove'] === "post" ) {


                    // ----------------------------------------
                    // unsubscribe the user for all the posts in the system
                    // ----------------------------------------
                    if( $_GET['remove'] === "all" ) {

                        $all_comments_subscribe = $wpdb->get_results("SELECT * FROM " . $yydev_comments_subscribe_table_name . " WHERE email = '{$comment_email}' ");

                        // unsubscribe from all posts
                        foreach($all_comments_subscribe as $comments_subscribe_data) {

                            $comment_id = $comments_subscribe_data->id;

                            // update the data into the datbase and remove subscriber
                            $wpdb->update( $yydev_comments_subscribe_table_name, array('active_subscriber'=>0, 'cancel_date'=>$today_date), array('id'=>$comment_id), array('%d', '%s') );

                        } // foreach($all_comments_subscribe as $comments_subscribe_data) {

                        $yy_comments_page_title = __('Unsubscribe from comments newsletter', 'comments-subscribe-checkbox');
                        $yy_comments_page_content = __('Your email address was successfully removed from all posts on the site', 'comments-subscribe-checkbox');

                    } // if( $_GET['remove'] === "all" ) {

                } // if( intval($subscriber_comment_data->sent_mail_amount) > 0 ) {

            } else { // if( $wpdb->num_rows > 0) {
                $comments_subscribe_error = 1;
            } // } else { // if( $wpdb->num_rows > 0) {


        } else { // if( isset($comments_subscribe_id) && isset($get_id) && isset($_GET['remove']) ) {
            $comments_subscribe_error = 1;
        } // if( isset($comments_subscribe_id) && isset($get_id) && isset($_GET['remove']) ) {


        // if something went wrong it will output error to the page
        if(isset($comments_subscribe_error)) {
            $yy_comments_page_title = __('An error has occurred', 'comments-subscribe-checkbox');
            $yy_comments_page_content = __("We're sorry there was a problem and the email address wasn't deleted from the mailing list", 'comments-subscribe-checkbox');
        } // if(isset($comments_subscribe_error)) {

        // ---------------------------------------------
        // Setting the page as page.php instead of 404 page
        // ---------------------------------------------
        
        function filter_the_posts($array)  { 

                        global $wp_query;
                        global $yy_comments_page_title;
                        global $yy_comments_page_content;

                        $posts[] =
                            (object) array(
                                'ID'                    => '9999999',
                                'post_author'           => '1',
                                'post_date'             => '2001-01-01 11:38:56',
                                'post_date_gmt'         => '2001-01-01 00:38:56',
                                'post_content'          => $yy_comments_page_content,
                                'post_title'            => $yy_comments_page_title,
                                'post_excerpt'          => '',
                                'post_status'           => 'publish',
                                'comment_status'        => 'closed',
                                'ping_status'           => 'closed',
                                'post_password'         => '',
                                'to_ping'               => '',
                                'pinged'                => '',
                                'post_modified'         => '2001-01-01 11:00:01',
                                'post_modified_gmt'     => '2001-01-01 00:00:01',
                                'post_content_filtered' => '',
                                'post_parent'           => '0',
                                'menu_order'            => '0',
                                'post_type'             => 'page',
                                'post_mime_type'        => '',
                                'post_category'         => '0',
                                'comment_count'         => '0',
                                'guid'                  => get_bloginfo( 'url' ) . '/?page_id=9999999',
                                'post_name'             => get_bloginfo( 'url' ) . '/?page_id=9999999',
                                'ancestors'             => array()
                            );

            // make filter magic happen here... 
            return $posts; 
        }; 
                
        // add the filter 
        add_filter( 'the_posts', 'filter_the_posts', 10, 1 ); 

        // ---------------------------------------------
        // making sure that wordpress will load page.php
        // ---------------------------------------------
        function load_regular_page_for_comments_subscribe( $template ) {

                $new_template = locate_template( array( 'page.php' ) );

                if ( !empty( $new_template ) ) {
                    return $new_template;
                } // if ( !empty( $new_template ) ) {

            return $template;
        } // function load_regular_page_for_comments_subscribe( $template ) {

        add_filter( 'template_include', 'load_regular_page_for_comments_subscribe');


    } // if( strstr($page_url, "/comments/") ) {

} // if( isset($comments_subscribe_id) ) {

?>