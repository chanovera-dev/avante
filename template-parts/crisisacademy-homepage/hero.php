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
$hero_second_content = get_field('hero_second_content');

if (empty($hero_span) && empty($hero_first_content) && empty($hero_action_button)) {
    return;
}
?>
<section id="hero" class="block">
    <div class="content">
        <?php if ($hero_span): ?>
            <span class="span-pretext"><?php echo esc_html($hero_span); ?></span>
        <?php endif; ?>

        <?php if ($hero_first_content): ?>
            <div class="hero-description">
                <?php echo apply_filters( 'the_content', $hero_first_content ); ?>
            </div>
        <?php endif; ?>
        <div class="types-container">
            <?php
        if (have_rows('types_container')) :
            while (have_rows('types_container')) :
                the_row();
                $icon = get_sub_field('types_container_icon');
                $text = get_sub_field('types_container_text');
                ?>
                <div class="type-item">
                    <?php if (!empty($icon) && is_array($icon)): ?>
                        <img src="<?= esc_url($icon['url']) ?>" alt="<?= esc_attr($icon['alt']) ?>" width="150px" height="150px" loading="lazy">
                    <?php endif; ?>
                    <p><?= esc_html($text) ?></p>       
                </div>
            <?php endwhile;
        else :
            echo '<p>No se encontraron métodos de tipos.</p>';
        endif;        
        ?>
        </div>
        <?php if ($hero_action_button && $hero_action_button_label): ?>
            <div class="cta-container">
                <a href="<?= esc_url($hero_action_button); ?>" class="btn primary">
                    <?= avante_get_icon('forward'); ?>
                    <?= esc_html($hero_action_button_label); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>