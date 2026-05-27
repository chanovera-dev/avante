<?php
/**
 * Theme functions and definitions
 *
 * This file acts as the central hub for the Avante theme's functionality.
 * It is responsible for:
 *  - Defining theme constants (e.g., version).
 *  - Implementing security measures to prevent direct file access.
 *  - Loading modular components from the /inc directory (core setup, custom features, template tags).
 *
 * The modular architecture ensures maintainability by conditionally loading
 * only the necessary files found in the includes directory.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Avante
 * @since Avante 1.0.0
 */

// Prevent direct access to this file for security reasons.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Theme version constant (safe: only define if not already defined).
 */
$theme = wp_get_theme();
$version = $theme && method_exists($theme, 'get') ? $theme->get('Version') : '1.0.0';

if (!defined('AVANTE_VERSION')) {
    define('AVANTE_VERSION', (string) $version);
}

/**
 * Load optional theme components from the /inc directory.
 * Note: files are included only if they exist.
 */
$inc_files = array(
    'core' => 'inc/core.php',
    'options-page' => 'inc/options-page.php',
    'likes' => 'inc/likes.php',
    'seo' => 'inc/seo-meta.php',
);

foreach ($inc_files as $key => $relative_path) {
    $path = __DIR__ . '/' . $relative_path;
    if (file_exists($path)) {
        require_once $path;
    }
}

/**
 * Conditionally load real estate tools only if the CPT 'property' is registered.
 * Hooked to init with a high priority (99) to ensure plugins
 * have already registered the custom post type.
 */
