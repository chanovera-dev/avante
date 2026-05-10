<?php
/**
 * Template part for displaying the CTA section on the homepage.
 *
 * This section features two trust-building subsections (audience & authority)
 * and a prominent call-to-action panel designed for maximum conversion.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */
?>
<section id="cta" class="block">
    <!-- ¿Para quién es? -->
    <div class="content">
        <span class="span-pretext">¿Para quién es?</span>
        <h2 class="title-section">Diseñado para quienes protegen la reputación</h2>
        <div class="grid-containers">
            <div class="containers">
                <div class="cta-container">
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <!-- Slide 1: Cards 1-4 -->
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                                    </svg>
                                </div>
                                <span class="audience-label">Direcciones y Gerencias de Comunicación</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                                <span class="audience-label">Comités de Crisis y Emergencias</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" /><line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" /><polyline points="10 9 9 9 8 9" />
                                    </svg>
                                </div>
                                <span class="audience-label">Áreas Legales y Compliance</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 18h-2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2l5-4v20l-5-4z" />
                                        <path d="M19 9a5 5 0 0 1 0 6" />
                                    </svg>
                                </div>
                                <span class="audience-label">Voceros y Portavoces</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 21h18" /><path d="M5 21V7l8-4v18" /><path d="M19 21V11l-6-4" />
                                        <line x1="9" y1="9" x2="9" y2="9.01" /><line x1="9" y1="13" x2="9" y2="13.01" />
                                        <line x1="9" y1="17" x2="9" y2="17.01" />
                                    </svg>
                                </div>
                                <span class="audience-label">Instituciones y Gobierno</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" /><line x1="2" y1="12" x2="22" y2="12" />
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </div>
                                <span class="audience-label">Consultores</span>
                            </div>
                            <div class="audience-item">
                                <div class="audience-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                        <path d="M6 12v5c3 3 12 3 12 0v-5" />
                                    </svg>
                                </div>
                                <span class="audience-label">Universidades y Organizaciones</span>
                            </div>
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
                <div class="cta-container">
                    <span class="span-pretext">Autoridad y experiencia</span>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <div class="authority-item">
                                <div class="authority-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                                <div class="authority-text">
                                    <strong>Metodologías de líderes reconocidos</strong>
                                    <span>Ian Mitroff, Paul Benoit, Timothy Coombs</span>
                                </div>
                            </div>
                            <div class="authority-item">
                                <div class="authority-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                    </svg>
                                </div>
                                <div class="authority-text">
                                    <strong>Redacción de más de 100 manuales y playbooks</strong>
                                    <span>Para empresas e instituciones</span>
                                </div>
                            </div>
                            <div class="authority-item">
                                <div class="authority-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <line x1="19" y1="8" x2="19" y2="14" /><line x1="22" y1="11" x2="16" y2="11" />
                                    </svg>
                                </div>
                                <div class="authority-text">
                                    <strong>Acompañamiento en crisis reales</strong>
                                    <span>En diversas industrias y sectores</span>
                                </div>
                            </div>
                            <div class="authority-item">
                                <div class="authority-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22V2M12 2C9.5 2 7.5 4 7.5 6.5c0 1 .5 2 1.5 2.5C7 9.5 6 11 6 13c0 2 1.5 3.5 3.5 3.5h.5c0 1 .5 2 1.5 2.5M12 2c2.5 0 4.5 2 4.5 4.5 0 1-.5 2-1.5 2.5 2 .5 3 2 3 4 0 2-1.5 3.5-3.5 3.5h-.5c0 1-.5 2-1.5 2.5" />
                                        <path d="M7.5 6.5C5 6.5 3 8.5 3 11c0 2.5 2 4.5 4.5 4.5M16.5 6.5c2.5 0 4.5 2 4.5 4.5 0 2.5-2 4.5-4.5 4.5" />
                                    </svg>
                                </div>
                                <div class="authority-text">
                                    <strong>Contenidos actualizados con IA</strong>
                                    <span>Fake news y nuevas tendencias globales</span>
                                </div>
                            </div>
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
            </div>
            <div class="cta-panel" style="background-image: url('<?= get_template_directory_uri(); ?>/assets/img/war-room-big.webp'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="cta-panel-backdrop"></div>
                <div class="cta-panel-content">
                    <h2 class="cta-headline">Prepárate antes de que la crisis defina tu respuesta</h2>
                    <p class="cta-subheadline">Solicita información sobre nuestros programas de certificación, auditorías y manuales de crisis. Sin compromiso.</p>
                    <div class="cta-actions">
                        <a href="/contacto" class="btn primary cta-btn-primary" id="cta-main-button">
                            <?= avante_get_icon('forward'); ?>
                            Solicitar información
                        </a>
                        <a href="/taller-de-especializacion" class="btn hollow cta-btn-secondary" id="cta-secondary-button">
                            Ver próximos talleres
                        </a>
                    </div>
                    <p class="cta-microcopy">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Respuesta en menos de 24 horas · Programas personalizados
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
