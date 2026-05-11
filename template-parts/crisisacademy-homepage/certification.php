<?php
/**
 * Template part for displaying the certification section on the homepage.
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
<section id="certification" class="block">
    <!-- Entrenamiento y Módulos -->
    <div class="content">
        <span class="span-pretext scramble-letters">Entrenamiento Especializado</span>
        <h2 class="title-section">La ruta definitiva para convertirte en un experto en gestión de crisis</h2>
        <div class="grid-containers">
            <div class="containers">
                <div class="cta-container">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext">Certificación Profesional</div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <!-- Card 1 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">1</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                    </div>
                                    <div class="module-card--content-text">
                                        <h3 class="step-title">Diagnóstico</h3>
                                        <p class="step-desc">Perfilamos a los participantes y definimos objetivos.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 2 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">2</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                        </svg>
                                    </div>
                                    <div class="module-card--content-text">
                                        <h3 class="step-title">6 Módulos especializados</h3>
                                        <p class="step-desc">Contenido actualizado, casos reales y tendencias.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 3 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">3</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                            <line x1="8" y1="21" x2="16" y2="21" />
                                            <line x1="12" y1="17" x2="12" y2="21" />
                                            <path d="M10 13a2 2 0 1 0 4 0" />
                                            <path d="M8 15a4 4 0 0 1 8 0" />
                                        </svg>
                                    </div>
                                    <div class="module-card--content-text">
                                        <h3 class="step-title">Simulación de crisis</h3>
                                        <p class="step-desc">Escenarios de alta intensidad en war room.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 4 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">4</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="20" x2="18" y2="10" />
                                            <line x1="12" y1="20" x2="12" y2="4" />
                                            <line x1="6" y1="20" x2="6" y2="14" />
                                            <path d="M3 3v18h18" />
                                        </svg>
                                    </div>
                                    <div class="module-card--content-text">
                                        <h3 class="step-title">Evaluación y Scorecard</h3>
                                        <p class="step-desc">Medición del desempeño con KPIs: URR, MPR, TTR.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 5 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">5</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="7" />
                                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                                        </svg>
                                    </div>
                                    <div class="module-card--content-text">
                                        <h3 class="step-title">Certificación</h3>
                                        <p class="step-desc">Demuestra tu aprendizaje y recibe tu certificación profesional.</p>
                                    </div>
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
                <div class="cta-container">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext">Módulos de certificación</div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <!-- Card 1 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">1</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" />
                                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                        </svg>
                                    </div>
                                    <p>Investigación y estudios de crisis. Radar de riesgos: tendencias 2026 y casos actuales</p>
                                </div>
                            </div>
                            <!-- Card 2 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">2</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 18a10 10 0 0 1 20 0" />
                                            <line x1="12" y1="18" x2="12" y2="14" />
                                            <line x1="12" y1="18" x2="16" y2="11" />
                                            <circle cx="12" cy="18" r="2" />
                                        </svg>
                                    </div>
                                    <p>Herramientas y parámetros de medición de una crisis y su respuesta</p>
                                </div>
                            </div>
                            <!-- Card 3 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">3</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22V2M12 2C9.5 2 7.5 4 7.5 6.5c0 1 .5 2 1.5 2.5C7 9.5 6 11 6 13c0 2 1.5 3.5 3.5 3.5h.5c0 1 .5 2 1.5 2.5M12 2c2.5 0 4.5 2 4.5 4.5 0 1-.5 2-1.5 2.5 2 .5 3 2 3 4 0 2-1.5 3.5-3.5 3.5h-.5c0 1-.5 2-1.5 2.5" />
                                            <path d="M7.5 6.5C5 6.5 3 8.5 3 11c0 2.5 2 4.5 4.5 4.5M16.5 6.5c2.5 0 4.5 2 4.5 4.5 0 2.5-2 4.5-4.5 4.5" />
                                        </svg>
                                    </div>
                                    <p>Entorno mediático y digital: el nuevo rol de la Inteligencia Artificial</p>
                                </div>
                            </div>
                            <!-- Card 4 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">4</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                                        </svg>
                                    </div>
                                    <p>Comunicación estratégica para manejo de crisis 4.0</p>
                                </div>
                            </div>
                            <!-- Card 5 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">5</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="4" y1="9" x2="20" y2="9" />
                                            <line x1="4" y1="15" x2="20" y2="15" />
                                            <line x1="10" y1="3" x2="8" y2="21" />
                                            <line x1="16" y1="3" x2="14" y2="21" />
                                        </svg>
                                    </div>
                                    <p>Procesos para un manejo ágil de crisis en redes sociales</p>
                                </div>
                            </div>
                            <!-- Card 6 -->
                            <div class="module-card">
                                <div class="module-card--content">
                                    <span class="module-number">6</span>
                                    <div class="module-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 18h-2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2l5-4v20l-5-4z" />
                                            <path d="M19 9a5 5 0 0 1 0 6" />
                                        </svg>
                                    </div>
                                    <p>Control de narrativa y arquitectura de vocerías</p>
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

        <!-- Certifications info bar -->
        <div class="certifications-info-bar">
            <!-- Item 1 -->
            <div class="info-bar-item">
                <div class="info-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <circle cx="18" cy="18" r="3" />
                        <line x1="22" y1="22" x2="20" y2="20" />
                    </svg>
                </div>
                <div class="info-bar-text">
                    <strong>Modalidad: Presencial y en vivo</strong>
                    <span>Grupos privados por empresa</span>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="info-bar-item">
                <div class="info-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                        <line x1="8" y1="21" x2="16" y2="21" />
                        <line x1="12" y1="17" x2="12" y2="21" />
                        <polygon points="10 8 16 11 10 14 10 8" />
                    </svg>
                </div>
                <div class="info-bar-text">
                    <strong>También disponible en línea</strong>
                    <span>(en vivo, no grabado)</span>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="info-bar-item">
                <div class="info-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                </div>
                <div class="info-bar-text">
                    <strong>Los módulos pueden cursarse</strong>
                    <span>de manera individual</span>
                </div>
            </div>
        </div>

        <!-- Modules Footer panel -->
        <div class="modules-footer-panel">
            <p>Los módulos pueden cursarse de manera individual. La Certificación se obtiene al cursar los 6 módulos, la simulación y demostrar el aprendizaje adquirido a través de una evaluación rigurosa.</p>
        </div>
    </div>
</section>
