<?php
/**
 * Template part for displaying the Signals section on the homepage.
 *
 * This section features statistical indicators showcasing pre-crisis warning signals.
 * All content is managed through Advanced Custom Fields (ACF).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */
$title = get_field('signals_title');
$subtitle = get_field('signals_subtitle');

if (empty($title)) {
    return;
}
?>
<section id="signals" class="block">
    <div class="content">
        <?php if ($title): ?>
            <?php echo apply_filters( 'the_content', $title ); ?>
        <?php endif; ?>
        <div class="signals-container">
            <?php if ( have_rows('signals_container') ): ?>
                <?php while ( have_rows('signals_container') ): the_row(); ?>
                    <?php
                        $icon = get_sub_field('signal_item_icon');
                        $number = get_sub_field('signal_item_number');
                        $label = get_sub_field('signal_item_label');
                        $info = get_sub_field('signal_item_info');
                    ?>
                    <div class="signal-item card-reveal">
                        <div class="signal-graph">
                            <?php if ( $number ) : 
                                $percent = intval( $number );
                                $dashoffset = 251.32 * ( 1 - $percent / 100 );
                                $anim_id = "draw-ring-" . $percent . "-" . uniqid();
                            ?>
                                <svg width="100%" height="100%" viewBox="0 0 100 100" class="tech-progress-svg" style="transform: rotate(-90deg);">
                                    <defs>
                                        <filter id="glow-<?= $percent ?>" x="-20%" y="-20%" width="140%" height="140%">
                                            <feGaussianBlur stdDeviation="3.5" result="blur" />
                                            <feMerge>
                                                <feMergeNode in="blur" />
                                                <feMergeNode in="SourceGraphic" />
                                            </feMerge>
                                        </filter>
                                        <linearGradient id="grad-<?= $percent ?>" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#00d9ff" />
                                            <stop offset="100%" stop-color="#0077ff" />
                                        </linearGradient>
                                    </defs>
                                    
                                    <!-- Inner tech radar rings -->
                                    <circle cx="50" cy="50" r="46" stroke="rgba(0, 217, 255, 0.04)" stroke-width="1" fill="none" />
                                    <circle cx="50" cy="50" r="34" stroke="rgba(0, 217, 255, 0.04)" stroke-width="1" fill="none" />
                                    
                                    <!-- Background track -->
                                    <circle cx="50" cy="50" r="40" stroke="rgba(0, 217, 255, 0.08)" stroke-width="5" fill="none" stroke-linecap="round" />
                                    
                                    <!-- Animated Progress Ring -->
                                    <circle cx="50" cy="50" r="40" 
                                            stroke="url(#grad-<?= $percent ?>)" 
                                            stroke-width="5.5" 
                                            fill="none" 
                                            stroke-linecap="round"
                                            stroke-dasharray="251.32" 
                                            stroke-dashoffset="251.32"
                                            filter="url(#glow-<?= $percent ?>)"
                                            class="progress-ring-circle-<?= $percent ?>" />
                                            
                                    <!-- Outer cyber ticks -->
                                    <circle cx="50" cy="50" r="44" stroke="rgba(0, 217, 255, 0.25)" stroke-width="1.5" fill="none" stroke-dasharray="1.5 5.5" />
                                    
                                    <!-- Scanning radar needle -->
                                    <line x1="50" y1="50" x2="50" y2="10" stroke="rgba(0, 217, 255, 0.2)" stroke-width="1.5" stroke-linecap="round" style="transform-origin: 50px 50px; animation: radar-needle-sweep 3s linear infinite;" />
                                    
                                    <style>
                                        .signal-item.is-visible .progress-ring-circle-<?= $percent ?> {
                                            animation: <?= $anim_id ?> 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                                        }
                                        @keyframes <?= $anim_id ?> {
                                            from { stroke-dashoffset: 251.32; }
                                            to { stroke-dashoffset: <?= $dashoffset ?>; }
                                        }
                                        @keyframes radar-needle-sweep {
                                            from { transform: rotate(0deg); }
                                            to { transform: rotate(360deg); }
                                        }
                                    </style>
                                </svg>
                            <?php else : ?>
                                <svg width="100%" height="100%" viewBox="0 0 100 100" class="tech-progress-svg" style="transform: rotate(-90deg);">
                                    <defs>
                                        <filter id="glow-spin" x="-20%" y="-20%" width="140%" height="140%">
                                            <feGaussianBlur stdDeviation="3.5" result="blur" />
                                            <feMerge>
                                                <feMergeNode in="blur" />
                                                <feMergeNode in="SourceGraphic" />
                                            </feMerge>
                                        </filter>
                                        <linearGradient id="grad-spin" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#00d9ff" />
                                            <stop offset="100%" stop-color="#0077ff" />
                                        </linearGradient>
                                    </defs>
                                    
                                    <!-- Inner tech radar rings -->
                                    <circle cx="50" cy="50" r="46" stroke="rgba(0, 217, 255, 0.04)" stroke-width="1" fill="none" />
                                    <circle cx="50" cy="50" r="34" stroke="rgba(0, 217, 255, 0.04)" stroke-width="1" fill="none" />
                                    
                                    <!-- Background track -->
                                    <circle cx="50" cy="50" r="40" stroke="rgba(0, 217, 255, 0.08)" stroke-width="5" fill="none" stroke-linecap="round" />
                                    
                                    <!-- Infinite Spinning Short Glowing Arc -->
                                    <circle cx="50" cy="50" r="40" 
                                            stroke="url(#grad-spin)" 
                                            stroke-width="5.5" 
                                            fill="none" 
                                            stroke-linecap="round"
                                            stroke-dasharray="60 192.32" 
                                            stroke-dashoffset="0"
                                            filter="url(#glow-spin)"
                                            style="transform-origin: 50px 50px; animation: infinite-arc-spin 2s linear infinite;" />
                                            
                                    <!-- Outer cyber ticks -->
                                    <circle cx="50" cy="50" r="44" stroke="rgba(0, 217, 255, 0.25)" stroke-width="1.5" fill="none" stroke-dasharray="1.5 5.5" />
                                    
                                    <!-- Scanning radar needle -->
                                    <line x1="50" y1="50" x2="50" y2="10" stroke="rgba(0, 217, 255, 0.2)" stroke-width="1.5" stroke-linecap="round" style="transform-origin: 50px 50px; animation: radar-needle-sweep 3s linear infinite;" />
                                    
                                    <!-- Fallback static icon inside the center of the spinning ring -->
                                    <?php if ( $icon ) : ?>
                                        <g style="transform: rotate(90deg); transform-origin: 50px 50px;">
                                            <image href="<?= esc_url( $icon['url'] ) ?>" x="32" y="32" width="36" height="36" style="opacity: 0.85;" />
                                        </g>
                                    <?php endif; ?>

                                    <style>
                                        @keyframes infinite-arc-spin {
                                            from { transform: rotate(0deg); }
                                            to { transform: rotate(360deg); }
                                        }
                                        @keyframes radar-needle-sweep {
                                            from { transform: rotate(0deg); }
                                            to { transform: rotate(360deg); }
                                        }
                                    </style>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <?php if( $number ): ?>
                            <div class="signal-number"><span class="number number-value-counter" data-target="<?= $number ?>" data-decimals="0" data-duration="7000"><?= $number ?></span><span class="sign">%</span></div>
                        <?php endif; ?>
                        <?php if( $label ): ?>
                            <span class="signal-label"><?= $label ?></span>
                        <?php endif; ?>
                        <?php if( $info ): ?>
                            <span class="signal-info"><?php echo apply_filters( 'the_content', $info ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                <?php else : ?>
                    <p>No se encontraron señales.</p>
            <?php endif; ?>
            <p class="source object-reveal">Fuente: <a href="https://crisisconsultant.com/icm-annual-crisis-report/" target="_blank">Institute for Crisis Management (ICM)</a></p>
        </div>
        <?php if ($subtitle): ?>
            <?php echo apply_filters( 'the_content', $subtitle ); ?>
        <?php endif; ?>
    </div>
</section>