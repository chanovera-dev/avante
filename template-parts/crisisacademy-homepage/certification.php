<?php
/**
 * Template part for displaying the certification section as a Sales Funnel.
 *
 * Funnel stages:
 *  1. Hook    — Pain-point headline
 *  2. Promise — The transformation / solution
 *  3. Proof   — Social-proof stats
 *  4. Offer   — What they get (modules overview)
 *  5. CTA     — Urgency + enroll button
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */
$intro = get_field('certification_intro');
$cp_title = get_field('certification_process_title');
$fm_text = get_field('certification_footer_text');
$fm_bg_data = get_field('certification_footer_text_background');
$fm_bg_url = is_array($fm_bg_data) ? $fm_bg_data['url'] : $fm_bg_data;
$cert_cta_bg = get_field('certification_cta_background');
$cert_cta_bg_url = is_array($cert_cta_bg) ? $cert_cta_bg['url'] : $cert_cta_bg;
$cert_urgency_text  = get_field('certification_urgency_text');
$cert_cta_headline  = get_field('certification_cta_headline');
$cert_cta_subhead   = get_field('certification_cta_subheadline');
$cert_cta_btn1_text = get_field('certification_cta_btn1_text');
$cert_cta_btn1_url  = get_field('certification_cta_btn1_url');
$cert_cta_btn2_text = get_field('certification_cta_btn2_text');
$cert_cta_btn2_file = get_field('certification_cta_btn2_file');
$cert_cta_microcopy = get_field('certification_cta_microcopy');
?>

