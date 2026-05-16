<?php
/**
 * Template part for displaying the CTA section on the homepage.
 *
 * This section features a prominent call-to-action panel designed for maximum conversion.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */

$cta_pretext = get_field('cta_final_pretext') ?: 'Tu reputación en las mejores manos';
$cta_title = get_field('cta_final_title') ?: 'Anticípate, prepárate y domina cualquier crisis';
$cta_desc = get_field('cta_final_description') ?: 'No dejes el futuro de tu empresa al azar. Agenda una llamada con nuestros expertos y descubre cómo podemos fortalecer tu resiliencia corporativa.';
$cta_btn_text = get_field('cta_final_btn_text') ?: 'Contactar a un experto';
$cta_btn_url = get_field('cta_final_btn_url') ?: '/contacto';
?>
<section id="cta" class="block">
    <div class="content">
        <div class="cta-premium-wrapper">
            <div class="cta-premium-bg">
                <div class="cta-glow cta-glow-1"></div>
                <div class="cta-glow cta-glow-2"></div>
            </div>
            
            <div class="cta-premium-content">
                <span class="span-pretext"><?= esc_html($cta_pretext); ?></span>
                <h2 class="title-section"><?= esc_html($cta_title); ?></h2>
                <p class="cta-description"><?= esc_html($cta_desc); ?></p>
                
                <div class="cta-action-area">
                    <a href="<?= esc_url($cta_btn_url); ?>" class="btn primary cta-pulse-btn">
                        <?= avante_get_icon('forward'); ?>
                        <?= esc_html($cta_btn_text); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
