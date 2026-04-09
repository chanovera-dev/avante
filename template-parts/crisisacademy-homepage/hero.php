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
$hero_text = get_field('hero_text');
$hero_button_file = get_field('hero_button_file');
$hero_button_label = get_field('hero_button_label');

if (empty($hero_span) && empty($hero_text) && empty($hero_button_file)) {
    return;
}
?>
<section id="hero" class="block">
    <div class="content hero--content heading">

        <div class="hero-content--text">
            <?php if ($hero_span): ?>
                <span class="span-tag"><?php echo esc_html($hero_span); ?></span>
            <?php endif; ?>

            <?php if ($hero_text): ?>
                <?php echo wp_kses_post($hero_text); ?>
            <?php endif; ?>

            <?php if (($hero_button_file && $hero_button_label)): ?>
                <div class="cta-buttons">
                    <?php 
                        // Manage both array and string return types from ACF File field
                        $file_url = is_array($hero_button_file) ? $hero_button_file['url'] : $hero_button_file; 
                    ?>
                    <a href="<?php echo esc_url($file_url); ?>" class="btn primary" download>
                        <?php
                        echo avante_get_icon('file');
                        echo esc_html($hero_button_label);
                        ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>