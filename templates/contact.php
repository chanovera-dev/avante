<?php
/**
 * Template name: Contacto
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="block">
        <?php
        if (has_post_thumbnail()) {
            echo get_the_post_thumbnail(null, 'full', ['alt' => get_the_title(), 'loading' => 'lazy', 'class' => 'background-parallax', 'data-speed' => '0.5']);
        }
        ?>
        <div class="content">
            <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
        </div>
    </header>
    <?php wp_breadcrumbs(); ?>
</main><!-- .site-main -->

<?php get_footer(); ?>