<?php
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */

/* =============================== */
/* send out welcome email function */
/* =============================== */
function arch_send_welcome_email($email, $token, $full_name) {
        
    $confirm_url = add_query_arg(array('arch_subscribe'=>$token), get_site_url());
    
    $subject = 'Architect PHP - Please confirm your signup';
    
    $message = "Hi $full_name: <br/><br/>Thanks for joining our newsletter sign up process. ";
    $message .= "<br/><strong>[If this was not you please ignore this email]</strong><br/>";    
    $message .= "<br/>Please follow this link to confirm your subscription to our email notification list: <br/> ";
    $message .= "<a href='$confirm_url'> Confirm sign up </a>";
    
    // Send email to new sign up email.
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $email, $subject, $message, $headers );
} // end send_welcome_email function

/* ============================================== */
/* generate unique ID for new subscriber function */
/* ============================================== */
function arch_unique_token() {
    global $wpdb;
    $result = $wpdb->get_row( $wpdb->prepare("SELECT `token_prefix`
            FROM `architect_control` WHERE `architect_control_id` = %d", 1 )
        );
    $prefix = $result->token_prefix ;
    return uniqid($prefix, true) ;    
}

/* ============================== */
/* Build help icon and title text */
/* ============================== */
function arch_build_help($field) {
    global $wpdb;
    $image_source = ARCHITECT_PLUGIN_URL . 'images/question_mark.png' ;
    
    $result_help = $wpdb->get_row( $wpdb->prepare("SELECT architect_help_help AS help_text
            FROM `architect_help` WHERE `architect_help_field` = %s", $field) );
    if ($result_help) {
        $out_string = "<img src='$image_source' title='$result_help->help_text' />" ;
    } else {
        $out_string = "" ;
    }
    return $out_string ;
}


?>