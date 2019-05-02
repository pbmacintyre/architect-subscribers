<?php 
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */

global $wpdb;

require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

/* ================================= */
/* Create architect_control table    */
/* ================================= */

$architect_sql = "CREATE TABLE `architect_control` (
  `architect_control_id` int(11) NOT NULL AUTO_INCREMENT,  
  `email_updates` tinyint(4) NOT NULL, 
  `token_prefix` varchar(10) NOT NULL,  
  PRIMARY KEY (`architect_control_id`) ) ";
dbDelta($architect_sql);
/* ====================================== */
/* seed table with control row and basic  */
/* data if there is no existing row       */
/* ====================================== */

$row_exists = $wpdb->get_var($wpdb->prepare("SELECT `architect_control_id` FROM `architect_control`
        WHERE `architect_control_id` = %d", 1) );
if (!$row_exists) {
    $wpdb->query( $wpdb->prepare("INSERT INTO `architect_control`
      (`architect_control_id`, `email_updates`)
      VALUES (%d, %d)", 1, 1 ) );
}
/* ================================= */
/* Create architect_contacts table   */
/* ================================= */

$architect_sql = "CREATE TABLE `architect_contacts` (
  `architect_contacts_id` int(11) NOT NULL AUTO_INCREMENT,  
  `architect_token` VARCHAR(33) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `email_optin_ip` varchar(50) NOT NULL,
  `email_optin_date` varchar(10) NOT NULL,  
  PRIMARY KEY (`architect_contacts_id`) ) ";
dbDelta($architect_sql);

/* ==============================*/
/* Create architect_help table   */
/* ==============================*/

$architect_sql = "CREATE TABLE `architect_help` ( 
`architect_help_id` INT NOT NULL AUTO_INCREMENT , 
`architect_help_field` VARCHAR(75) NULL , 
`architect_help_help` TEXT NULL , 
PRIMARY KEY (`architect_help_id`))";
dbDelta($architect_sql);
/* ====================================== */
/* seed table with control row and basic  */
/* data if there is no existing row       */
/* ====================================== */

$row_exists = $wpdb->get_var($wpdb->prepare("SELECT `architect_help_id` FROM `architect_help`
        WHERE `architect_help_id` = %d", 1) );
if (!$row_exists) {   
    $wpdb->query( $wpdb->prepare("INSERT INTO `architect_help`
      (`architect_help_field`, `architect_help_help`) VALUES (%s, %s)",
        "token_prefix", "This is used to make the opt-in email link unique, so that we can know better where the link is coming from." ) ); 
}
/* ======================================= */
/* build email confirmation page if needed */
/* ======================================= */

$new_page_title = 'eMail Confirmation';
$new_page_content = 'email-confirmed ... thanks for that !';
$new_page_template = '';
$page_check = get_page_by_title($new_page_title);
$new_page = array(
    'post_type' => 'page',
    'post_title' => $new_page_title,
    'post_content' => $new_page_content,
    'post_status' => 'publish',
    'post_author' => 1,
);
if(!isset($page_check->ID)){
    $new_page_id = wp_insert_post($new_page);
    if(!empty($new_page_template)){
        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
    }
}
/* =================================================== */
/* build email unsubscribe confirmation page if needed */
/* =================================================== */

$new_page_title = 'eMail Unsubscribe';
$new_page_content = 'You have been unsubscribed from the email list ... sorry to see you go.';
$new_page_template = '';
$page_check = get_page_by_title($new_page_title);
$new_page = array(
    'post_type' => 'page',
    'post_title' => $new_page_title,
    'post_content' => $new_page_content,
    'post_status' => 'publish',
    'post_author' => 1,
);
if(!isset($page_check->ID)){
    $new_page_id = wp_insert_post($new_page);
    if(!empty($new_page_template)){
        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
    }
}

?>