<?php
/**
 * Template part for displaying posts in a Justified Grid
 *
 * @package Avante
 * @since Avante 1.0.0
 */

// Calcular Aspect Ratio
$ratio = 1; // Default cuadrado
$width = 300;
$height = 300;

if (has_post_thumbnail()) {
    $img_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium_large');
    if ($img_data) {
        $width = $img_data[1];
        $height = $img_data[2];
        // Evitar división por cero
        if ($height > 0) {
            $ratio = $width / $height;
        }
    }
}
?>

<!-- Wrapper con Aspect Ratio calculado -->
<div class="ajax-item-wrapper" 
    style="flex-grow: <?php echo esc_attr($ratio * 100); ?>; flex-basis: calc( var(--row-height, 250px) * <?php echo esc_attr($ratio); ?> );" 
    data-ratio="<?php echo esc_attr($ratio); ?>"
    data-year="<?php echo get_the_date('Y'); ?>">
    
    <article id="post-<?php the_ID(); ?>" <?php post_class('justified-post'); ?> data-id="<?php the_ID(); ?>" style="padding-bottom: <?php echo esc_attr((1 / $ratio) * 100); ?>%;">
        <div class="post__backdrop"></div>
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);

            $format = get_post_format($post_id);
            if ($format === 'gallery') {
                echo '<a href="' . esc_url(get_post_format_link('gallery')) . '" class="format-post-tag">' . avante_get_icon('gallery') . esc_html(__('Galería', 'avante')) . '</a>';
            } else {
                echo '<a href="' . esc_url(get_post_format_link('image')) . '" class="format-post-tag">' . avante_get_icon('image') . esc_html(__('Dibujo', 'avante')) . '</a>';
            }
            ?>
            <?php
            if (in_array($format, ['gallery'])) {
                ?>
                <button class="toggle-post-content" aria-label="Mostrar información">
                    <span class="icon-info"><?= avante_get_icon('info-circle'); ?></span>
                    <span class="icon-close" style="display:none;"><?= avante_get_icon('close'); ?></span>
                </button>
                <?php
            }
            ?>
            <?php echo avante_render_like_button(); ?>
        </div>
        <div class="post__content">
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
        // Lógica condicional: O es Galería O es Imagen estática
        if ( get_post_format() === 'gallery' ) :
            
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

        <?php else : 
            // NO ES GALERÍA: Mostrar Thumbnail normal
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium_large', [
                    'class' => 'post-thumbnail', 
                    'alt' => get_the_title(),
                    'loading' => 'lazy'
                ]);
            }
        endif; 
        ?>
    </article>
</div>