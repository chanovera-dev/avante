<?php
/**
 * Post Likes Functionality
 *
 * Handles getting, checking, and updating likes for posts via AJAX.
 *
 * @package Avante
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Handler for Post Likes
 */
function avante_handle_post_like() {
    // Verify nonce for security if possible, but for simple likes often skipped or handled differently
    // check_ajax_referer('avante_likes_nonce', 'security');

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id) {
        wp_send_json_error('ID de post no válido.');
    }

    // Get current likes
    $likes = get_post_meta($post_id, '_avante_likes_count', true);
    $likes = $likes ? intval($likes) : 0;

    // Use a cookie to record that the user has liked this post (simple client-side check)
    $cookie_name = 'avante_liked_' . $post_id;
    $is_liked = isset($_COOKIE[$cookie_name]);

    if ($is_liked) {
        // Unlike
        $likes = max(0, $likes - 1);
        setcookie($cookie_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
        $action = 'unliked';
    } else {
        // Like
        $likes++;
        setcookie($cookie_name, '1', time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN); // 30 days
        $action = 'liked';
    }

    update_post_meta($post_id, '_avante_likes_count', $likes);

    wp_send_json_success([
        'likes' => $likes,
        'action' => $action,
        'icon' => avante_get_icon(($action === 'liked' || $likes > 0) ? 'heart-fill' : 'heart')
    ]);
}
add_action('wp_ajax_avante_post_like', 'avante_handle_post_like');
add_action('wp_ajax_nopriv_avante_post_like', 'avante_handle_post_like');

/**
 * Helper to get likes count
 */
function avante_get_likes_count($post_id) {
    $likes = get_post_meta($post_id, '_avante_likes_count', true);
    return $likes ? intval($likes) : 0;
}

/**
 * Helper to check if user liked a post
 */
function avante_user_has_liked($post_id) {
    return isset($_COOKIE['avante_liked_' . $post_id]);
}
