<?php
// Function to delete selected users in bulk
function bulk_user_removal_plugin_delete_users($user_ids) {
    if (!current_user_can('delete_users')) {
        return 0;
    }

    $deleted_count = 0;
    $batch_size = 50; // Set the number of users to delete per batch

    if (!empty($user_ids)) {
        $user_ids_chunks = array_chunk($user_ids, $batch_size);

        foreach ($user_ids_chunks as $chunk) {
            foreach ($chunk as $user_id) {
                // Check if the user to be deleted is not the administrator
                if ($user_id != get_current_user_id()) {
                    // Add your logic to delete users here
                    // You might want to perform additional checks before deleting the user.
                    // For example, check if the user has a specific role before deleting.
                    wp_delete_user($user_id);
                    $deleted_count++;
                }
            }
            sleep(2); // Add a delay between batches (e.g., 2 seconds)
        }
    }

    return $deleted_count;
}
