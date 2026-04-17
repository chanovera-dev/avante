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
$hero_action_file_button = get_field('hero_action_file_button');
$hero_action_file_button_label = get_field('hero_action_file_button_label');

if (empty($hero_span) && empty($hero_first_content) && empty($hero_action_file_button)) {
    return;
}
?>
<section id="hero" class="block">
    <div class="content wide hero--content heading">

        <div class="hero-content--text">
            <?php if ($hero_span): ?>
                <span class="span-pretext"><?php echo esc_html($hero_span); ?></span>
            <?php endif; ?>

            <?php if ($hero_first_content): ?>
                <div class="hero-description">
                    <?php echo apply_filters( 'the_content', $hero_first_content ); ?>
                </div>
            <?php endif; ?>

            <?php if (($hero_action_file_button && $hero_action_file_button_label)): ?>
                <div class="cta-buttons">
                    <?php 
                        // Manage both array and string return types from ACF File field
                        $file_url = is_array($hero_action_file_button) ? $hero_action_file_button['url'] : $hero_action_file_button; 
                    ?>
                    <a href="<?php echo esc_url($file_url); ?>" class="btn primary large" download>
                        <?php
                        echo avante_get_icon('file');
                        echo esc_html($hero_action_file_button_label);
                        ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="content wide about">
        <div class="container glass-border-bright">
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
            <h2 class="title-section">El 75% de las crisis dieron señales que nadie en la organización fue capaz de detectar o atender hasta que explotaron.</h2>
            <h3 class="subtitle-section span-pretext">Capacitamos <strong>profesionalmente</strong> a quienes participan en el <u>manejo de crisis.</u></h3>
            <div class="logos">
                <div class="logo-wrapper">
                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/logos/php.svg" alt="" srcset="">
                </div>
                <div class="logo-wrapper">
                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/logos/RedHat.svg" alt="" srcset="">
                </div>
                <div class="logo-wrapper">
                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/logos/Windows.svg" alt="" srcset="">
                </div>
                <div class="logo-wrapper">
                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/logos/Wordpress.svg" alt="" srcset="">
                </div>
            </div>
            <h3 class="subtitle-section">The Crisis Academy en números</h3>
            <div class="numbers">
                <div class="number">
                    <div class="number-wrapper"><div class="number-value-counter" data-target="30" data-duration="3000">30</div><div class="postfix">+</div></div>
                    <div class="number-label">Años de experiencia</div>
                </div>
                <div class="number">
                    <div class="number-wrapper"><div class="number-value-counter" data-target="100" data-duration="3000">100</div><div class="postfix">+</div></div>
                    <div class="number-label">Casos de éxito</div>
                </div>
                <div class="number">
                    <div class="number-wrapper"><div class="number-value-counter" data-target="1000" data-duration="3000">1000</div><div class="postfix">+</div></div>
                    <div class="number-label">Alumnos capacitados</div>
                </div>
                <div class="number">
                    <div class="number-wrapper"><div class="number-value-counter" data-target="95" data-duration="3000">95</div><div class="postfix">%</div></div>
                    <div class="number-label">de efectividad</div>
                </div>
            </div>
        </div>
    </div>
</section>