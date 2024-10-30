<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 

// ================================================
// Add your checkbox after the comment field
// ================================================

function adding_comments_subscribe_checkbox( $comment_field ) {

  // ------------------------------------------------
  // this filter allow us to force the checkbox as checked
  // ------------------------------------------------

    $check_subscribe_comment = apply_filters( 'yydev_check_subscribe_comment',  '0' );

    $checked_box = "";
    if( intval($check_subscribe_comment) == 1 ) {
        $checked_box = "checked";
    } // if( intval($check_subscribe_comment) == 1 ) {

  // ------------------------------------------------
  // control the text that show up next to the checkbox button
  // ------------------------------------------------

  $checkbox_text = "";
  $check_subscribe_text = apply_filters( 'yydev_check_subscribe_text',  __('Send me an email notification when someone replies to my comment', 'comments-subscribe-checkbox'), $checkbox_text);
  $check_subscribe_text = sanitize_text_field($check_subscribe_text);

  // ------------------------------------------------
  // output the form into the page
  // ------------------------------------------------

    $subscribe_box = "";

    $subscribe_box .= "<div class='subscribe-comments-line' style='padding: 3px 0px 8px 0px; display: block;'>";

        $subscribe_box .= "<label for='subscribe_comments_box' style='display: inline-block; margin: 0px;'>";
            $subscribe_box .= "<input type='checkbox' id='subscribe_comments_box' name='subscribe_comments_box' value='1' " . $checked_box . " style='display: inline-block; margin: 0px;' />";
            $subscribe_box .= "<span style='margin: 0px 4px 0px 4px;'>" . $check_subscribe_text . "</span>";
        $subscribe_box .= "</label>";
        
        $subscribe_box .= "<input type='hidden' name='comments_subscribe_post_id' value='" . get_the_ID() . "' />";

        // getting email and name for user who is logged in
        if( is_user_logged_in() ) {

          $user_id = get_current_user_id(); 
          $user_info = get_userdata($user_id);
          $user_email = $user_info->user_email;
          $user_name = $user_info->display_name;

          $subscribe_box .= "<input type='hidden' name='email' value='" . $user_email . "' />";
          $subscribe_box .= "<input type='hidden' name='author' value='" . $user_name . "' />";

        } // if( is_user_logged_in() )

    $subscribe_box .= "</div><!--subscribe-comments-line-->";


  $comment_field =  $comment_field . $subscribe_box;
  return $comment_field;

} // function adding_comments_subscribe_checkbox( $comment_field ) {

add_filter( 'comment_form_field_comment', 'adding_comments_subscribe_checkbox' );

