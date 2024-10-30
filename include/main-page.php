<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$error_message = '';

// ====================================================
// Include the file that contains all the info
// ====================================================
include('settings.php');

// ====================================================
// Removing the Data if it was deleted
// ====================================================

if( isset($_GET['id']) ) {
    $secondary_page_id = intval( $_GET['id'] );
} // if( isset($_GET['id']) ) {

if(isset($_GET['remove-form']) && isset($secondary_page_id) && !empty($secondary_page_id) ) {

    $check_content_id = $wpdb->query("SELECT * FROM " . $yydev_comments_subscribe_table_name . " WHERE id = " . $secondary_page_id);

    if($check_content_id > 0) {
        // if the data id exists on the database it will be removed
        
        $wpdb->delete( $yydev_comments_subscribe_table_name, array('id'=>$secondary_page_id) );
        $success_message = "The comments subscribe id #" . $secondary_page_id . " was removed successfully";
        
        $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
        $new_page_link = $page_url . "&message=" . urlencode($success_message);
        // yydev_comments_subscribe_redirect_page($new_page_link);
        
    } else { // if($check_content_id > 0) {
        
        $error_message = "The comments subscribe id wasn't not found";
        
        $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
        $new_page_link = $page_url . "&error-message=" . urlencode($error_message);
        // yydev_comments_subscribe_redirect_page($new_page_link);
        
    } // } else { // if($check_content_id > 0) {
    
} // if(isset($_GET['remove-form']) && isset($secondary_page_id) && !empty($secondary_page_id) ) {

?>

<div class="wrap yydev_comments_subscribe">
    <h2>Comments Subscribers</h2>

    <?php yydev_comments_subscribe_echo_message_if_exists($success_message); ?>
    <?php yydev_comments_subscribe_echo_error_message_if_exists($error_message); ?>

            
    <table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th style="width:40px;">ID</th>
            <th style="width:80px;">Date</th>
            <th style="width:150px;">Name</th>
            <th style="width:250px;">Email</th>
            <th style="width:250px;">Subscribed Post</th>
            <th style="width:80px;">Comment ID</th>
            <th style="width:130px;">Active Subscriber</th>
            <th style="width:130px;">IP</th>
            <th style="width:250px;">Action</th>
            <th style="width:150px;">Sent Mails Amount</th>
        </tr>
    </thead>
    
    <tbody id="the-list">
    
<?php
    
// ================================================
// Echoing all the data from the database 
// ================================================
    
    global $wpdb;
    $database_content_output = $wpdb->get_results("SELECT * FROM " . $yydev_comments_subscribe_table_name . " ORDER BY id DESC ");
    
    // Echo if nothing was found
    if(empty($database_content_output)) {
?>
    <tr class="no-items"><td class="colspanchange" colspan="10">No comments subscribers found</td></tr>
<?php     
    } // if(empty($database_content_output)) {
    
    
    
    foreach($database_content_output as $database_output) {
        
        $page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );        
    
        // creating a class if the user is not active 
        $not_active_class_tr = "";
        if($database_output->active_subscriber == 0) {
            $not_active_class_tr = "class='not_active_subscribe_class_tr'";
        } // if($database_output->active_subscriber == 1) {
?>
        <tr <?php echo $not_active_class_tr; ?>>
            <td><?php echo intval($database_output->id); ?></td>
            <td><?php echo yydev_comments_subscribe_html_output($database_output->date); ?></td>
            <td><a href="<?php echo $page_url . "&view=secondary&id=" . $database_output->id; ?>"><?php echo yydev_comments_subscribe_html_output($database_output->name); ?></a></td>
            <td><a href="<?php echo $page_url . "&view=secondary&id=" . $database_output->id; ?>"><?php echo yydev_comments_subscribe_html_output($database_output->email); ?></a></td>

            <td> <a target="_blank" href="<?php echo esc_url($database_output->post_url); ?>#comment-<?php echo intval($database_output->comment_id); ?>"><?php echo yydev_comments_subscribe_html_output($database_output->post_title); ?></a></td>

            <td><?php echo intval($database_output->comment_id); ?></td>

            <?php

                 $active_subscriber = "Not Active";
                if($database_output->active_subscriber == 1) {
                    $active_subscriber = "Active";
                } // if($database_output->active_subscriber == 1) {

            ?>
            <td><?php echo $active_subscriber; ?></td>

            <td><?php echo yydev_comments_subscribe_html_output($database_output->ip); ?></td>

            <td>
                <a href="<?php echo $page_url . "&view=secondary&id=" . intval($database_output->id); ?>">Edit</a> &nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;
                <a class="remove-form" href="<?php echo $page_url . "&remove-form=1&id=" . intval($database_output->id); ; ?>">Delete</a>
            </td>

            <td><?php echo intval($database_output->sent_mail_amount); ?></td>

        </tr>
        
<?php
    } // foreach($database_content_output as $database_output) {
    
?>

    </tbody>
    
    <tfoot>
        <tr>
            <th style="width:40px;">ID</th>
            <th style="width:80px;">Date</th>
            <th style="width:150px;">Name</th>
            <th style="width:250px;">Email</th>
            <th style="width:250px;">Subscribed Post</th>
            <th style="width:80px;">Comment ID</th>
            <th style="width:130px;">Active Subscriber</th>
            <th style="width:130px;">IP</th>
            <th style="width:250px;">Action</th>
            <th style="width:150px;">Sent Mails Amount</th>
        </tr>
    </tfoot>
    
    </table>
        
<br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/comments-subscribe-checkbox/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=comments-subscribe-checkbox">buy us a coffee</a>.</span>
</span>
</div><!--wrap-->

</div><!--wrap-->