<?php
/**
 * Template part for displaying aside format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */
$post_id = get_the_ID();
$likes_count = avante_get_likes_count($post_id);
$has_liked = avante_user_has_liked($post_id);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body">
        <div class="post--content is-layout-constrained">
            <?php the_content(); ?>
        </div>
        <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
            <?= avante_get_icon('date'); ?>
            <p><?= get_the_date('F j, Y'); ?></p>
        </div>
    </div>
    <div class="post_footer">
        <div class="format-type">
            <?php echo '<a href="' . esc_url(get_post_format_link('aside')) . '" class="format-post-tag">' . avante_get_icon('aside') . '</a>'; ?>
        </div>
        <div class="post--tags__wrapper">
            <div class="post--tags">
                <?php
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="post-tag small">' . avante_get_icon('tag') . esc_html($tag->name) . '</a>';
                    }
                }
                ?>
            </div>
        </div>
        <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
            <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
            <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
        </button>
    </div>
</article>