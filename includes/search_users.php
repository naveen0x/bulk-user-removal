<?php
// Function to handle the search and retrieve matching users
function bulk_user_delete_search_users() {
    $search_results = array();

    if (isset($_POST['search_users_btn'])) {
        $search_term = sanitize_text_field($_POST['search_term']);
        $search_criteria = sanitize_text_field($_POST['search_criteria']);

        $args = array(
            'search' => '*' . $search_term . '*', // Search for partial name, username, or email
            'search_columns' => array(
                'user_login',
                'user_email',
                'display_name',
            ),
            'fields' => 'all',
        );

        // Filter by role
        if (!empty($_POST['user_role'])) {
            $args['role'] = sanitize_text_field($_POST['user_role']);
        }

        // Filter by account creation date range
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $args['date_query'] = array(
                array(
                    'after' => $_POST['start_date'],
                    'before' => $_POST['end_date'],
                    'inclusive' => true,
                ),
            );
        }

        $users = get_users($args);

        if (!empty($users)) {
            foreach ($users as $user) {
                $search_results[] = array(
                    'ID' => $user->ID,
                    'user_login' => $user->user_login,
                    'user_email' => $user->user_email,
                    'display_name' => $user->display_name,
                    'role' => implode(', ', $user->roles),
                    'registered_date' => $user->user_registered,
                );
            }
        }
    }

    return $search_results;
}

// Function to display the search form and search results
function bulk_user_delete_search_users_page() {
    // Get the search results when the form is submitted
    $search_results = bulk_user_delete_search_users();

    // Handle user deletion when "Delete Selected" button is clicked
    if (isset($_POST['delete_users_btn']) && isset($_POST['user_ids']) && is_array($_POST['user_ids'])) {
        $deleted_count = bulk_user_delete_delete_users($_POST['user_ids']);
        if ($deleted_count > 0) {
            echo '<div class="notice notice-success"><p>' . $deleted_count . ' users were successfully deleted.</p></div>';
        }
    }

    // Output the search form
    echo '<div class="wrap">';
    echo '<h1>User Search</h1>';
    echo '<form method="post">';
    echo '<input type="hidden" id="user_search_action" value="search_users" />';
    echo '<label for="search_term">Search Term:</label>';
    echo '<input type="text" name="search_term" id="search_term" />';
    echo '<label for="search_criteria">Search Criteria:</label>';
    echo '<select name="search_criteria" id="search_criteria">';
    echo '<option value="display_name">Display Name</option>';
    echo '<option value="user_login">Username</option>';
    echo '<option value="user_email">Email</option>';
    echo '</select>';

    // Add role filter
    $roles = wp_roles()->get_names();
    echo '<label for="user_role">Filter by Role:</label>';
    echo '<select name="user_role" id="user_role">';
    echo '<option value="">All</option>';
    foreach ($roles as $role => $name) {
        echo '<option value="' . esc_attr($role) . '">' . esc_html($name) . '</option>';
    }
    echo '</select>';

    echo '<label for="start_date">Start Date:</label>';
    echo '<input type="date" name="start_date" id="start_date" />';
    echo '<label for="end_date">End Date:</label>';
    echo '<input type="date" name="end_date" id="end_date" />';
    echo '<input type="submit" name="search_users_btn" value="Search" class="button-primary" />';
    echo '</form>';

    // Output the search results table
    if (!empty($search_results)) {
        echo '<h2>Search Results</h2>';
        echo '<form method="post">';
        echo '<input type="hidden" name="user_search_action" value="delete_users" />';
        echo '<table id="user_search_results" class="display">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="check-column">';
        echo '<label class="screen-reader-text" for="cb-select-all-1">Select All</label>';
        echo '<input type="checkbox" id="cb-select-all-1" />';
        echo '</th>';
        echo '<th>ID</th>';
        echo '<th>Username</th>';
        echo '<th>Email</th>';
        echo '<th>Display Name</th>';
        echo '<th>Role</th>';
        echo '<th>Registered Date</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($search_results as $user) {
            echo '<tr>';
            echo '<td class="check-column">';
            echo '<input type="checkbox" name="user_ids[]" value="' . esc_attr($user['ID']) . '" />';
            echo '</td>';
            echo '<td>' . esc_html($user['ID']) . '</td>';
            echo '<td>' . esc_html($user['user_login']) . '</td>';
            echo '<td>' . esc_html($user['user_email']) . '</td>';
            echo '<td>' . esc_html($user['display_name']) . '</td>';
            echo '<td>' . esc_html($user['role']) . '</td>';
            echo '<td>' . esc_html($user['registered_date']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<input type="submit" name="delete_users_btn" value="Delete Selected" class="button-primary" onclick="return confirmDelete();" />';
        echo '</form>';
    } else {
        echo '<p>No users found matching the search criteria.</p>';
    }
    
    echo '<script>
            jQuery(document).ready(function($) {
                $("#user_search_results").DataTable({
                    pageLength: 50, // Set the default number of entries per page
                    lengthMenu: [10, 25, 50, 100, 250, 500],
                    processing: true,
                });
            });
        </script>';

    echo '</div>';
    ?>
    <script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete the selected users? This action cannot be undone.');
    }
    
    </script>
    <?php
}
