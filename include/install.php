<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Require to use dbDelta
    include('settings.php'); // Load the files to get the databse info

    if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_comments_subscribe_table_name}' ") != $yydev_comments_subscribe_table_name ) {
        // The table we want to create doesn't exists
       
        $sql = "CREATE TABLE " . $yydev_comments_subscribe_table_name . "( 
            id INTEGER(11) UNSIGNED AUTO_INCREMENT,
            active_subscriber TINYINT(1),
            comment_id INTEGER (11),
            email VARCHAR (200),
            name VARCHAR (200),
            post_id INTEGER (11),
            post_url TEXT,
            post_title TEXT,
            strtotime INTEGER (30),
            date VARCHAR (200),
            cancel_date VARCHAR (200),
            sent_mail_amount INTEGER (11),
            ip VARCHAR (50),
            PRIMARY KEY (id) 
        ) $charset_collate;";
        
        dbDelta($sql);
        
       
    }  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_comments_subscribe_table_name}' ") != $yydev_comments_subscribe_table_name ) {
