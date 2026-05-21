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
 * @version 2.0.0
 */
?>
<?php
// ── ACF fields with static fallbacks ──────────────────────────────────────

$intro = get_field('certification_intro');

// Offer cards
$cert_slider1_title = get_field('certification_process_title') ?: 'Tu proceso de certificación';
$cert_slider2_title = get_field('certification_results_title') ?: 'Lo que dominarás';
$cert_footer_text   = get_field('certification_footer_text')   ?: 'Los módulos pueden cursarse de manera individual. La Certificación se obtiene al cursar los 6 módulos, la simulación y demostrar el aprendizaje adquirido a través de una evaluación rigurosa.';

// CTA Panel
$cert_urgency_text  = get_field('certification_urgency_text')    ?: 'Próxima cohorte: <strong>15 de junio</strong> — Últimos lugares disponibles';
$cert_cta_headline  = get_field('certification_cta_headline')    ?: 'Obtén tu Certificación Oficial en Gestión de Crisis';
$cert_cta_subhead   = get_field('certification_cta_subheadline') ?: 'Avalamos tus conocimientos con la primera certificación especializada de la región. Únete a la próxima generación de expertos.';

// CTA Panel
$cert_cta_bg        = get_field('certification_cta_background');
$cert_cta_bg_url    = $cert_cta_bg
    ? (is_array($cert_cta_bg) ? $cert_cta_bg['url'] : $cert_cta_bg)
    : get_template_directory_uri() . '/assets/img/certificaciones-big.webp';


$cert_cta_btn1_text = get_field('certification_cta_btn1_text')   ?: 'Inscribirme ahora';
$cert_cta_btn1_url  = get_field('certification_cta_btn1_url')    ?: '/#cta';
$cert_cta_btn2_text = get_field('certification_cta_btn2_text')   ?: 'Descargar Temario';
$cert_cta_btn2_file = get_field('certification_cta_btn2_file');
$cert_cta_btn2_url  = '';
if ( $cert_cta_btn2_file ) {
    if ( is_array( $cert_cta_btn2_file ) ) {
        $cert_cta_btn2_url = $cert_cta_btn2_file['url'];
    } elseif ( is_numeric( $cert_cta_btn2_file ) ) {
        $cert_cta_btn2_url = wp_get_attachment_url( $cert_cta_btn2_file );
    } else {
        $cert_cta_btn2_url = $cert_cta_btn2_file;
    }
}
if ( ! $cert_cta_btn2_url ) {
    $cert_cta_btn2_url = '/temario.pdf';
}
$cert_cta_microcopy = get_field('certification_cta_microcopy')   ?: 'Avalado internacionalmente · Plazas limitadas';



/**
 * Renders an SVG icon by its attachment ID, theme key, URL, or fallback.
 */
if ( ! function_exists( 'avante_render_acf_icon' ) ) {
    function avante_render_acf_icon( $icon ) {
        if ( ! $icon ) {
            return '';
        }
        if ( is_numeric( $icon ) ) {
            $svg_path = get_attached_file( $icon );
            if ( $svg_path && file_exists( $svg_path ) ) {
                return file_get_contents( $svg_path );
            }
            return wp_get_attachment_image( $icon, 'full' );
        }
        if ( is_string( $icon ) && filter_var( $icon, FILTER_VALIDATE_URL ) ) {
            if ( pathinfo( parse_url( $icon, PHP_URL_PATH ), PATHINFO_EXTENSION ) === 'svg' ) {
                $url_path = parse_url( $icon, PHP_URL_PATH );
                $absolute_path = ABSPATH . ltrim( $url_path, '/' );
                if (file_exists($absolute_path)) {
                    return file_get_contents( $absolute_path );
                }
            }
            return '<img src="' . esc_url( $icon ) . '" alt="" class="svg-icon">';
        }
        return avante_get_icon( $icon ) ?: $icon;
    }
}
?>

