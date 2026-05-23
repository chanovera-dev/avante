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

/**
 * Renders the HTML for the post like button with full accessibility (ARIA, roles, type).
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Like button HTML.
 */
function avante_render_like_button($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    if (!$post_id) {
        return '';
    }

    $likes_count = avante_get_likes_count($post_id);
    $has_liked = avante_user_has_liked($post_id);

    $is_active = ($has_liked || $likes_count > 0);
    $class = 'button__like' . ($is_active ? ' liked' : '');
    $icon = avante_get_icon($is_active ? 'heart-fill' : 'heart');
    
    $aria_label = $has_liked 
        ? __('Quitar me gusta a esta publicación', 'avante') 
        : __('Dar me gusta a esta publicación', 'avante');

    ob_start();
    ?>
    <button type="button" 
            class="<?php echo esc_attr($class); ?>" 
            aria-label="<?php echo esc_attr($aria_label); ?>" 
            aria-pressed="<?php echo $has_liked ? 'true' : 'false'; ?>">
        <?php echo $icon; ?>
        <span class="like-count"><?php echo $likes_count > 0 ? esc_html($likes_count) : ''; ?></span>
    </button>
    <?php
    return ob_get_clean();
}
