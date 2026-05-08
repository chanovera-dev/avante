<?php
/**
 * Template Name: Crisis Academy Homepage
 * 
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php
    $directory = get_template_directory() . '/template-parts/crisisacademy-homepage';

    $sections = [
        'hero',
        'signals',
        'about',
        'how-works',
        'certifications',
        // 'blog' => !empty(get_posts(['post_type' => 'post', 'posts_per_page' => 1])),
        // 'special' => !empty(get_posts(['post_type' => 'special', 'posts_per_page' => 1])),
        // 'faq',
    ];

    foreach ($sections as $section => $condition) {
        if (is_int($section)) {
            $section = $condition;
            $condition = true;
        }

        if ($condition && file_exists("$directory/$section.php")) {
            include "$directory/$section.php";
        }
    }
    ?>
</main>

<?php get_footer();