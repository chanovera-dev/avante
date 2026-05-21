<?php
/**
 * Template Name: Crisis Academy Homepage
 * 
 */
// Load globally required helper functions.
require_once get_template_directory() . '/templates/helpers/acf-helpers.php';

get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php
    $directory = get_template_directory() . '/template-parts/crisisacademy-homepage';

    $sections = [
        'hero',
        'certification',
        'about',
        'signals',
        'how-works',
        'crisis-simulator',
        'cta',
        'upcoming-events',
        'news' => !empty(get_posts(['post_type' => 'news', 'posts_per_page' => 1])),
        'faq',
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