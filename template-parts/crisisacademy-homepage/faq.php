<?php
/**
 * Template part for displaying the FAQ section on the homepage.
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 */

// Fetch from ACF, fallback to hardcoded array for immediate display
$faqs = get_field('homepage_faqs');

if (empty($faqs)) {
    $faqs = [
        [
            'question' => '¿En qué se especializa The Crisis Academy?',
            'answer'   => 'Nos especializamos en ayudar a organizaciones y líderes a diseñar, construir y escalar protocolos de respuesta ante crisis. Nuestra experiencia incluye estrategia de reputación, entrenamiento de voceros, simulacros en tiempo real y auditorías de riesgo para empresas de todos los sectores.'
        ],
        [
            'question' => '¿Trabajan con startups y empresas en etapas iniciales?',
            'answer'   => 'Sí, adaptamos nuestros marcos de trabajo para equipos ágiles. Creemos que es vital tener una estructura de respuesta rápida desde el día uno para proteger la reputación conforme el negocio escala.'
        ],
        [
            'question' => '¿Cuánto tiempo toma un proceso de consultoría típico?',
            'answer'   => 'El tiempo varía según la complejidad de la organización, pero un diagnóstico y plan de respuesta inicial suelen establecerse en un periodo de 4 a 6 semanas, con seguimiento continuo.'
        ],
        [
            'question' => '¿Pueden integrar Inteligencia Artificial en nuestra gestión de crisis?',
            'answer'   => 'Absolutamente. Implementamos herramientas de monitoreo impulsadas por IA para detección temprana de sentimientos y tendencias negativas, automatizando alertas para que tu equipo gane tiempo valioso.'
        ],
        [
            'question' => '¿Qué información necesitan de mi parte para empezar?',
            'answer'   => 'Únicamente acceso a tus canales de comunicación actuales, organigrama clave y un recuento de incidentes previos (si existen). A partir de ahí, realizamos un kick-off de inmersión profunda.'
        ],
        [
            'question' => '¿Cómo aseguran la calidad del entrenamiento de voceros?',
            'answer'   => 'Nuestros entrenadores son periodistas y expertos en RRPP en activo. Utilizamos simulaciones realistas en video y métricas de desempeño para medir la claridad, calma y efectividad del mensaje.'
        ],
    ];
}

// Layout text configuration for easy editing via ACF later
$faq_title = get_field('homepage_faq_title') ?: 'Preguntas más frecuentes';
$faq_subtitle = get_field('homepage_faq_subtitle') ?: 'FAQs';

$cta_title = get_field('homepage_faq_cta_title') ?: 'Agenda una llamada de 15 min';
$cta_desc = get_field('homepage_faq_cta_desc') ?: 'Si tienes dudas, agenda una videollamada gratuita de 15 minutos antes de suscribirte a un plan.';
$cta_btn_text = get_field('homepage_faq_cta_btn_text') ?: 'Reservar Llamada Gratuita';
$cta_url = get_field('homepage_faq_cta_url') ?: '#booking';
$cta_avatar = get_field('homepage_faq_cta_avatar') ?: 'https://i.pravatar.cc/150?img=33';
?>

<section id="faq-section" class="block">
    <div class="content">
        <div class="faq-grid">
            <!-- Left Column -->
            <div class="faq-column-info">
                <span class="span-pretext faq-pretext">
                    <?= esc_html($faq_subtitle); ?>
                </span>
                <h2 class="title-section faq-main-title"><?= wp_kses_post($faq_title); ?></h2>

                <div class="faq-cta-card">
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
                    <?php foreach ($faqs as $index => $faq): 
                        // Open the first one by default as in the design reference
                        $is_active = ($index === 0); 
                    ?>
                        <div class="accordion-item <?= $is_active ? 'active' : ''; ?>">
                            <button class="accordion-header" aria-expanded="<?= $is_active ? 'true' : 'false'; ?>">
                                <span class="accordion-question"><?= esc_html($faq['question']); ?></span>
                                <span class="accordion-icon">
                                    <?= $is_active ? '&times;' : '+'; ?>
                                </span>
                            </button>
                            <div class="accordion-body" style="<?= $is_active ? 'max-height: 500px; opacity: 1;' : ''; ?>">
                                <div class="accordion-inner">
                                    <?= wp_kses_post($faq['answer']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
