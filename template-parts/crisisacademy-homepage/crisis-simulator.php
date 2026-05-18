<?php
/**
 * Template part for displaying the simulator section on the homepage.
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
$simulator_content = get_field('simulator_showreel');

if (empty($simulator_content)) {
    return;
}
?>
<section id="crisis-simulator" class="block">
    <div class="content">
        <div class="data">
            <span class="span-pretext pretext-reveal">Simulador de crisis</span>
            <h2 class="title-section title-reveal">Experimenta la presión en tiempo real y descubre si tu equipo está preparado</h2>
        </div>
        <div class="container app card-reveal">
            <?php
            if ( ! function_exists( 'is_plugin_active' ) ) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            if ( is_plugin_active( 'crisis-simulator/simulador-de-crisis.php' ) ) : ?>
                <a href="<?php echo esc_url( home_url( '/simulador-de-crisis/' ) ); ?>" class="btn-simulator-link btn primary">
                    <?= avante_get_icon('terminal'); ?>
                    Simular Crisis
                </a>
            <?php endif; ?>
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                    if (have_rows('simulator_showreel')):
                        $count = 0;
                        while (have_rows('simulator_showreel')):
                            the_row(); 
                            $image = get_sub_field('simulator_showreel_image');
                            $count++;
                            ?>

                            <article id="simulator-item-<?= $count; ?>" class="simulator-item post">
                                <div class="simulator-content">
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
            <?php if (have_rows('simulator_showreel')): ?>
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
    </div>
</section>