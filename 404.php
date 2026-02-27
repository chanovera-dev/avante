<?php
/**
 * 404 Error Page Template
 *
 * This template is used when WordPress cannot find the requested content.
 * It provides a friendly message to users, a link to return to the homepage,
 * and can be styled or enhanced with additional navigation, search, or suggested content.
 *
 * @package Avante
 * @since Avante 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <section class="block not-found__wrapper">
        <div class="content not-found">
            <h1><?php esc_html_e('404', 'avante'); ?></h1>
            <h2><?php esc_html_e('Página no encontrada.', 'avante'); ?></h2>

            <!-- Back to Homepage Button -->
            <button class="btn primary" onclick="window.location.href='<?= site_url(); ?>'" aria-name="Link to go home">
                <?= avante_get_icon('home'); ?>
                <?php esc_html_e('Ir al inicio', 'avante'); ?>
            </button>
        </div>
    </section>
</main><!-- .site-main -->

<?php get_footer(); ?>