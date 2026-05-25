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
$about_image = get_field('about_image');

if (empty($about_content)) {
    return;
}
?>
<section id="about" class="block">
    <div class="content">
        <div class="container app card-reveal">
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
            <?php $counter = 1; ?>
            <?php if (have_rows('about_items')) : ?>
                <?php while (have_rows('about_items')) : the_row(); ?>
                    <div class="about-item card-reveal">
                        <div class="about-item__circuits"></div>
                        <?php
                        $icon = get_sub_field('about_item_icon');
                        $label = get_sub_field('about_item_label');
                        ?>
                            <div class="module-icon module-level"><?= $counter ?></div>
                        <?php $counter++; ?>
                        <h3 class="about-item-label"><?= $label ?></h3>
                    </div>
                <?php endwhile; ?>
                <?php else : ?>
                    <p>No se encontraron ítems.</p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>