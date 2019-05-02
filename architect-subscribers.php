<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

/*
 Plugin Name: Architect Subscribers
 Plugin URI:  https://paladin-bs.com/plugins/
 Description: Plugin sample for teaching plugin Development.
 Author:      Peter MacIntyre
 Version:     1.2 
 Author URI:  https://paladin-bs.com/peter-macintyre/
 Details URI: https://paladin-bs.com
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
 Architect Subscribers is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.
 
 Architect Subscribers is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.
 
 See License URI for full details.
 */

/* ============================== */
/* Set Architect Constant values */
/* ============================== */

if(!defined('ARCHITECT_PLUGIN_URL')){
    define('ARCHITECT_PLUGIN_URL', plugin_dir_url(__FILE__) ) ;
}
// if(!defined('ARCHITECT_PLUGIN_FILENAME_URL')){
//     define ('ARCHITECT_PLUGIN_FILENAME_URL', plugin_basename(dirname(__FILE__) . '/architect-plugin.php') ) ;
// }

if(!defined('ARCHITECT_ICON')){
    define ('ARCHITECT_ICON', ARCHITECT_PLUGIN_URL . 'images/logo_only_orange.png' ) ;
}
if(!defined('ARCHITECT_PLUGINDIR')){
    define('ARCHITECT_PLUGINDIR', plugin_dir_path(__FILE__) ) ;
}
if(!defined('ARCHITECT_LOGO')){
    define ('ARCHITECT_LOGO', ARCHITECT_PLUGIN_URL . 'images/logo_orange.png' ) ;
}

// for proper .php file inclusion
if(!defined('ARCHITECT_PLUGIN_FILENAME')){
    define( 'ARCHITECT_PLUGIN_FILENAME', __FILE__ );
    // looks like: /home2/paladip9/public_html/plugins/wp-content/plugins/architect-plugin/architect-plugin.php    
}
if(!defined('ARCHITECT_PLUGIN_FILEPATH')){
    //  looks like: /home2/paladip9/public_html/plugins/wp-content/plugins/architect-plugin
    define( 'ARCHITECT_PLUGIN_FILEPATH', dirname( ARCHITECT_PLUGIN_FILENAME ) );
}
if(!defined('ARCHITECT_PLUGIN_FILEPATH_INCLUDES')){
    //  looks like: /home2/paladip9/public_html/plugins/wp-content/plugins/architect-plugin/includes/
    define( 'ARCHITECT_PLUGIN_FILEPATH_INCLUDES', dirname( ARCHITECT_PLUGIN_FILENAME )  . "/includes/" );
}
/* ============ for debugging purposes ================ */
if(!defined('ARCHITECT_SPACER')){
    $spacer = "<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
    define ('ARCHITECT_SPACER', $spacer) ;
}
/* ======================= */
/* Load language pointers  */
/* ======================= */
function arch_subscribers_languages() {
    $worked = load_plugin_textdomain('architect-subscribers', false, 'architect-subscribers/languages');
    // echo ARCHITECT_SPACER . "loaded: " . $worked ;
}
add_action('plugins_loaded', 'arch_subscribers_languages');
/* ============ for debugging purposes ================ */

/* ==================== */
/* set supporting cast  */
/* ==================== */
function architect_js_add_script() {
    $js_path = ARCHITECT_PLUGIN_URL . 'js/architect-scripts.js' ;
    wp_enqueue_script('architect-js', $js_path) ;
}
add_action('init', 'architect_js_add_script');

function architect_css_add_script() {
    wp_register_style( 'architect_custom_admin_css',
        ARCHITECT_PLUGIN_URL . 'css/architect-custom.css',
        false, '1.0.0' );
    wp_enqueue_style( 'architect_custom_admin_css' );
}

add_action( 'admin_print_styles', 'architect_css_add_script' );

/* ========================================= */
/* Make top level menu                       */
/* ========================================= */

function arch_menu(){
    add_menu_page(
        'Architect Free: Architect Settings',   # Page & tab title
        'Architect Free',                       # Menu title
        'manage_options',                       # Capability option
        'architect_Admin',                      # Menu slug
        'architect_config_page',                # menu destination function call
        ARCHITECT_ICON,                         # menu icon path        
        25 );                                   # menu position level
        add_submenu_page(
            'architect_Admin',                // parent slug
            'Architect Free: Configurations Page', // page title
            'Settings',                            // menu title - can be different than parent
            'manage_options',                      // options
            'architect_Admin' );                 // menu slug to match top level (go to the same link)
        add_submenu_page(
            'architect_Admin',                   // parent menu slug
            'Architect Free: Manage Subscribers', // page title
            'List Subscribers',                    // menu title
            'manage_options',                      // capability
            'architect_list_subs',               // menu slug
            'architect_list_subscribers'         // callable function
            );
        add_submenu_page(
            'architect_Admin',                // parent menu slug
            'Architect Free: Add a New Subscriber', // page title
            'Add Subscribers',                  // menu title
            'manage_options',                   // capability
            'architect_add_subs',             // menu slug
            'architect_add_subscribers'       // callable function
            );
}