add_action('init', function() {
    if (post_type_exists('property')) {
        $real_estate_files = array(
            'sync-properties' => 'inc/easybroker-sync.php',
            'real-estate-tools' => 'inc/real-estate-tools.php',
        );

        foreach ($real_estate_files as $key => $relative_path) {
            $path = __DIR__ . '/' . $relative_path;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
}, 99);

/**
 * TEMPORAL: Limpiar transients de link preview.
 * BORRAR este bloque después de cargar cualquier página.
 */
// add_action('init', function() {
//     global $wpdb;
//     $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%avante_link_preview_%'");
// }, 1);
/**
 * Fetch URL metadata for link posts
 */
function avante_fetch_url_metadata($url) {
    $transient_key = 'avante_link_preview_' . md5($url);
    $meta = get_transient($transient_key);
    if (false !== $meta) return $meta;

    $meta = [
        'title' => '', 'image' => '', 'author' => '', 'date' => '', 'site_name' => '', 'author_avatar' => '', 'tags' => [], 'http_status' => ''
    ];

    $args = [
        'timeout' => 8,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36'
    ];
    $response = wp_remote_get($url, $args);
    
    $http_code = wp_remote_retrieve_response_code($response);
    $meta['http_status'] = 'Code: ' . $http_code;

    if (!is_wp_error($response) && $http_code === 200) {
        $html = wp_remote_retrieve_body($response);
        if (stripos($html, '<title>Just a moment</title>') === false) {
            // Basic Title
            if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) $meta['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            elseif (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) $meta['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

            // Image
            if (preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) $meta['image'] = $matches[1];

            // Author/Site
            if (preg_match('/<meta[^>]*property=["\']og:site_name["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) $meta['site_name'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            if (preg_match('/<meta[^>]*name=["\']author["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) $meta['author'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            
            // Date
            if (preg_match('/<meta[^>]*property=["\']article:published_time["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
                $ts = strtotime($matches[1]);
                if ($ts) $meta['date'] = date_i18n('F j, Y', $ts);
            }

            // Tags — from article:tag (Open Graph) or meta keywords
            if (preg_match_all('/<meta[^>]*property=["\']article:tag["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $tag_matches)) {
                $meta['tags'] = array_map('trim', $tag_matches[1]);
            } elseif (preg_match('/<meta[^>]*name=["\']keywords["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $kw_match)) {
                $meta['tags'] = array_map('trim', explode(',', $kw_match[1]));
            }
            
            // Avatar fallback (logo, icon or apple icon)
            if (preg_match('/<link[^>]*rel=["\'](?:shortcut icon|icon|apple-touch-icon.*?)["\'][^>]*href=["\']([^"\']+)["\']/is', $html, $logo)) {
                $icon_url = $logo[1];
                if (strpos($icon_url, 'http') !== 0) {
                    if (strpos($icon_url, '//') === 0) {
                        $icon_url = 'https:' . $icon_url;
                    } else {
                        $parsed = parse_url($url);
                        $icon_url = rtrim($parsed['scheme'] . '://' . $parsed['host'], '/') . '/' . ltrim($icon_url, '/');
                    }
                }
                $meta['author_avatar'] = $icon_url;
            } else {
                // Manually check /favicon.ico if not found in HTML
                $parsed = parse_url($url);
                $favicon_url = $parsed['scheme'] . '://' . $parsed['host'] . '/favicon.ico';
                $fav_response = wp_remote_head($favicon_url, ['timeout' => 3]);
                if (!is_wp_error($fav_response) && wp_remote_retrieve_response_code($fav_response) === 200) {
                    $meta['author_avatar'] = $favicon_url;
                }
            }
        }
    }

    set_transient($transient_key, $meta, 12 * HOUR_IN_SECONDS);
    return $meta;
}

/**
 * Get link post data for templates
 */
function avante_get_link_post_data($post) {
    $content = get_the_content(null, false, $post);
    $url = preg_match('/https?:\/\/[^\s"\'<>]+/', $content, $matches) ? $matches[0] : '';
    
    $data = [
        'url' => $url,
        'title' => get_the_title($post),
        'image' => get_the_post_thumbnail_url($post, 'full'),
        'date' => get_the_date('F j, Y', $post),
        'site_name' => '',
        'author_name' => '',
        'author_avatar' => '',
        'external_tags' => [],
        'http_status' => ''
    ];

    if ($url) {
        $meta = avante_fetch_url_metadata($url);
        if (!empty($meta['title'])) $data['title'] = $meta['title'];
        if (!empty($meta['image'])) $data['image'] = $meta['image'];
        if (!empty($meta['date'])) $data['date'] = $meta['date'];
        if (!empty($meta['site_name'])) $data['site_name'] = $meta['site_name'];
        
        $domain = parse_url($url, PHP_URL_HOST);
        $data['author_name'] = !empty($meta['author']) ? $meta['author'] : (!empty($meta['site_name']) ? $meta['site_name'] : preg_replace('/^www\./', '', $domain));
        $data['author_avatar'] = !empty($meta['author_avatar']) ? $meta['author_avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($data['author_name']) . '&background=random&color=fff&size=128';
        $data['external_tags'] = $meta['tags'];
        $data['http_status'] = $meta['http_status'];
    }

    if (empty($data['author_name']) || filter_var($data['author_name'], FILTER_VALIDATE_URL)) {
        $data['author_name'] = __('Editor', 'avante');
    }

    return $data;
}

/**
 * AJAX handler for filtering news posts by post format
 */
add_action('wp_ajax_filter_news_posts', 'avante_filter_news_posts');
add_action('wp_ajax_nopriv_filter_news_posts', 'avante_filter_news_posts');

function avante_filter_news_posts() {
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : 'all';

    $args = [
        'post_type'      => 'news',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ];

    if ($format !== 'all') {
        if ($format === 'standard') {
            // Standard format means no specific post format term is assigned
            $args['tax_query'] = [
                [
                    'taxonomy' => 'post_format',
                    'field'    => 'slug',
                    'terms'    => ['post-format-aside', 'post-format-image', 'post-format-video', 'post-format-quote', 'post-format-link', 'post-format-gallery', 'post-format-audio'],
                    'operator' => 'NOT IN'
                ]
            ];
        } else {
            // Specific post format like 'video', 'link'
            $args['tax_query'] = [
                [
                    'taxonomy' => 'post_format',
                    'field'    => 'slug',
                    'terms'    => 'post-format-' . $format,
                ]
            ];
        }
    }

    global $wp_query;
    $wp_query = new WP_Query($args);

    ob_start();
    get_template_part('templates/archive/wp', 'loop');
    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(['html' => $html]);
}
