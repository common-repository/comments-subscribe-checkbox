<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

global $wpdb;
$yydev_comments_subscribe_table_name = $wpdb->prefix . "yydev_comments_subscribe"; // Database Table Name

// Important:
// We also need to change the function on function.php and make sure there is not function with the same name for mysql_prep


?>