// call add action func to build the menu
add_action('admin_menu', 'arch_menu');

/* ========================================= */
/* page / menu calling functions             */
/* ========================================= */

// function for default Admin page
function architect_config_page() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-config-page.php");
}

// function for editing existing subscribers page
function architect_list_subscribers() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-list-subscribers.php");
}

// function for adding new subscribers page
function architect_add_subscribers() {
    // check user capabilities
    if (!current_user_can('manage_options')) { return; }
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-add-subscribers.php");
}

/* ================================== */
/* Add action for the contacts widget */
/* ================================== */
add_action('widgets_init', 'arch_register_contacts_widget');

/* ============================================== */
/* Add contacts widget function                   */
/* This registers the architect_contacts_widget       */
/* ============================================== */
function arch_register_contacts_widget() {
    // parameter is the class name in following required file
    register_widget('arch_contacts_widget') ;  
}

require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-contacts-widget.php");
/* ================================================== */
/* Add action & function calls for a dashboard widget */
/* ================================================== */

add_action('wp_dashboard_setup', 'architect_dashbaord_sample');

function architect_dashbaord_sample() {
    wp_add_dashboard_widget('dashboard_custom_feed', 'Architect Free Plugin Info','dashboard_example_display') ;
}

function dashboard_example_display() {
    echo "<font color=red>Architect Plugin Demo - help is on the way! </font>" ;
}

/* ========================== */
/* Add shortcode for contacts */
/* ========================== */
add_shortcode('architect_contacts', 'arch_contacts_scode');

/* ======================================= */
/* Add contacts short code function        */
/* This registers the architect_contacts_short */
/* ======================================= */

function arch_contacts_scode() {
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-subscribers-scode.php");
}

/* ============================================== */
/* Add action hook for correspondence on new post */
/* ============================================== */
add_action( 'pending_to_publish', 'arch_new_post_send_notify');

add_action( 'draft_to_publish', 'arch_new_post_send_notify');

function arch_new_post_send_notify( $post ) {
    global $wpdb ;
    $result = $wpdb->get_row( $wpdb->prepare("SELECT `email_updates` FROM `architect_control`
        WHERE `architect_control_id` = %d", 1)  );
    // If this is a revision, don't send the correspondence.
    if (wp_is_post_revision( $post->ID )) return;
    
    // this is also triggered on a page publishing, so ensure that the type is a Post and then carry on
    if (get_post_type($post->ID) === 'post') {
        // only send out correspondence if set in control / admin
        if ($result->email_updates) { require_once(ARCHITECT_PLUGIN_FILEPATH . "architect-send-mass-email.php"); }
    }
}

/* ================================= */
/* Add filter hook for subscriptions */
/* ================================= */
function architect_vars($vars) {
    $vars[] = 'arch_subscribe';
    $vars[] = 'arch_unsubscribe';    
    $vars[] = 'rcwebhook';
    return $vars;
}

add_filter('query_vars', 'architect_vars');

function architect_handle_vars() {
    global $wpdb;
    $subscribe = get_query_var('arch_subscribe');
    $unsubscribe = get_query_var('arch_unsubscribe');    
    
    if (!empty($subscribe)) {
        $token_id = $subscribe;
        require_once(ARCHITECT_PLUGINDIR . "includes/architect-confirm-optin.php");
    } elseif (!empty($unsubscribe)) {
        $token_id = $unsubscribe;
        require_once(ARCHITECT_PLUGINDIR . "includes/architect-unsubscribe.php");
    }
}

add_action('parse_query', 'architect_handle_vars');
    
/* ============================================= */
/* Add registration hook for plugin installation */
/* ============================================= */
register_activation_hook(__FILE__, 'architect_install');
register_activation_hook(__FILE__, 'activation_code');

function architect_install() {
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-install.php");
}

/* ========================================= */
/* Create default pages on plugin activation */
/* ========================================= */
function activation_code(){
    require_once(ARCHITECT_PLUGIN_FILEPATH_INCLUDES . "architect-activation.php");
}

/* ================================ */
/* bring in generic architect functions */
/* ================================ */
require_once("includes/architect-functions.php");


?>