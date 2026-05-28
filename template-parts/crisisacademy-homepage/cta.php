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
$cta_title     = get_field('cta_final_title')         ?: 'Anticípate, prepárate y gestiona profesionalmente cualquier crisis.';
$cta_desc      = get_field('cta_final_description')   ?: 'No dejes el futuro de tu organización al azar. Agenda una llamada con nuestros expertos y descubre cómo podemos fortalecer tu resiliencia corporativa.';
$cta_btn_text  = get_field('cta_final_btn_text')      ?: 'Enviar mensaje';
$cta_btn_url   = get_field('cta_final_btn_url')       ?: '/contacto';
$cta_microcopy = get_field('cta_final_microcopy')     ?: 'Sin compromisos · Respuesta en menos de 24 h';

// Número de WhatsApp configurable mediante código (o ACF en el futuro)
$whatsapp_number = '525543910088';
?>
<section id="cta" class="block">
    <div class="backdrop-glow">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
    </div>
    <div class="content card-reveal">
        <div class="content-backdrop-glow">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
            <div class="circle circle-4"></div>
        </div>
        <div class="cta-intro">
            <div class="logo object-reveal">
                <div class="ring-wrap">
                    <div class="glow-ring"></div>
                    <div class="scene">
                        <div class="cube">
                            <div class="face front">
                                <div class="grid"></div>
                                <div class="ai-eye">
                                    <div class="pupil"></div>
                                    <span class="node node-1"></span>
                                    <span class="node node-2"></span>
                                    <span class="node node-3"></span>
                                    <span class="node node-4"></span>
                                </div>
                            </div>
                            <div class="face back">
                                <div class="grid"></div>
                            </div>
                            <div class="face left">
                                <div class="grid"></div>
                            </div>
                            <div class="face right">
                                <div class="grid"></div>
                            </div>
                            <div class="face top">
                                <div class="grid"></div>
                            </div>
                            <div class="face bottom">
                                <div class="grid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <span class="span-pretext pretext-reveal"><?= esc_html($cta_pretext); ?></span> -->
            <h2 class="title-section title-reveal"><?= esc_html($cta_title); ?></h2>
            <p class="cta-description object-reveal"><?= esc_html($cta_desc); ?></p>
        </div>
        <div class="separator"></div>
        <div class="cta-action object-reveal">
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
                    <div class="cta-action-area">
                        <button type="submit" class="btn primary cta-pulse-btn" id="cta-main-btn" style="border:none; cursor:pointer; width:auto;">
                            <?= avante_get_icon('forward'); ?>
                            <?= esc_html($cta_btn_text); ?>
                        </button>

                        <p class="cta-micro-copy">
                            <?= avante_get_icon('shield-check'); ?>
                            <?= esc_html($cta_microcopy); ?>
                        </p>
                    </div>
                </div>
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
</section>