<section id="certification" class="block">

    <div class="content">

        <!-- ══ STAGE 1 · HOOK ══════════════════════════════════════════════ -->
        <div class="funnel-hook">
            <div class="heading">
                <div class="intro-content">
                    <?php
                    if ($intro):
                        echo apply_filters('the_content', $intro);
                    endif;
                    ?>
                </div>
            </div>

            <!-- ══ PROOF: Pain pills + Stats unidos ══════════════════════ -->
            <div class="funnel-proof card-reveal" aria-label="Por qué certificarte">

                <!-- Fila superior: pain-point pills -->
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

                <!-- Fila inferior: estadísticas -->
                <div class="funnel-proof__stats" aria-label="Estadísticas de la certificación">
                    <?php if (have_rows('certification_stats')) : ?>
                        <?php while (have_rows('certification_stats')) : the_row(); ?>
                            <div class="funnel-stat">
                                <span class="funnel-stat__number pretext-reveal"><?= esc_html(get_sub_field('stat_number')); ?></span>
                                <span class="funnel-stat__label pretext-reveal"><?= esc_html(get_sub_field('stat_text')); ?></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No hay estadísticas definidas.</p>
                    <?php endif;?>
                </div>

            </div><!-- /.funnel-proof -->
        </div>

        <!-- ══ STAGE 3 · OFFER (cards grid + CTA panel) ════════════════════ -->
        <div class="grid-containers">
            <div class="containers">

                <!-- Slider 1: Proceso de certificación -->
                <div class="cert-container card-reveal">

                    <div class="span-pretext--wrapper">
                        <div class="span-pretext"><?= esc_html($cert_slider1_title); ?></div>
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

                <!-- Slider 2: Módulos de certificación -->
                <div class="cert-container card-reveal">
                    <div class="span-pretext--wrapper">
                        <div class="span-pretext"><?= esc_html($cert_slider2_title); ?></div>
                    </div>
                    <div class="slideshow--wrapper">
                        <div class="slideshow">
                            <?php if ( have_rows('certification_results_cards') ) : ?>
                                <?php $count = 1; while ( have_rows('certification_results_cards') ) : the_row();
                                    $icon  = get_sub_field('icon');
                                    $desc  = get_sub_field('description');
                                ?>
                                <div class="module-card">
                                    <div class="module-card--content">
                                        <span class="module-number"><?= $count++; ?></span>
                                        <div class="module-icon"><?= avante_render_acf_icon($icon); ?></div>
                                        <div class="module-card--content-text">
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

                <!-- Slider 3: Modalidades -->
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

            </div><!-- /.containers -->

            <div class="modules-footer-panel">
                <p class="title-reveal"><?= wp_kses_post($cert_footer_text); ?></p>
            </div>

            <!-- ══ STAGE 4 · CTA PANEL ══════════════════════════════════════ -->
            <div class="cert-panel card-reveal"
                 style="background-image: url('<?= esc_url($cert_cta_bg_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">

                <div class="cert-panel-backdrop"></div>

                <div class="cert-panel-content">

                    <!-- Urgency banner -->
                    <div class="cert-urgency-banner">
                        <?= avante_get_icon('clock-history');?>
                        <?= wp_kses_post($cert_urgency_text); ?>
                    </div>

                    <h2 class="cert-headline"><?= esc_html($cert_cta_headline); ?></h2>
                    <p class="cert-subheadline"><?= esc_html($cert_cta_subhead); ?></p>

                    <!-- Guarantee strip -->
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
                        <?php if ($cert_cta_btn2_text && $cert_cta_btn2_url): ?>
                        <a href="<?= esc_url($cert_cta_btn2_url); ?>" class="btn hollow cert-btn-secondary" id="cert-secondary-button" download target="_blank">
                            <?= esc_html($cert_cta_btn2_text); ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <p class="cert-microcopy">
                        <?= avante_get_icon('shield-check'); ?>
                        <?= esc_html($cert_cta_microcopy); ?>
                    </p>

                </div><!-- /.cert-panel-content -->
            </div><!-- /.cert-panel -->

        </div><!-- /.grid-containers -->
        

    </div><!-- /.content -->
</section>
