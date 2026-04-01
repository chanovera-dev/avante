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
add_action('init', function() {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%avante_link_preview_%'");
}, 1);