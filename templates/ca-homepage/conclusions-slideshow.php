<section class="block">
    <div class="content">
        <div class="container">
            <h2>Conclusiones</h2>
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                    $args = array(
                        'post_type' => 'conclusion',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );

                    $quotes_query = new WP_Query($args);

                    if ($quotes_query->have_posts()):
                        while ($quotes_query->have_posts()):
                            $quotes_query->the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('quote-item'); ?>>
                                <div class="quote-content is-layout-constrained">
                                    <?php the_content(); ?>
                                </div>
                            </article>

                        <?php endwhile;
                        wp_reset_postdata();
                    else:
                        echo '<p>No se encontraron conclusiones recientes.</p>';
                    endif;
                    ?>
                </div>
            </div>
            <div class="slideshow-bullets-wrapper">
                <button class="slideshow-prev btn-pagination small-pagination" aria-label="siguiente diapositiva">
                    <?= avante_get_icon('backward'); ?>
                </button>
                <div class="slideshow-bullets bullets"></div>
                <button class="slideshow-next btn-pagination small-pagination" aria-label="anterior diapositiva">
                    <?= avante_get_icon('forward'); ?>
                </button>
            </div>
        </div>
    </div>
</section>