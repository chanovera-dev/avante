<?php
/**
 * Template part for displaying the FAQ section on the homepage.
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 */

// Layout text configuration for easy editing via ACF later
$faq_title = get_field('homepage_faq_title') ?: 'Preguntas más frecuentes';
$faq_subtitle = get_field('homepage_faq_subtitle') ?: 'FAQs';

$cta_title = get_field('homepage_faq_cta_title') ?: 'Agenda una llamada de 15 min';
$cta_desc = get_field('homepage_faq_cta_desc') ?: 'Si tienes dudas, agenda una videollamada gratuita de 15 minutos antes de suscribirte a un plan.';
$cta_btn_text = get_field('homepage_faq_cta_btn_text') ?: 'Reservar Llamada Gratuita';
$cta_url = get_field('homepage_faq_cta_url') ?: '#cta';
$cta_avatar = get_field('homepage_faq_cta_avatar')['url'] ?? 'https://i.pravatar.cc/150?img=33';
?>

<section id="faq-section" class="block">

    <!-- Background decorative elements -->
    <div class="faq-bg" aria-hidden="true">
        <div class="faq-bg__scanline"></div>
        <div class="faq-bg__glow faq-bg__glow--tl"></div>
        <div class="faq-bg__glow faq-bg__glow--br"></div>
        <div class="faq-bg__dots faq-bg__dots--left"></div>
        <div class="faq-bg__dots faq-bg__dots--right"></div>
        <div class="faq-bg__corner faq-bg__corner--tl"></div>
        <div class="faq-bg__corner faq-bg__corner--tr"></div>
        <div class="faq-bg__corner faq-bg__corner--bl"></div>
        <div class="faq-bg__corner faq-bg__corner--br"></div>
    </div>

    <div class="content">
        <div class="faq-grid">
            <!-- Left Column -->
            <div class="faq-column-info">
                <span class="span-pretext faq-pretext pretext-reveal">
                    <?= esc_html($faq_subtitle); ?>
                </span>
                <h2 class="title-section faq-main-title title-reveal"><?= wp_kses_post($faq_title); ?></h2>

                <div class="faq-cta-card card-reveal">
                    <div class="faq-cta-avatar-wrapper">
                        <img src="<?= esc_url($cta_avatar); ?>" alt="Avatar" class="faq-cta-avatar">
                    </div>
                    <h3 class="faq-cta-title"><?= esc_html($cta_title); ?></h3>
                    <p class="faq-cta-desc"><?= esc_html($cta_desc); ?></p>
                    <a href="<?= esc_url($cta_url); ?>" class="faq-cta-button btn primary">
                        <?= avante_get_icon('date'); ?>
                        <?= esc_html($cta_btn_text); ?>
                    </a>
                </div>
            </div>

            <!-- Right Column (Accordion) -->
            <div class="faq-column-accordion">
                <div class="accordion-container">
                    <?php if (have_rows('homepage_faqs')) : ?>
                        <?php while (have_rows('homepage_faqs')) : the_row();
                            $is_active = ($index === 0);
                            $question = get_sub_field('question');
                            $answer = get_sub_field('answer');
                            ?>
                            <div class="accordion-item <?= $is_active ? 'active' : ''; ?>">
                            <button class="accordion-header" aria-expanded="<?= $is_active ? 'true' : 'false'; ?>">
                                <span class="accordion-question"><?= esc_html($question); ?></span>
                                <span class="accordion-icon">
                                    <?= $is_active ? '&times;' : '+'; ?>
                                </span>
                            </button>
                            <div class="accordion-body" style="<?= $is_active ? 'max-height: 500px; opacity: 1;' : ''; ?>">
                                <div class="accordion-inner">
                                    <?= wp_kses_post($answer); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>Agrega preguntas frecuentes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
