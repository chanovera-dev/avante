<?php
/**
 * ACF Helper Functions for Avante Theme
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Renders an SVG icon by its attachment ID, theme key, URL, or fallback.
 */
if ( ! function_exists( 'avante_render_acf_icon' ) ) {
    function avante_render_acf_icon( $icon ) {
        if ( ! $icon ) {
            return '';
        }
        if ( is_numeric( $icon ) ) {
            $svg_path = get_attached_file( $icon );
            if ( $svg_path && file_exists( $svg_path ) ) {
                return file_get_contents( $svg_path );
            }
            return wp_get_attachment_image( $icon, 'full' );
        }
        if ( is_string( $icon ) && filter_var( $icon, FILTER_VALIDATE_URL ) ) {
            if ( pathinfo( parse_url( $icon, PHP_URL_PATH ), PATHINFO_EXTENSION ) === 'svg' ) {
                $url_path = parse_url( $icon, PHP_URL_PATH );
                $absolute_path = ABSPATH . ltrim( $url_path, '/' );
                if ( file_exists( $absolute_path ) ) {
                    return file_get_contents( $absolute_path );
                }
            }
            return '<img src="' . esc_url( $icon ) . '" alt="" class="svg-icon">';
        }
        return avante_get_icon( $icon ) ?: $icon;
    }
}
