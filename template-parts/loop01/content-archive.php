<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body glass-border-bright">
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            echo '<a href="' . esc_url(get_post_format_link('standard')) . '" class="format-post-tag">' . avante_get_icon('category') . esc_html(__('Estándar', 'avante')) . '</a>';
            ?>
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post--content">
            <?php get_template_part('templates/single/tags'); ?>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= get_the_date('F j, Y'); ?></p>
            </div>
            <a href="<?= get_the_permalink(); ?>" class="post__permalink">
                <?php the_title('<h2 class="post__title">', '</h2>'); ?>
            </a>
            <?php get_template_part('templates/single/author'); ?>
        </div>
        <?php
        if (has_post_thumbnail()) {
            echo get_the_post_thumbnail(null, 'loop-thumbnail', ['alt' => get_the_title(), 'loading' => 'lazy']);
        }
        ?>
    </div>
</article>