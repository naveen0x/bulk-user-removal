<?php
/*
Plugin Name: Bulk Users Remove
Description: A plugin to search and delete registered users by different criteria.
Version: 1.0
Author: Naveen Ranasinghe
Website: https://www.linkedin.com/in/ranasingheny/
*/

// Define plugin paths and URLs
define('BULK_USERS_REMOVE_DIR', plugin_dir_path(__FILE__));
define('BULK_USERS_REMOVE_URL', plugin_dir_url(__FILE__));

// Enqueue scripts and styles
function bulk_users_remove_enqueue_scripts() {
    wp_enqueue_style('bulk_users_remove_style', BULK_USERS_REMOVE_URL . 'assets/style.css');
    wp_enqueue_style('datatables_style', 'https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables_script', 'https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js', array('jquery'), '1.11.4', true);
    wp_enqueue_script('bulk_users_remove_script', BULK_USERS_REMOVE_URL . 'assets/script.js', array('jquery', 'datatables_script'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'bulk_users_remove_enqueue_scripts');

// Include the necessary files
require_once(BULK_USERS_REMOVE_DIR . 'includes/search_users.php');
require_once(BULK_USERS_REMOVE_DIR . 'includes/delete_users.php');

// Add admin menu page
function bulk_users_remove_add_menu_page() {
    add_menu_page(
        'User Search Plugin',
        'User Search',
        'manage_options',
        'user-search',
        'bulk_users_remove_search_users_page',
        'dashicons-search',
        50
    );
}
add_action('admin_menu', 'bulk_users_remove_add_menu_page');
