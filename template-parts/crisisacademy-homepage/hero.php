<?php
/**
 * Template part for displaying the hero section on the homepage.
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
$hero_span = get_field('hero_span');
$hero_first_content = get_field('hero_first_content');
$hero_action_button = get_field('hero_action_button');
$hero_action_button_label = get_field('hero_action_button_label');
$hero_second_content = get_field('hero_second_content');

if (empty($hero_span) && empty($hero_first_content) && empty($hero_action_button)) {
    return;
}
?>
<section id="hero" class="block">
    <div class="content hero--content heading">

        <div class="hero-content--text">
            <?php if ($hero_span): ?>
                <span class="span-pretext scramble-letters"><?php echo esc_html($hero_span); ?></span>
            <?php endif; ?>

            <?php if ($hero_first_content): ?>
                <div class="hero-description scramble-words">
                    <?php echo apply_filters( 'the_content', $hero_first_content ); ?>
                </div>
            <?php endif; ?>

            <?php if ($hero_action_button && $hero_action_button_label): ?>
                <div class="cta-buttons">
                    <a href="<?= esc_url($hero_action_button); ?>" class="btn primary">
                        <?= avante_get_icon('forward'); ?>
                        <?= esc_html($hero_action_button_label); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content about">
        <div class="container glass-border-bright animate-in--scale-up">
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
        <div class="about-content">
            <?php echo apply_filters( 'the_content', $hero_second_content ); ?>
        </div>
    </div>
</section>