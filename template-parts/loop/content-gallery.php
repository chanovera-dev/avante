<?php
/**
 * Template part for displaying gallery format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */

$a = avante_get_assets();
require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
avante_enqueue_script('loop-gallery', $a['js']['loop-gallery']);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body">
        <div class="gallery-wrapper">
            <div class="gallery" style="display: flex;">
                <?php
                if (function_exists('avante_extract_gallery_images')) {

                    $ids = avante_extract_gallery_images(get_the_ID());

                    if (!empty($ids)) {
                        foreach ($ids as $id) {
                            echo '<div class="slide">';
                            echo wp_get_attachment_image($id, 'loop-thumbnail', false, ['style' => 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;']);
                            echo '</div>';
                        }
                    }

                }
                ?>
            </div>
            <div class="gallery-navigation" style="display: flex; align-items: center;">
                <button class="gallery-prev btn-pagination small-pagination"
                    aria-label="Foto anterior"><?= avante_get_icon('backward'); ?></button>
                <div class="loop-gallery-bullets"></div>
                <button class="gallery-next btn-pagination small-pagination"
                    aria-label="Foto siguiente"><?= avante_get_icon('forward'); ?></button>
            </div>
        </div>
        <div class="post_body__overlay"></div>
        <div class="post_body__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            echo '<a href="' . esc_url(get_post_format_link('gallery')) . '" class="format-post-tag">' . avante_get_icon('gallery') . '</a>';
            ?>
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post_body__content">
            <?php get_template_part('templates/single/tags'); ?>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= get_the_date('F j, Y'); ?></p>
            </div>
            <a href="<?= get_the_permalink(); ?>" class="post_body__permalink">
                <?php the_title('<h2 class="post--title">', '</h2>'); ?>
            </a>
            <?php get_template_part('templates/single/author'); ?>
        </div>
    </div>
</article>