<?php
/**
 * Template part for displaying the hero section on the homepage.
 *
 * This section features a main heading, subheading, and call-to-action buttons.
 * All content is managed through Advanced Custom Fields (ACF).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */
$hero_span = get_field('hero_span');
$hero_first_content = get_field('hero_first_content');
$hero_action_button = get_field('hero_action_button');
$hero_action_button_label = get_field('hero_action_button_label');
$hero_action_button_secondary = get_field('hero_action_button_secondary');
$hero_action_button_secondary_label = get_field('hero_action_button_secondary_label');
$hero_second_content = get_field('hero_second_content');

if (empty($hero_span) && empty($hero_first_content) && empty($hero_action_button) && empty($hero_action_button_secondary)) {
    return;
}

// —— Resolve URL, target, download and icon for Primary Button ——
$primary_url = '';
$primary_target = '';
$primary_download = '';
$primary_icon = 'forward';

if (!empty($hero_action_button)) {
    if (is_array($hero_action_button)) {
        $primary_url = $hero_action_button['url'] ?? '';
        $primary_target = ' target="_blank"';
        $primary_download = ' download';
        $primary_icon = 'file';
    } elseif (is_numeric($hero_action_button)) {
        $primary_url = wp_get_attachment_url($hero_action_button);
        $primary_target = ' target="_blank"';
        $primary_download = ' download';
        $primary_icon = 'file';
    } else {
        $primary_url = $hero_action_button;
        if (preg_match('/\.(pdf|zip|docx|xlsx|pptx)$/i', $primary_url)) {
            $primary_target = ' target="_blank"';
            $primary_download = ' download';
            $primary_icon = 'file';
        }
    }
}

// —— Resolve URL, target, download and icon for Secondary Button ——
$secondary_url = '';
$secondary_target = '';
$secondary_download = '';
$secondary_icon = 'forward';

if (!empty($hero_action_button_secondary)) {
    if (is_array($hero_action_button_secondary)) {
        $secondary_url = $hero_action_button_secondary['url'] ?? '';
        $secondary_target = ' target="_blank"';
        $secondary_icon = 'file';
    } elseif (is_numeric($hero_action_button_secondary)) {
        $secondary_url = wp_get_attachment_url($hero_action_button_secondary);
        $secondary_target = ' target="_blank"';
        $secondary_icon = 'file';
    } else {
        $secondary_url = $hero_action_button_secondary;
        if (preg_match('/\.(pdf|zip|docx|xlsx|pptx)$/i', $secondary_url)) {
            $secondary_target = ' target="_blank"';
            $secondary_icon = 'file';
        }
    }
}
?>
<section id="hero" class="block">
    <!-- .hero-grid is generated dynamically by hero.js -->
    <div class="hero-glow"></div>
    <div class="content">
        <?php if ($hero_span): ?>
            <div class="span-pretext object-reveal"><?php echo apply_filters( 'the_content', $hero_span ); ?></div>
        <?php endif; ?>

        <?php if ($hero_first_content): ?>
            <div class="hero-description">
                <?php echo apply_filters( 'the_content', $hero_first_content ); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($primary_url) || !empty($secondary_url)): ?>
            <div class="cta-container">
                <?php if (!empty($primary_url) && !empty($hero_action_button_label)): ?>
                    <a href="<?= esc_url($primary_url); ?>" class="btn primary object-reveal"<?= $primary_target; ?><?= $primary_download; ?>>
                        <?= avante_get_icon($primary_icon); ?>
                        <?= esc_html($hero_action_button_label); ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($secondary_url) && !empty($hero_action_button_secondary_label)): ?>
                    <a href="<?= esc_url($secondary_url); ?>" class="btn hollow object-reveal"<?= $secondary_target; ?><?= $secondary_download; ?>>
                        <?= avante_get_icon($secondary_icon); ?>
                        <?= esc_html($hero_action_button_secondary_label); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>