<section id="certification" class="block">
    <div class="content">
        <header class="intro">
            <div class="intro-content">
                <?php
                if ($intro):
                    echo apply_filters('the_content', $intro);
                endif;
                ?>
            </div>
            <div class="intro-funnel card-reveal" aria-label="Por qué certificarte">
                <div class="quotes-container">
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('certification_pain_pills') ) : ?>
                                <?php while ( have_rows('certification_pain_pills') ) : the_row(); 
                                    $icon = get_sub_field('pain_pill_icon');
                                    $text = get_sub_field('pain_pill_text');
                                ?>
                                    <span class="pain-pill">
                                        <span class="pain-pill__icon"><?= avante_render_acf_icon($icon); ?></span>
                                        <span class="pain-pill__text"><?= esc_html($text); ?></span>
                                    </span>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <p>No hay pildoras de dolor definidas.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="slideshow-bullets-wrapper">
                        <button class="slideshow-prev btn-pagination small-pagination" aria-label="diapositiva anterior">
                            <?= avante_get_icon('backward'); ?>
                        </button>
                        <div class="slideshow-bullets bullets"></div>
                        <button class="slideshow-next btn-pagination small-pagination" aria-label="siguiente diapositiva">
                            <?= avante_get_icon('forward'); ?>
                        </button>
                    </div>
                </div>
                <?php if (have_rows('certification_stats')) : ?>
                    <div class="funnel-proof__stats" aria-label="Estadísticas de la certificación">
                    <?php while (have_rows('certification_stats')) : the_row(); ?>
                        <div class="funnel-stat">
                            <span class="funnel-stat__number pretext-reveal"><?= esc_html(get_sub_field('stat_number')); ?></span>
                            <span class="funnel-stat__label pretext-reveal"><?= esc_html(get_sub_field('stat_text')); ?></span>
                        </div>
                    <?php endwhile; ?>
                    </div>
                <?php endif;?>
            </div>
        </header>
        <div class="content-main">
            <div class="sliders">
                <div class="cert-container card-reveal">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext"><?= esc_html($cp_title); ?></div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('certification_process_cards') ) : ?>
                                <?php $count = 1; while ( have_rows('certification_process_cards') ) : the_row();
                                    $icon  = get_sub_field('icon');
                                    $title = get_sub_field('title');
                                    $desc  = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number"><?= $count++; ?></span>
                                        <div class="module-icon"><?= avante_render_acf_icon($icon); ?></div>
                                        <div class="module-card--content-text">
                                            <h3 class="step-title"><?= esc_html($title); ?></h3>
                                            <p class="step-desc"><?= esc_html($desc); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <p>No se han encontrado tarjetas para el proceso de certificación.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="slideshow-bullets-wrapper">
                        <button class="slideshow-prev btn-pagination small-pagination" aria-label="diapositiva anterior">
                            <?= avante_get_icon('backward'); ?>
                        </button>
                        <div class="slideshow-bullets bullets"></div>
                        <button class="slideshow-next btn-pagination small-pagination" aria-label="siguiente diapositiva">
                            <?= avante_get_icon('forward'); ?>
                        </button>
                    </div>
                </div>
                <div class="quotes-container card-reveal">
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('certification_modalities_cards') ) : ?>
                                <?php while ( have_rows('certification_modalities_cards') ) : the_row();
                                    $title = get_sub_field('title');
                                    $desc  = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <div class="module-card--content-text">
                                            <h3 class="step-title"><?= esc_html($title); ?></h3>
                                            <p class="step-desc"><?= esc_html($desc); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <p>No se han encontrado modalidades para el proceso de certificación.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="slideshow-bullets-wrapper">
                        <button class="slideshow-prev btn-pagination small-pagination" aria-label="diapositiva anterior">
                            <?= avante_get_icon('backward'); ?>
                        </button>
                        <div class="slideshow-bullets bullets"></div>
                        <button class="slideshow-next btn-pagination small-pagination" aria-label="siguiente diapositiva">
                            <?= avante_get_icon('forward'); ?>
                        </button>
                    </div>
                </div>
                <div class="final-message card-reveal" style="background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)), url('<?= esc_url($fm_bg_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <p class="title-reveal"><?= wp_kses_post($fm_text); ?></p>
                </div>
            </div>
        </div>
        <div class="cert-panel card-reveal" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= esc_url($cert_cta_bg_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <?php if ($cert_urgency_text): ?>
            <div class="cert-urgency-banner">
                <?= avante_get_icon('clock-history');?>
                <?= wp_kses_post($cert_urgency_text); ?>
            </div>
            <?php endif; ?>
            <?php if ($cert_cta_headline): ?>
            <h2 class="cert-headline"><?= esc_html($cert_cta_headline); ?></h2>
            <?php endif; ?>
            <?php if ($cert_cta_subhead): ?>
            <p class="cert-subheadline"><?= esc_html($cert_cta_subhead); ?></p>
            <?php endif; ?>

            <div class="quotes-container">
                <div class="slideshow--wrapper">
                    <div class="slideshow">
                        <?php if ( have_rows('guarantee_strip') ) : ?>
                            <?php $count = 1; while ( have_rows('guarantee_strip') ) : the_row();
                                $icon  = get_sub_field('icon');
                                $text  = get_sub_field('text');
                            ?>
                            <div class="module-card">
                                <div class="module-card--content">
                                    <div class="module-icon"><?= avante_render_acf_icon($icon); ?></div>
                                    <p class="guarantee-text"><?= esc_html($text); ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <p>No se ha encontrado información para la franja de garantías.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="slideshow-bullets-wrapper">
                    <button class="slideshow-prev btn-pagination small-pagination" aria-label="diapositiva anterior">
                        <?= avante_get_icon('backward'); ?>
                    </button>
                    <div class="slideshow-bullets bullets"></div>
                    <button class="slideshow-next btn-pagination small-pagination" aria-label="siguiente diapositiva">
                        <?= avante_get_icon('forward'); ?>
                    </button>
                </div>
            </div>
            <div class="cert-actions">
                <?php if ($cert_cta_btn1_text && $cert_cta_btn1_url): ?>
                <a href="<?= esc_url($cert_cta_btn1_url); ?>" class="btn primary cert-btn-primary" id="cert-main-button">
                    <?= avante_get_icon('forward'); ?>
                    <?= esc_html($cert_cta_btn1_text); ?>
                </a>
                <?php endif; ?>
                <?php if ($cert_cta_btn2_text): ?>
                <a href="<?= esc_url($cert_cta_btn2_url); ?>" class="btn hollow cert-btn-secondary" id="cert-secondary-button" download target="_blank">
                    <?= esc_html($cert_cta_btn2_text); ?>
                </a>
                <?php endif; ?>
            </div>
            <!-- <p class="cert-microcopy"> -->
                <?php // echo avante_get_icon('shield-check'); ?>
                <?php // echo esc_html($cert_cta_microcopy); ?>
            <!-- </p> -->
        </div>
    </div>
</section>