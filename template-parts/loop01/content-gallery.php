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
avante_enqueue_script('three', $a['js']['three']);
avante_enqueue_script('gsap', $a['js']['gsap']);
avante_enqueue_script('loop-gallery', $a['js']['loop-gallery'], ['three', 'gsap']);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body glass-border-bright">
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            echo '<a href="' . esc_url(get_post_format_link('gallery')) . '" class="format-post-tag">' . avante_get_icon('gallery') . esc_html(__('Galería', 'core')) . '</a>';
            ?>
            <button class="toggle-post-content" aria-label="Mostrar información">
                <span class="icon-info"><?= avante_get_icon('info-circle'); ?></span>
                <span class="icon-close" style="display:none;"><?= avante_get_icon('close'); ?></span>
            </button>
            <?php echo avante_render_like_button(); ?>
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
        // Incluir helper si no existe
        if (!function_exists('avante_extract_gallery_images')) {
            require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
        }
        $ids = avante_extract_gallery_images(get_the_ID());
        ?>

        <div class="gallery-wrapper" style="width: 100%; height: 100%; position: absolute; inset: 0;">
            <div class="gallery" style="display: flex;">
                <?php if (!empty($ids)) : foreach ($ids as $id) : ?>
                    <div class="slide" style="width: 100%; height: 100%;">
                        <?php echo wp_get_attachment_image($id, 'large', false, ['style' => 'width: 100%; height: 100%; object-fit: cover; display: block; position: absolute; inset: 0;']); ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            
            <div class="gallery-navigation" style="display: flex; align-items: center; position: absolute; bottom: 10px; left: 10px; z-index: 10; width: calc(100% - 20px);">
                <button class="gallery-prev btn-pagination small-pagination glass-backdrop" aria-label="Anterior"><?= avante_get_icon('backward'); ?></button>
                <div class="loop-gallery-bullets" style="flex-grow: 1; display: flex; justify-content: center;"></div>
                <button class="gallery-next btn-pagination small-pagination glass-backdrop" aria-label="Siguiente"><?= avante_get_icon('forward'); ?></button>
            </div>
        </div>
    </div>
</article>