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
                    <div class="signal-item">
                        <div class="signal-graph">
                            <img src="<?= $icon['url'] ?>" alt="<?= $icon['alt'] ?>" srcset="" width="100px" height="100px" loading="lazy">
                        </div>
                        <?php if( $number ): ?>
                            <div class="signal-number"><span class="number"><?= $number ?></span><span class="sign">%</span></div>
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
            <p class="source">Fuente: Reporte anual 2025 ICM</p>
        </div>
        <?php if ($subtitle): ?>
            <?php echo apply_filters( 'the_content', $subtitle ); ?>
        <?php endif; ?>
    </div>
</section>