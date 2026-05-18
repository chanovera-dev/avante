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
<?php
// Variables de campos ACF con fallbacks a contenido estático actual
$cert_pretext       = get_field('cert_pretext') ?: 'Entrenamiento Especializado';
$cert_title         = get_field('cert_title') ?: 'La ruta definitiva para convertirte en un experto en gestión de crisis';

// Slider Titles
$cert_slider1_title = get_field('cert_slider1_title') ?: 'Certificación Profesional';
$cert_slider2_title = get_field('cert_slider2_title') ?: 'Módulos de certificación';
$cert_slider3_title = get_field('cert_slider3_title') ?: 'Modalidades';

// CTA Panel fields
$cert_cta_bg        = get_field('cert_cta_background');
$cert_cta_bg_url    = $cert_cta_bg ? (is_array($cert_cta_bg) ? $cert_cta_bg['url'] : $cert_cta_bg) : get_template_directory_uri() . '/assets/img/certificaciones-big.webp';
$cert_cta_headline  = get_field('cert_cta_headline') ?: 'Obtén tu Certificación Oficial en Gestión de Crisis';
$cert_cta_subhead   = get_field('cert_cta_subheadline') ?: 'Avalamos tus conocimientos con la primera certificación especializada de la región. Únete a la próxima generación de expertos.';
$cert_cta_btn1_text = get_field('cert_cta_btn1_text') ?: 'Inscribirme';
$cert_cta_btn1_url  = get_field('cert_cta_btn1_url') ?: '/#cta';
$cert_cta_btn2_text = get_field('cert_cta_btn2_text') ?: 'Descargar Temario';
$cert_cta_btn2_url  = get_field('cert_cta_btn2_url') ?: '/temario.pdf'; 
$cert_cta_microcopy = get_field('cert_cta_microcopy') ?: 'Avalado internacionalmente · Plazas limitadas';

// Footer Panel field
$cert_footer_text   = get_field('cert_footer_text') ?: 'Los módulos pueden cursarse de manera individual. La Certificación se obtiene al cursar los 6 módulos, la simulación y demostrar el aprendizaje adquirido a través de una evaluación rigurosa.';
?>
<section id="certification" class="block" style="background-image: linear-gradient(to bottom, var(--wp--preset--color--tertiary) 0%, color-mix(in srgb, var(--wp--preset--color--tertiary) 70%, transparent) 35%, color-mix(in srgb, var(--wp--preset--color--tertiary) 70%, transparent) 55%, var(--wp--preset--color--tertiary) 100%),
        url('<?= $cert_cta_bg_url ?>')">
    <!-- Entrenamiento y Módulos -->
    <div class="content">
        <span class="span-pretext pretext-reveal"><?= esc_html($cert_pretext); ?></span>
        <h2 class="title-section title-reveal"><?= esc_html($cert_title); ?></h2>
        <div class="grid-containers">
            <div class="containers">
                <div class="cert-container card-reveal">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext"><?= esc_html($cert_slider1_title); ?></div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('cert_slider1_cards') ) : ?>
                                <?php $count = 1; while ( have_rows('cert_slider1_cards') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $title = get_sub_field('title');
                                    $desc = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number"><?= $count++; ?></span>
                                        <div class="module-icon">
                                            <?= $icon; ?>
                                        </div>
                                        <div class="module-card--content-text">
                                            <h3 class="step-title"><?= esc_html($title); ?></h3>
                                            <p class="step-desc"><?= esc_html($desc); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else : ?>
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
                <div class="cert-container card-reveal">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext"><?= esc_html($cert_slider2_title); ?></div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('cert_slider2_cards') ) : ?>
                                <?php $count = 1; while ( have_rows('cert_slider2_cards') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $desc = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number"><?= $count++; ?></span>
                                        <div class="module-icon">
                                            <?= $icon; ?>
                                        </div>
                                        <p><?= esc_html($desc); ?></p>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else : ?>
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
                <div class="cert-container card-reveal">

                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('cert_info_bar') ) : ?>
                                <?php $count = 1; while ( have_rows('cert_info_bar') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $title = get_sub_field('title');
                                    $desc = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <div class="module-icon">
                                            <?= $icon; ?>
                                        </div>
                                        <div class="info-bar-text">
                                            <strong><?= esc_html($title); ?></strong>
                                            <span><?= esc_html($desc); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <!-- Card 1 -->
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number">1</span>
                                        <div class="module-icon">
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
                                </div>
                                <!-- Card 2 -->
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number">2</span>
                                        <div class="module-icon">
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
                                </div>
                                <!-- Card 3 -->
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number">3</span>
                                        <div class="module-icon">
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
            </div>
            <div class="cert-panel card-reveal" style="background-image: url('<?= esc_url($cert_cta_bg_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="cert-panel-backdrop"></div>
                <div class="cert-panel-content">
                    <h2 class="cert-headline"><?= esc_html($cert_cta_headline); ?></h2>
                    <p class="cert-subheadline"><?= esc_html($cert_cta_subhead); ?></p>
                    <div class="cert-actions">
                        <?php if ($cert_cta_btn1_text && $cert_cta_btn1_url): ?>
                        <a href="<?= esc_url($cert_cta_btn1_url); ?>" class="btn primary cert-btn-primary" id="cert-main-button">
                            <?= avante_get_icon('forward'); ?>
                            <?= esc_html($cert_cta_btn1_text); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($cert_cta_btn2_text && $cert_cta_btn2_url): ?>
                        <a href="<?= esc_url($cert_cta_btn2_url); ?>" class="btn hollow cert-btn-secondary" id="cert-secondary-button">
                            <?= esc_html($cert_cta_btn2_text); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <p class="cert-microcopy">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <?= esc_html($cert_cta_microcopy); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Modules Footer panel -->
        <div class="modules-footer-panel">
            <p><?= wp_kses_post($cert_footer_text); ?></p>
        </div>
    </div>
</section>
