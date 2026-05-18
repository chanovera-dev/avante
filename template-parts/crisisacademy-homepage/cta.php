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
 * @version 2.0.0
 */

$cta_pretext   = get_field('cta_final_pretext')      ?: 'Tu reputación en las mejores manos';
$cta_title     = get_field('cta_final_title')         ?: 'Anticípate, prepárate y domina cualquier crisis';
$cta_desc      = get_field('cta_final_description')   ?: 'No dejes el futuro de tu empresa al azar. Agenda una llamada con nuestros expertos y descubre cómo podemos fortalecer tu resiliencia corporativa.';
$cta_btn_text  = get_field('cta_final_btn_text')      ?: 'Contactar a un experto';
$cta_btn_url   = get_field('cta_final_btn_url')       ?: '/contacto';
$cta_microcopy = get_field('cta_final_microcopy')     ?: 'Sin compromisos · Respuesta en menos de 24 h';

// Trust signals
$trust_items = [
    'Más de 10 años de experiencia comprobada',
    'Metodología internacional certificada',
    'Atención personalizada para cada empresa',
];

// Stats
$stats = [
    ['number' => '+200',  'label' => 'Empresas protegidas'],
    ['number' => '98%',   'label' => 'Índice de satisfacción'],
    ['number' => '+10',   'label' => 'Años de experiencia'],
    ['number' => '24/7',  'label' => 'Soporte disponible'],
];
?>
<section id="cta" class="block">

    <!-- Efectos de fondo futuristas sobre toda la sección -->
    <div class="cta-bg-fx" aria-hidden="true">
        <div class="cta-hud-ring cta-hud-ring-1"></div>
        <div class="cta-hud-ring cta-hud-ring-2"></div>
        <div class="cta-scanline"></div>
        <div class="cta-grid"></div>
        <div class="cta-data-points">
            <span></span><span></span><span></span>
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="content">
        <div class="cta-premium-wrapper" id="cta-wrapper">

            <!-- Fondos de glow animados -->
            <div class="cta-premium-bg">
                <div class="cta-glow cta-glow-1"></div>
                <div class="cta-glow cta-glow-2"></div>
                <div class="cta-glow cta-glow-3"></div>
            </div>

            <!-- Glow interactivo (sigue al ratón) -->
            <div class="cta-interactive-glow"></div>

            <!-- Layout principal de dos columnas -->
            <div class="cta-premium-inner">

                <!-- Columna izquierda: texto -->
                <div class="cta-text-col">
                    <span class="span-pretext pretext-reveal"><?= esc_html($cta_pretext); ?></span>

                    <h2 class="title-section title-reveal"><?= esc_html($cta_title); ?></h2>

                    <p class="cta-description"><?= esc_html($cta_desc); ?></p>
                </div>

                <!-- Separador vertical -->
                <div class="cta-divider" aria-hidden="true"></div>

                <!-- Columna derecha: acción -->
                <div class="cta-action-col">

                    <!-- Trust signals -->
                    <ul class="cta-trust-list">
                        <?php foreach ($trust_items as $item) : ?>
                        <li>
                            <span class="trust-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                            <?= esc_html($item); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Botón y microcopy -->
                    <div class="cta-action-area">
                        <a href="<?= esc_url($cta_btn_url); ?>" class="btn primary cta-pulse-btn" id="cta-main-btn">
                            <?= avante_get_icon('forward'); ?>
                            <?= esc_html($cta_btn_text); ?>
                        </a>

                        <p class="cta-micro-copy">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <?= esc_html($cta_microcopy); ?>
                        </p>
                    </div>

                </div>
            </div>

            <!-- Barra de estadísticas -->
            <div class="cta-stats-bar">
                <?php foreach ($stats as $stat) : ?>
                <div class="cta-stat-item">
                    <span class="cta-stat-number"><?= esc_html($stat['number']); ?></span>
                    <span class="cta-stat-label"><?= esc_html($stat['label']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<script>
(function () {
    const wrapper = document.getElementById('cta-wrapper');
    if (!wrapper) return;

    wrapper.addEventListener('mousemove', (e) => {
        const rect = wrapper.getBoundingClientRect();
        wrapper.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
        wrapper.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
    });
})();
</script>
