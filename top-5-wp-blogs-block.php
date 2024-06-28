<?php
/**
 * Plugin Name: Top 5 WordPress Blogs Block
 * Description: A custom Gutenberg block to display the top 5 WordPress blogs in a 3-column grid layout.
 * Version: 1.0.1
 * Author: Sony Merlin
 * Text Domain: top-5-wp-blogs-block
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function register_top_5_wp_blogs_block() {
    // Register block editor script
    wp_register_script(
        'top-5-wp-blogs-block-editor-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data' )
    );

    // Register block editor styles
    wp_register_style(
        'top-5-wp-blogs-block-editor-style',
        plugins_url( 'editor.css', __FILE__ ),
        array( 'wp-edit-blocks' )
    );

    // Register block frontend styles
    wp_register_style(
        'top-5-wp-blogs-block-style',
        plugins_url( 'style.css', __FILE__ ),
        array()
    );

    // Register the block
    register_block_type( 'custom/top-5-wp-blogs', array(
        'editor_script' => 'top-5-wp-blogs-block-editor-script',
        'editor_style'  => 'top-5-wp-blogs-block-editor-style',
        'style'         => 'top-5-wp-blogs-block-style',
        'render_callback' => 'render_top_5_wp_blogs_block',
        'attributes'    => array(
            'orderBy' => array(
                'type'    => 'string',
                'default' => 'DESC'
            ),
            'order' => array(
                'type'    => 'string',
                'default' => 'publish_date'
            ),
            'noOfDisplay' => array(
                'type'    => 'number',
                'default' => 5
            ),
        ),
    ));
}
add_action( 'init', 'register_top_5_wp_blogs_block' );

function render_top_5_wp_blogs_block( $attributes ) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $attributes['noOfDisplay'],
        'orderby'        => $attributes['order'] === 'name' ? 'title' : 'date',
        'order'          => $attributes['orderBy'],
    );

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        $output = '<div class="top-5-wp-blogs-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $output .= '<div class="top-5-wp-blog-item">';
            $output .= get_the_post_thumbnail( get_the_ID(), array( 300, 240 ) );
            $output .= '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            $output .= '<p>' . get_the_excerpt() . '</p>';
            $output .= '</div>';
        }
        $output .= '</div>';
    }
    wp_reset_postdata();
    return $output;
}
