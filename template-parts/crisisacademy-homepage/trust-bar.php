<?php
/**
 * Template part for displaying the Trust Bar on the homepage.
 *
 * Shows trust signals (logos + hard metrics) immediately below the hero
 * to establish authority at first glance (Revisión §1).
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.1.0
 */

// —— Trust Metrics (datos duros) ——
$metrics = [];
if (have_rows('trust_metrics')) {
    while (have_rows('trust_metrics')) {
        the_row();
        $metrics[] = [
            'number' => get_sub_field('metric_number'),
            'label'  => get_sub_field('metric_label'),
        ];
    }
}

// Fallback defaults if ACF fields are empty
if (empty($metrics)) {
    $metrics = [
        ['number' => '+150',  'label' => 'Directivos capacitados'],
        ['number' => '10+',   'label' => 'Años de experiencia'],
        ['number' => '98%',   'label' => 'Satisfacción'],
    ];
}

// —— Trust Logos ——
$logos = [];
if (have_rows('trust_logos')) {
    while (have_rows('trust_logos')) {
        the_row();
        $img  = get_sub_field('logo_image');
        $name = get_sub_field('logo_name');
        if ($img) {
            $logos[] = [
                'url'  => is_array($img) ? $img['url'] : $img,
                'alt'  => $name ?: (is_array($img) ? ($img['alt'] ?: 'Trust logo') : 'Trust logo'),
            ];
        }
    }
}

// Fallback placeholder logos when ACF is empty (using actual files in assets/logos/)
if (empty($logos)) {
    $theme_uri = get_stylesheet_directory_uri();
    $logos = [
        [
            'url' => $theme_uri . '/assets/logos/logo-Forbes.png',
            'alt' => 'Forbes',
        ],
        [
            'url' => $theme_uri . '/assets/logos/CNN.svg.png',
            'alt' => 'CNN',
        ],
        [
            'url' => $theme_uri . '/assets/logos/Bloomberg.png',
            'alt' => 'Bloomberg',
        ],
        [
            'url' => $theme_uri . '/assets/logos/el-economista.png',
            'alt' => 'El Economista',
        ]
    ];
}
?>
<section id="trust-bar" class="block" aria-label="Confianza y credenciales">
    <div class="content">
        <?php if (!empty($logos)) : ?>
            <div class="trust-bar__logos">
                <span class="trust-bar__logos-label object-reveal">Respaldados por</span>
                <div class="logos">
                    <?php foreach ($logos as $logo) : ?>
                    <img
                        src="<?= esc_url($logo['url']); ?>"
                        alt="<?= esc_attr($logo['alt']); ?>"
                        class="trust-bar__logo object-reveal"
                        loading="lazy"
                        decoding="async"
                    >
                <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($metrics)) : ?>
            <div class="trust-bar__metrics">
                <?php foreach ($metrics as $i => $m) : ?>
                    <?php if ($i > 0) : ?>
                        <span class="trust-bar__divider" aria-hidden="true"></span>
                    <?php endif; ?>
                    <div class="trust-bar__metric object-reveal">
                        <span class="trust-bar__metric-number"><?= esc_html($m['number']); ?></span>
                        <span class="trust-bar__metric-label"><?= esc_html($m['label']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
