<?php
/**
 * Single Participant Template
 *
 * Template for displaying a single participant post (Custom Post Type: 'participant').
 * This file includes the full participant details.
 *
 * @package Avante
 * @since Avante 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="block">
            <div class="content">
                <div class="container">
                    <h1 class="title-page">Benchmark de competencias en comunicación</h1>
                    <h2 class="subtitle-page"><?php the_title(); ?></h2>
                    
                    <?php if ( $position = get_field('position') ) : ?>
                        <h3 class="position"><?php echo esc_html( $position ); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ( $company = get_field('company_name') ) : ?>
                        <h3 class="company"><?php echo esc_html( $company ); ?></h3>
                    <?php endif; ?>
                    
                    <?php
                    $global_excluded = get_option('storytelling_global_excluded_metrics', array());
                    if (!is_array($global_excluded)) $global_excluded = array();

                    $label_to_db_key = array(
                        'Lenguaje no verbal'   => 'm_lenguaje_no_verbal',
                        'Dirige la entrevista' => 'm_dirige_entrevista',
                        'Mensajes memorables'  => 'm_mensajes',
                        'Preguntas incisivas'  => 'm_preguntas_incisivas',
                        'Frases citables'      => 'm_frases_citables',
                        'Usa datos, cifras'    => 'm_usa_datos',
                        'Valores e historias'  => 'm_habla_valores'
                    );

                    $label_to_acf_key = array(
                        'Lenguaje no verbal'   => 'no_verbal_language',
                        'Dirige la entrevista' => 'manage_interview',
                        'Mensajes memorables'  => 'memorable_messages',
                        'Preguntas incisivas'  => 'incisive_questions',
                        'Frases citables'      => 'soundbites_messages',
                        'Usa datos, cifras'    => 'show_data',
                        'Valores e historias'  => 'show_storytelling'
                    );

                    $total_score = 0;
                    $valid_count = 0;

                    foreach ( $label_to_acf_key as $label => $acf_key ) {
                        $db_key = $label_to_db_key[ $label ];
                        if ( in_array( $db_key, $global_excluded ) ) continue;
                        
                        $local_excluded = json_decode(get_post_meta(get_the_ID(), 'excluded_metrics', true), true);
                        if ( is_array($local_excluded) && in_array($db_key, $local_excluded) ) continue;
                        
                        $val = get_field( $acf_key );
                        if (empty($val) || $val === 'No hay datos') continue;

                        $score = 0;
                        if ( $val === 'Buen vocero/a' ) $score = 2.5;
                        elseif ( $val === 'Experto/a' ) $score = 5;
                        elseif ( $val === 'Manejo insuficiente' ) $score = 1;

                        $total_score += $score;
                        $valid_count++;
                    }
                    
                    $average = $valid_count > 0 ? round($total_score / $valid_count, 1) : 0;
                    
                    if ($valid_count > 0) :
                    ?>
                    <div class="data-participant-item mt-3">
                        <h3 class="data-participant-item-label" style="color: var(--wp--preset--color--focus);">Promedio de competencias:</h3>
                        <span class="data-participant-item-value" style="font-size: 1.25rem; font-weight: 700; color: var(--wp--preset--color--focus);">
                            <?php echo number_format($average, 1); ?> <span style="font-size: 1rem; font-weight: 400; color: var(--wp--preset--color--contrast);">/ 5.0</span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="container">
                    <?php 
                    $avatar_id = get_field('avatar');
                    if ( $avatar_id ) {
                        // Output the image safely letting WordPress handle attributes
                        echo wp_get_attachment_image( $avatar_id, 'large', false, array( 'alt' => get_the_title(), 'class' => 'avatar' ) );
                    }
                    ?>
                </div>
            </div>
        </header>
        <section class="block metadata-wrapper">
            <div class="content metadata">
                <div class="container">
                    <h2 class="title-section">Datos del participante</h2>
                    <div class="data-participant">
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Nombre:</h3>
                            <span class="data-participant-item-value"><?php the_title(); ?></span>
                        </div>
                        <?php if ( $position = get_field('position') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Posición:</h3>
                            <span class="data-participant-item-value"><?php echo esc_html( $position ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $company = get_field('company_name') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Empresa:</h3>
                            <span class="data-participant-item-value"><?php echo esc_html( $company ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $ranking_p = get_field('personal_ranking') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Ranking de reputación personal:</h3>
                            <span class="data-participant-item-value"><?php echo esc_html( $ranking_p ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $ranking_i = get_field('institutional_ranking') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Ranking de reputación institucional:</h3>
                            <span class="data-participant-item-value"><?php echo esc_html( $ranking_i ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $others = get_field('others') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Presencia y dominio escénico:</h3>
                            <span class="data-participant-item-value"><?php echo wp_kses_post( $others ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $observations = get_field('observations') ) : ?>
                        <div class="data-participant-item">
                            <h3 class="data-participant-item-label">Desempeño retórico y contenidos:</h3>
                            <span class="data-participant-item-value"><?php echo wp_kses_post( $observations ); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="container">
                    <?php
                    $global_excluded = get_option('storytelling_global_excluded_metrics', array());
                    if (!is_array($global_excluded)) {
                        $global_excluded = array();
                    }

                    $label_to_db_key = array(
                        'Lenguaje no verbal'   => 'm_lenguaje_no_verbal',
                        'Dirige la entrevista' => 'm_dirige_entrevista',
                        'Mensajes memorables'  => 'm_mensajes',
                        'Preguntas incisivas'  => 'm_preguntas_incisivas',
                        'Frases citables'      => 'm_frases_citables',
                        'Usa datos, cifras'    => 'm_usa_datos',
                        'Valores e historias'  => 'm_habla_valores'
                    );

                    $label_to_acf_key = array(
                        'Lenguaje no verbal'   => 'no_verbal_language',
                        'Dirige la entrevista' => 'manage_interview',
                        'Mensajes memorables'  => 'memorable_messages',
                        'Preguntas incisivas'  => 'incisive_questions',
                        'Frases citables'      => 'soundbites_messages',
                        'Usa datos, cifras'    => 'show_data',
                        'Valores e historias'  => 'show_storytelling'
                    );

                    $radar_labels = array();
                    $radar_values = array();
                    $has_data = false;

                    foreach ( $label_to_acf_key as $label => $acf_key ) {
                        $db_key = $label_to_db_key[ $label ];
                        
                        // Check if it is excluded globally by admin
                        if ( in_array( $db_key, $global_excluded ) ) {
                            continue;
                        }
                        
                        // Try to check local exclusion if exists
                        $local_excluded = json_decode(get_post_meta(get_the_ID(), 'excluded_metrics', true), true);
                        if ( is_array($local_excluded) && in_array($db_key, $local_excluded) ) {
                            continue;
                        }
                        
                        $val = get_field( $acf_key );
                        $score = 0;
                        if ( $val === 'Buen vocero/a' ) {
                            $score = 2.5;
                            $has_data = true;
                        } elseif ( $val === 'Experto/a' ) {
                            $score = 5;
                            $has_data = true;
                        } elseif ( $val === 'Manejo insuficiente' ) {
                            $score = 1;
                            $has_data = true;
                        }

                        $radar_labels[] = $label;
                        $radar_values[] = $score;
                    }
                    ?>
                    
                    <?php if ( $has_data ) : ?>
                        <div id="participant-radar-<?php the_ID(); ?>" style="width:100%; height:100%; min-height:400px;"></div>
                        
                        <!-- Load ApexCharts just for this instance if not loaded -->
                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var options = {
                                    series: [{
                                        name: 'Puntaje',
                                        data: <?php echo json_encode($radar_values); ?>
                                    }],
                                    chart: {
                                        height: '100%',
                                        width: '100%',
                                        type: 'radar',
                                        toolbar: { show: false },
                                        animations: { enabled: true }
                                    },
                                    labels: <?php echo json_encode($radar_labels); ?>,
                                    yaxis: {
                                        min: 0,
                                        max: 5,
                                        tickAmount: 5,
                                        labels: {
                                            style: { colors: ['#323232'] },
                                            formatter: function(val, i) {
                                                if(i % 2 === 0) { return val; } else { return ''; }
                                            }
                                        }
                                    },
                                    xaxis: {
                                        labels: {
                                            style: {
                                                colors: Array(<?php echo count($radar_labels); ?>).fill('#323232'),
                                                fontSize: '16px',
                                                fontFamily: 'Helvetica, Arial, sans-serif'
                                            }
                                        }
                                    },
                                    markers: {
                                        size: 4,
                                        colors: ['#fff'],
                                        strokeColor: '#0073aa',
                                        strokeWidth: 2
                                    },
                                    fill: {
                                        opacity: 0.2,
                                        colors: ['#0073aa']
                                    },
                                    stroke: {
                                        show: true,
                                        width: 2,
                                        colors: ['#0073aa'],
                                        dashArray: 0
                                    }
                                };
                    
                                var chart = new ApexCharts(document.querySelector("#participant-radar-<?php the_ID(); ?>"), options);
                                chart.render();
                            });
                        </script>
                    <?php else: ?>
                        <p style="color: #666; font-style: italic;">No hay suficientes datos de métricas para generar la gráfica.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <section class="block participant-footer">
            <div class="content">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/CE-red-logo.png" alt="Logo de Carolina Eslava" width="150" height="61" loading="lazy">
            </div>
        </section>
    </article>
</main>

<?php get_footer(); ?>