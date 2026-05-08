<?php
/**
 * Template part for displaying the about section on the homepage.
 *
 * This section features a main heading, subheading, and call-to-action buttons.
 * All content is managed through Advanced Custom Fields (ACF).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */
$about_content = get_field('about_content');

if (empty($about_content)) {
    return;
}
?>
<section id="about" class="block">
    <div class="content">
        <div class="container app">
            <?php
            if ( ! function_exists( 'is_plugin_active' ) ) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            if ( is_plugin_active( 'crisis-simulator/simulador-de-crisis.php' ) ) : ?>
                <a href="<?php echo esc_url( home_url( '/simulador-de-crisis/' ) ); ?>" class="btn-simulator-link btn primary">
                    Simular Crisis
                </a>
            <?php endif; ?>
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                    if (have_rows('about_showreel')):
                        $count = 0;
                        while (have_rows('about_showreel')):
                            the_row(); 
                            $image = get_sub_field('about_showreel_image');
                            $count++;
                            ?>

                            <article id="about-item-<?= $count; ?>" class="about-item post">
                                <div class="about-content">
                                    <?php
                                    if ($image) {
                                        $img_id = is_array($image) ? $image['ID'] : $image;
                                        echo wp_get_attachment_image($img_id, 'full', false, ['loading' => 'lazy']);
                                    }
                                    ?>
                                </div>
                            </article>

                        <?php endwhile;
                    else:
                        echo '<p>No se encontraron guías.</p>';
                    endif;
                    ?>
                </div>
            </div>
            <?php if (have_rows('about_showreel')): ?>
            <div class="slideshow-bullets-wrapper">
                <button class="slideshow-prev btn-pagination small-pagination" aria-label="siguiente diapositiva">
                    <?= avante_get_icon('backward'); ?>
                </button>
                <div class="slideshow-bullets bullets"></div>
                <button class="slideshow-next btn-pagination small-pagination" aria-label="anterior diapositiva">
                    <?= avante_get_icon('forward'); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="data">
            <?php if ($about_content) : ?>
                <?php echo apply_filters( 'the_content', $about_content )?>
            <?php endif; ?>    
            <div class="about-container">
            <?php if (have_rows('about_items')) : ?>
                <?php while (have_rows('about_items')) : the_row(); ?>
                    <div class="about-item">
                        <?php
                        $icon = get_sub_field('about_item_icon');
                        $label = get_sub_field('about_item_label');
                        if ($icon) :
                            ?>
                            <img src="<?= $icon['url'] ?>" alt="<?= $icon['alt'] ?>" srcset="" width="64px" height="64px" loading="lazy">
                        <?php endif; ?>
                        <span class="about-item-label"><?= $label ?></span>
                    </div>
                <?php endwhile; ?>
                <?php else : ?>
                    <p>No se encontraron ítems.</p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>