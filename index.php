<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/*
Plugin Name: YYDevelopment - Subscribe To Comments Checkbox
Plugin URI:  https://www.yydevelopment.com/yydevelopment-wordpress-plugins/
Description: Simple plugin that allow you to automatically send email when someone reply to comment that was subscribed
Version:     1.2.4
Author:      YYDevelopment
Author URI:  https://www.yydevelopment.com/
Text Domain: comments-subscribe-checkbox
*/

// ================================================
// Adding lanagues support to the plugin
// ================================================

function yydev_comments_subscribe_languages() {
  load_plugin_textdomain( 'comments-subscribe-checkbox', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
} // function yydev_comments_subscribe_languages() {
	
add_action( 'plugins_loaded', 'yydev_comments_subscribe_languages' );

// loading the languages for the admin back end as well and mainly for the unsubscribe page
load_plugin_textdomain( 'comments-subscribe-checkbox', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

// ================================================
// Including important files
// ================================================

include('include/settings.php');
require_once('include/functions.php');

// ================================================
// Creating Database when the plugin is activated
// ================================================

function yydev_comments_subscribe_create_database() {
    
    require_once('include/install.php');
        
} // function yydev_comments_subscribe_create_database() {

register_activation_hook(__FILE__, 'yydev_comments_subscribe_create_database');


// ================================================
// Adding menu tag inside wordpress admin panel
// ================================================

function yydev_comments_subscribe_subscribe_page() {
    
    global $wpdb;

    include('include/style.php');
    include('include/script.php');
    
    // Including the main page
    
    if( isset($_GET['view']) && isset($_GET['id']) && ($_GET['view'] = 'secondary') ) {
        include('include/secondary-page.php');
    } else {
        include('include/main-page.php');
    }
}// function yydev_comments_subscribe_subscribe_page() {

function wordpress_comments_subscribe_plugin_menu() {
    include('include/settings.php');
    add_comments_page('Comments Subscribers', 'Comments Subscribers', 'manage_options', 'wordpress-comment-subscribe', 'yydev_comments_subscribe_subscribe_page');
}

add_action('admin_menu', 'wordpress_comments_subscribe_plugin_menu');

// ================================================
// Add settings page to the plugin menu info
// ================================================

function yydev_comments_subscribe__add_settings_link( $actions, $plugin_file ) {

	static $plugin;

    if (!isset($plugin)) { $plugin = plugin_basename(__FILE__); }

	if ($plugin == $plugin_file) {

            $admin_page_url = esc_url( menu_page_url( 'wordpress-comment-subscribe', false ) );
			$settings = array('settings' => '<a href="' . $admin_page_url . '">Subscribers List</a>');
            $donate = array('donate' => '<a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=comments-subscribe-checkbox">Donate</a>');

            $actions = array_merge($settings, $donate, $actions);

    } // if ($plugin == $plugin_file) {
    return $actions;

} //function yydev_comments_subscribe__add_settings_link( $actions, $plugin_file ) {

add_filter( 'plugin_action_links', 'yydev_comments_subscribe__add_settings_link', 10, 5 );

// ================================================
// Including front-end code
// ================================================

include('front-end/add-checkbox-to-comments.php');

// ================================================
// Other backend code
// ================================================

// inserting the subscriber data into the database
include('include/insert-subscriber-to-db.php');

// sending mail to the subscriber
include('include/send-mail-to-subscriber.php');

// unsubscribe the user if he decide to leave
include('include/unsubscribe.php');

// ================================================
// including admin notices flie
// ================================================

if( is_admin() ) {
	include_once('notices.php');
} // if( is_admin() ) {