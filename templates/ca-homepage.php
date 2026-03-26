<?php
/**
 * Template Name: "Ranking de voceros"
 * 
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php wp_breadcrumbs(); ?>
        
        <?php get_template_part('templates/ca-homepage/quotes-slideshow'); ?>
        <section class="block posts--body">
            <div class="content">
                <?php
                global $wp_query;
                $temp_query = $wp_query;

                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                $participants_args = array(
                    'post_type'      => 'participants',
                    'posts_per_page' => 12,
                    'paged'          => $paged,
                    'post_status'    => 'publish',
                    'meta_key'       => 'personal_ranking',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'ASC'
                );

                $wp_query = new WP_Query($participants_args);

                get_template_part('templates/archive/wp', 'loop');

                $wp_query = $temp_query;
                wp_reset_postdata();
                ?>
            </div>
        </section>
        <section class="block">
            <div class="content is-layout-constrained">
                <?php the_content(); ?>
            </div>
        </section>
    </article>
</main>

<?php get_footer(); ?>