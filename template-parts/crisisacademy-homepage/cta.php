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
$cta_btn_text  = get_field('cta_final_btn_text')      ?: 'Enviar mensaje';
$cta_btn_url   = get_field('cta_final_btn_url')       ?: '/contacto';
$cta_microcopy = get_field('cta_final_microcopy')     ?: 'Sin compromisos · Respuesta en menos de 24 h';

// Número de WhatsApp configurable mediante código (o ACF en el futuro)
$whatsapp_number = '525543910088';

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
        <div class="cta-premium-wrapper card-reveal" id="cta-wrapper">

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
                     <div class="quotes-container">
                        <div class="slideshow--wrapper">
                            <div class="slideshow">
                                <?php if ( have_rows('trust_items') ) : ?>
                                    <?php $count = 1; while ( have_rows('trust_items') ) : the_row();
                                        $icon  = get_sub_field('trust_icon');
                                        $text  = get_sub_field('trust_text');
                                    ?>
                                    <div class="module-card">
                                        <div class="module-card--content">
                                            <div class="module-icon"><?= $icon; ?></div>
                                            <p class="guarantee-text"><?= esc_html($text); ?></p>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <!-- Card 1 -->
                                    <div class="module-card">
                                        <div class="module-card--content">
                                            <div class="module-icon">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <p class="guarantee-text">Más de 10 años de experiencia comprobada</p>
                                        </div>
                                    </div>
                                    <!-- Card 2 -->
                                    <div class="module-card">
                                        <div class="module-card--content">
                                            <div class="module-icon">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <p class="guarantee-text">Metodología internacional certificada</p>
                                        </div>
                                    </div>
                                    <!-- Card 3 -->
                                    <div class="module-card">
                                        <div class="module-card--content">
                                            <div class="module-icon">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                            <p class="guarantee-text">Atención personalizada para cada empresa</p>
                                        </div>
                                    </div>
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

                    <!-- Formulario WhatsApp -->
                    <form id="cta-whatsapp-form" class="cta-form" data-phone="<?= esc_attr($whatsapp_number); ?>">
                        <div class="cta-form-fields">
                            <div class="form-group">
                                <label for="wa_name">Nombre completo</label>
                                <input type="text" id="wa_name" required placeholder="Ej. Juan Pérez">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="wa_email">Correo electrónico</label>
                                    <input type="email" id="wa_email" required placeholder="juan@empresa.com">
                                </div>
                                <div class="form-group">
                                    <label for="wa_phone">Teléfono / WhatsApp</label>
                                    <input type="tel" id="wa_phone" required placeholder="+52 ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wa_interest">¿Qué quieres aprender específicamente?</label>
                                <textarea id="wa_interest" rows="2" required placeholder="Ej. Gestión de crisis, vocería..."></textarea>
                            </div>
                            
                            <!-- Botón y microcopy -->
                            <div class="cta-action-area" style="margin-top: 1rem;">
                                <button type="submit" class="btn primary cta-pulse-btn" id="cta-main-btn" style="border:none; cursor:pointer; width:auto;">
                                    <?= avante_get_icon('forward'); ?>
                                    <?= esc_html($cta_btn_text); ?>
                                </button>

                                <p class="cta-micro-copy">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                    <?= esc_html($cta_microcopy); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Mensaje de Éxito (Oculto inicialmente) -->
                        <div class="cta-success-message" style="display: none; text-align: center; padding: 2rem 0;">
                            <div class="success-icon" style="color: var(--wp--preset--color--primary); margin-bottom: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <h3 style="color: var(--wp--preset--color--base); font-size: 1.5rem; margin-bottom: 0.5rem;">¡Preparado!</h3>
                            <p style="color: rgba(255,255,255,0.7); font-size: 1rem; margin-bottom: 1.5rem;">Se ha abierto WhatsApp con tu mensaje pre-cargado.</p>
                            <button type="button" class="btn secondary" id="cta-reset-btn" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 99px; cursor: pointer; transition: all 0.3s ease;">
                                Llenar nuevo formulario
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Barra de estadísticas -->
            <div class="cta-stats-bar">
                <?php foreach ($stats as $stat) : ?>
                <div class="cta-stat-item">
                    <span class="cta-stat-number pretext-reveal"><?= esc_html($stat['number']); ?></span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cta-whatsapp-form');
    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const phone = form.getAttribute('data-phone');
            const name = document.getElementById('wa_name').value.trim();
            const email = document.getElementById('wa_email').value.trim();
            const userPhone = document.getElementById('wa_phone').value.trim();
            const interest = document.getElementById('wa_interest').value.trim();
            
            let message = `*Nueva Inscripción / Solicitud*\n\n`;
            message += `*Nombre:* ${name}\n`;
            message += `*Correo:* ${email}\n`;
            message += `*Teléfono:* ${userPhone}\n`;
            message += `*Interés:* ${interest}\n`;
            
            const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');

            // Ocultar campos y mostrar éxito
            const formFields = form.querySelector('.cta-form-fields');
            const successMsg = form.querySelector('.cta-success-message');
            if (formFields && successMsg) {
                formFields.style.display = 'none';
                successMsg.style.display = 'block';
            }
        });
        
        // Lógica de botón reset
        const resetBtn = document.getElementById('cta-reset-btn');
        if(resetBtn) {
            resetBtn.addEventListener('click', function() {
                form.reset();
                const formFields = form.querySelector('.cta-form-fields');
                const successMsg = form.querySelector('.cta-success-message');
                if (formFields && successMsg) {
                    formFields.style.display = 'block';
                    successMsg.style.display = 'none';
                }
            });
        }
    }
});
</script>
