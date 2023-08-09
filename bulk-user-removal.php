<?php
/*
Plugin Name: Bulk User Removal
Description: A plugin to search and delete registered users by different criteria.
Version: 1.0
Author: Naveen Ranasinghe
Author URI: https://www.linkedin.com/in/ranasingheny
Plugin URI: https://github.com/naveen0x/bulk-user-removal
License: GPLv2 or later
License URI: https://github.com/naveen0x/bulk-user-removal/blob/350845e58ebc60e9f6ab31445ff2809b2a1cfc20/LICENSE
*/

// Define plugin paths and URLs
define('BULK_USER_REMOVAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BULK_USER_REMOVAL_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enqueue scripts and styles
function bulk_user_removal_plugin_enqueue_scripts() {
    wp_enqueue_style('bulk_user_removal_plugin_style', BULK_USER_REMOVAL_PLUGIN_URL . 'assets/style.css');
    wp_enqueue_style('datatables_style', 'https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables_script', 'https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js', array('jquery'), '1.11.4', true);
    wp_enqueue_script('bulk_user_removal_plugin_script', BULK_USER_REMOVAL_PLUGIN_URL . 'assets/script.js', array('jquery', 'datatables_script'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'bulk_user_removal_plugin_enqueue_scripts');

// Include the necessary files
require_once(BULK_USER_REMOVAL_PLUGIN_DIR . 'includes/search_users.php');
require_once(BULK_USER_REMOVAL_PLUGIN_DIR . 'includes/delete_users.php');

// Add admin menu page
function bulk_user_removal_plugin_add_menu_page() {
    add_menu_page(
        'Bulk User Removal Plugin',
        'Bulk User Removal',
        'manage_options',
        'bulk-user-removal',
        'bulk_user_removal_plugin_search_users_page',
        'dashicons-trash',
        50
    );
}
add_action('admin_menu', 'bulk_user_removal_plugin_add_menu_page');
