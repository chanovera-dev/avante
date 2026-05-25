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
    $certification_intro = get_field('certification_intro');

    $sections = [
        'hero',
        // 'trust-bar',
        'about',
        'certification' => !empty($certification_intro) ? true : false,
        'signals',
        'how-works',
        'crisis-simulator',
        'cta',
        'upcoming-events',
        'news' => (int) wp_count_posts('news')->publish > 0,
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