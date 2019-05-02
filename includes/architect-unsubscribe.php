<?php 
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

require( '../../../../wp-load.php' );
global $wpdb;

// locate the email address to be removed.
$contact_id = $_GET['contact_id'] ;

$unsub_confirm_page = site_url() . "/email-unsubscribe" ;    

$sql = $wpdb->prepare("UPDATE `nomad_contacts` SET `email` = %s, `email_confirmed` = %s, 
    `email_optin_ip` = %s, `email_optin_date` = %s
    WHERE `nomad_contacts_id` = %d", "", "", "", "", $contact_id) ;

$wpdb->query($sql);

// then re-direct to public page confirming the email removal.
header("Location: $unsub_confirm_page");

?>