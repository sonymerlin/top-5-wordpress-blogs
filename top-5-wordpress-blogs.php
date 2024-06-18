<?php
/*
Plugin Name: Top 5 WordPress Blogs
Description: A custom block to display the top 5 WordPress blogs.
Version: 1.0
Author: Sony
*/

function top_5_wp_blogs_register_block() {
    // Register the block editor script
    wp_register_script(
        'top-5-wp-blogs-editor-script',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
        filemtime(plugin_dir_path(__FILE__) . 'block.js')
    );

    // Register the block editor styles
    wp_register_style(
        'top-5-wp-blogs-editor-style',
        plugins_url('editor.css', __FILE__),
        array('wp-edit-blocks'),
        filemtime(plugin_dir_path(__FILE__) . 'editor.css')
    );

    // Register the block front-end styles
    wp_register_style(
        'top-5-wp-blogs-style',
        plugins_url('style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'style.css')
    );

    // Register the block
    register_block_type('top-5-wp-blogs/top-5-wordpress-blogs', array(
        'editor_script' => 'top-5-wp-blogs-editor-script',
        'editor_style'  => 'top-5-wp-blogs-editor-style',
        'style'         => 'top-5-wp-blogs-style',
    ));
}
add_action('init', 'top_5_wp_blogs_register_block');


function render_top_5_wp_blogs($attributes) {
    $args = array(
        'posts_per_page' => $attributes['numberOfPosts'],
        'order' => $attributes['orderBy'],
        'orderby' => $attributes['order'] === 'title' ? 'name' : 'date',
    );

    $query = new WP_Query($args);
    $posts = $query->posts;

    $output = '<div class="top-5-wp-blogs-grid">';

    foreach ($posts as $post) {
        $post_id = $post->ID;
        $title = get_the_title($post_id);
        $link = get_permalink($post_id);
        $excerpt = get_the_excerpt($post_id);
        $thumbnail = get_the_post_thumbnail_url($post_id, array(300, 240));

        $output .= '<div class="top-5-wp-blogs-item">';
        if ($thumbnail) {
            $output .= '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($title) . '" />';
        }
        $output .= '<h2><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></h2>';
        $output .= '<p>' . esc_html($excerpt) . '</p>';
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

register_block_type('top-5-wp-blogs/top-5-wordpress-blogs', array(
    'editor_script' => 'top-5-wp-blogs-editor-script',
    'editor_style'  => 'top-5-wp-blogs-editor-style',
    'style'         => 'top-5-wp-blogs-style',
    'render_callback' => 'render_top_5_wp_blogs',
));

