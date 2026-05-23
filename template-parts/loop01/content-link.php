<?php
/**
 * Template part for displaying link format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package avante
 * @since 1.0.0
 */

// Obtener los datos del post link desde nuestra nueva función global
$link_data = avante_get_link_post_data(get_post());

$url           = $link_data['url'];
$title         = $link_data['title'];
$image         = $link_data['image'];
$date          = $link_data['date'];
$author_name   = $link_data['author_name'];
$author_avatar = $link_data['author_avatar'];
$external_tags = $link_data['external_tags'];
$http_status   = $link_data['http_status'];

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body glass-border-bright">
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            echo '<a href="' . esc_url(get_post_format_link('link')) . '" class="format-post-tag">' . avante_get_icon('link') . esc_html(__('Enlace', 'avante')) . '</a>';
            ?>
            <?php echo avante_render_like_button(); ?>
        </div>
        <div class="post--content">
            <div class="post--tags">
                <?php
                if (!empty($external_tags)) {
                    foreach ($external_tags as $tag_name) {
                        echo '<span class="post-tag">' . avante_get_icon('tag') . esc_html($tag_name) . '</span>';
                    }
                } else {
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a class="post-tag" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . avante_get_icon('tag') . esc_html($tag->name) . '</a>';
                        }
                    }
                }
                ?>
            </div>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= esc_html($date); ?></p>
            </div>
            <a href="<?= esc_url($url); ?>" class="post__permalink">
                <h2 class="post__title"><?= esc_html($title); ?></h2>
            </a>
            <div class="post--author">
                <?php 
                if ($author_avatar) {
                    echo '<img class="avatar avatar-70 photo" src="' . esc_url($author_avatar) . '" width="70" height="70" alt="' . esc_attr($author_name) . '" loading="lazy" />';
                } else {
                    echo get_avatar(get_the_author_meta('email'), '70');
                }
                ?>
                <h3 class="author-name">
                    <?= esc_html($author_name); ?>
                </h3>
                <span class="author-description">
                    <?= esc_html(preg_replace('/^www\./', '', wp_parse_url($url, PHP_URL_HOST))); ?>
                </span>
            </div>
        </div>
        <!-- Link Preview Image: <?= esc_html($image ?: 'Empty'); ?> | Debug: <?= esc_html($http_status) ?> -->
        <?php if ($image): ?>
            <img class="wp-post-image" src="<?= esc_url($image); ?>" alt="<?= esc_attr($title); ?>" loading="lazy" />
        <?php endif; ?>
    </div>
</article>