<?php
/**
 * Template part for displaying property posts in the loop
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */
$property_data = avante_get_property_data();
$type      = $property_data['type'] ?? get_post_meta(get_the_ID(), 'eb_property_type', true) ?: 'Sin tipo';
$operation = $property_data['operation'];
$price     = $property_data['price'];
$location  = $property_data['location'];
$gallery   = $property_data['gallery'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(['post', 'format-property']); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body">
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            // Translate property type from English to Spanish
            $type_translated = function_exists('translate_property_type') ? translate_property_type($type) : $type;
            echo '<span class="format-post-tag">' . esc_html($type_translated) . ' ';
            echo $operation === 'sale' ? 'en venta' : ( $operation === 'rental' ? 'en renta' : '' );
            echo '</span>';
            ?>
            
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post__content">
            <a href="<?= get_the_permalink(); ?>" class="post__permalink">
                <?php the_title('<h2 class="post__title">', '</h2>'); ?>
            </a>
            <span class="location">
                <?= avante_get_icon('location'); ?>
                <p><?php echo esc_html($location); ?></p>
            </span>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= get_the_date('F j, Y'); ?></p>
            </div>
            <h3 class="property__price">
                <?php 
                    // Extract numeric price for formatting
                    $price_numeric = preg_replace('/[^\d\.,]/', '', $price);
                    
                    // Handle european format (1.234.567,89) or US format (1,234,567.89)
                    if (strpos($price_numeric, ',') !== false && strpos($price_numeric, '.') !== false) {
                        // If contains both, assume european: remove dots, replace comma with dot
                        $price_numeric = str_replace('.', '', $price_numeric);
                        $price_numeric = str_replace(',', '.', $price_numeric);
                    } else {
                        // Remove commas used as thousands separators
                        $price_numeric = str_replace(',', '', $price_numeric);
                    }
                    
                    $price_numeric = preg_replace('/[^\d\.]/', '', $price_numeric);
                    
                    if (!empty($price_numeric)) {
                        echo function_exists('format_price') ? esc_html(format_price($price_numeric)) : esc_html($price);
                    } else {
                        echo esc_html($price);
                    }
                ?>
            </h3>
            <div class="property__metadata">
                <?php avante_display_property_metadata(); ?>
                <div class="container">
                    <?php
                        $content = get_the_content();

                        // --- 1. Detectar métodos ---
                        $methods = [];

                        // 1. WhatsApp (todos)
                        if ( preg_match_all( '/https:\/\/wa\.me\/[^\s"]+/', $content, $wa_matches ) ) {
                            foreach ( $wa_matches[0] as $wa_url ) {
                                $methods[] = [
                                    'type' => 'whatsapp',
                                    'url'  => $wa_url,
                                    'icon' => avante_get_icon('whatsapp'),
                                    'label'=> __('Informes', 'avante'),
                                ];
                            }
                        }

                        // 2. Teléfonos (todos)
                        if ( preg_match_all( '/tel:([0-9+\-\s]+)/i', $content, $tel_matches ) ) {
                            foreach ( $tel_matches[0] as $i => $tel_url ) {
                                $methods[] = [
                                    'type' => 'phone',
                                    'url'  => $tel_url,
                                    'icon' => avante_get_icon('phone'),
                                    'label'=> __('Informes', 'avante'),
                                ];
                            }
                        }

                        // 3. Correos electrónicos (todos)
                        if ( preg_match_all( '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content, $email_matches ) ) {
                            foreach ( $email_matches[0] as $email ) {
                                $methods[] = [
                                    'type' => 'email',
                                    'url'  => 'mailto:' . $email,
                                    'icon' => avante_get_icon('email'),
                                    'label'=> __('Informes', 'avante'),
                                ];
                            }
                        }

                        // --- 2. Determinar si se muestra o no el texto ---
                        $show_text = count($methods) < 2;

                        // --- 3. Imprimir botones ---
                        foreach ( $methods as $m ) {
                        ?>
                            <button class="btn go-contact"
                                    onclick="window.open('<?php echo esc_url( $m['url'] ); ?>','_blank','noopener,noreferrer')">
                                <?= $m['icon']; ?>
                                <?php if ( $show_text ) echo esc_html( $m['label'] ); ?>
                            </button>
                        <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
        // Incluir helper si no existe
        if (!function_exists('avante_extract_gallery_images')) {
            require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
        }
        $ids = avante_extract_gallery_images(get_the_ID());
        ?>

        <div class="gallery-wrapper">
            <div class="gallery" style="display: flex;">
                <?php if ( !empty($gallery) && is_array($gallery) ) : ?>
                    <?php foreach ( $gallery as $img ) :
                        $img_url = is_array($img) ? $img['url'] : $img; ?>
                        <div class="slide">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="" class="attachment-loop-thumbnail loop-thumbnail" width="400" height="400" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="gallery-navigation" style="display: flex; align-items: center; position: absolute; bottom: 10px; left: 10px; z-index: 10; width: calc(100% - 20px);">
                <button class="gallery-prev btn-pagination small-pagination glass-backdrop" aria-label="Anterior"><?= avante_get_icon('backward'); ?></button>
                <div class="loop-gallery-bullets" style="flex-grow: 1; display: flex; justify-content: center;"></div>
                <button class="gallery-next btn-pagination small-pagination glass-backdrop" aria-label="Siguiente"><?= avante_get_icon('forward'); ?></button>
            </div>
        </div>
    </div>
</article>