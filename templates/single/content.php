<?php
/**
 * Template part for displaying content posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */

if (has_post_thumbnail()) {
    echo '<div class="featured-picture--wrapper">';
    echo '<div class="featured-picture__overlay"></div>';
    echo get_the_post_thumbnail(null, 'full', ['alt' => get_the_title(), 'loading' => 'lazy']);
    echo '</div>';
}

the_content();

wp_link_pages(
    array(
        'before' => '<div class="page-links">' . __('Páginas:', 'avante'),
        'after' => '</div>',
    )
);