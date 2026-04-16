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
$hero_action_file_button = get_field('hero_action_file_button');
$hero_action_file_button_label = get_field('hero_action_file_button_label');

if (empty($hero_span) && empty($hero_first_content) && empty($hero_action_file_button)) {
    return;
}
?>
<section id="hero" class="block">
    <div class="content wide hero--content heading">

        <div class="hero-content--text">
            <?php if ($hero_span): ?>
                <span class="span-pretext"><?php echo esc_html($hero_span); ?></span>
            <?php endif; ?>

            <?php if ($hero_first_content): ?>
                <div class="hero-description">
                    <?php echo apply_filters( 'the_content', $hero_first_content ); ?>
                </div>
            <?php endif; ?>

            <?php if (($hero_action_file_button && $hero_action_file_button_label)): ?>
                <div class="cta-buttons">
                    <?php 
                        // Manage both array and string return types from ACF File field
                        $file_url = is_array($hero_action_file_button) ? $hero_action_file_button['url'] : $hero_action_file_button; 
                    ?>
                    <a href="<?php echo esc_url($file_url); ?>" class="btn primary large" download>
                        <?php
                        echo avante_get_icon('file');
                        echo esc_html($hero_action_file_button_label);
                        ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>