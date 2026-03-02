<?php

/**
 * MF Data Dev Headless Theme
 *
 * Registers custom post types and exposes them to the REST API
 * for the custom PHP/Twig frontend.
 */

// Register "Project" custom post type
function mfdd_register_project_post_type()
{
    register_post_type('project', [
        'labels' => [
            'name' => 'Projects',
            'singular_name' => 'Project',
            'add_new' => 'Add New Project',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
            'view_item' => 'View Project',
            'all_items' => 'All Projects',
            'search_items' => 'Search Projects',
        ],
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'project',
        'has_archive' => true,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'menu_icon' => 'dashicons-portfolio',
    ]);
}
add_action('init', 'mfdd_register_project_post_type');

// Register custom fields for projects
function mfdd_register_project_meta()
{
    $fields = [
        'tech_stack' => ['type' => 'string', 'description' => 'Comma-separated list of technologies'],
        'live_url' => ['type' => 'string', 'description' => 'URL to live project'],
        'github_url' => ['type' => 'string', 'description' => 'URL to GitHub repository'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('project', $key, [
            'show_in_rest' => true,
            'single' => true,
            'type' => $args['type'],
            'description' => $args['description'],
        ]);
    }
}
add_action('init', 'mfdd_register_project_meta');

// Add meta boxes for project custom fields in the admin
function mfdd_add_project_meta_boxes()
{
    add_meta_box(
        'project_details',
        'Project Details',
        'mfdd_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mfdd_add_project_meta_boxes');

function mfdd_project_details_callback($post)
{
    wp_nonce_field('mfdd_project_details', 'mfdd_project_nonce');

    $tech_stack = get_post_meta($post->ID, 'tech_stack', true);
    $live_url = get_post_meta($post->ID, 'live_url', true);
    $github_url = get_post_meta($post->ID, 'github_url', true);

    echo '<table class="form-table"><tbody>';

    echo '<tr><th><label for="tech_stack">Tech Stack</label></th>';
    echo '<td><input type="text" id="tech_stack" name="tech_stack" value="' . esc_attr($tech_stack) . '" class="regular-text">';
    echo '<p class="description">Comma-separated (e.g., Python, SQL, Airflow)</p></td></tr>';

    echo '<tr><th><label for="live_url">Live URL</label></th>';
    echo '<td><input type="url" id="live_url" name="live_url" value="' . esc_attr($live_url) . '" class="regular-text"></td></tr>';

    echo '<tr><th><label for="github_url">GitHub URL</label></th>';
    echo '<td><input type="url" id="github_url" name="github_url" value="' . esc_attr($github_url) . '" class="regular-text"></td></tr>';

    echo '</tbody></table>';
}

function mfdd_save_project_details($post_id)
{
    if (!isset($_POST['mfdd_project_nonce']) || !wp_verify_nonce($_POST['mfdd_project_nonce'], 'mfdd_project_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = ['tech_stack', 'live_url', 'github_url'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_project', 'mfdd_save_project_details');

// Enable featured images
add_theme_support('post-thumbnails');
