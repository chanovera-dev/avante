<?php
$args = array(
    'post_type' => 'experiences',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
);

$experiences_query = new WP_Query($args);
?>
<section id="experiences" class="block">
    <div class="content">
        <div class="container">
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                    if ($experiences_query->have_posts()):
                        while ($experiences_query->have_posts()):
                            $experiences_query->the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('experience-item'); ?>>
                                <div class="experience-content">
                                    <?php the_content(); ?>
                                </div>
                            </article>

                        <?php endwhile;
                        wp_reset_postdata();
                    else:
                        echo '<p>No se encontraron experiencias.</p>';
                    endif;
                    ?>
                </div>
            </div>
            <?php if ($experiences_query->have_posts()): ?>
            <div class="slideshow-bullets-wrapper">
                <button class="slideshow-prev btn-pagination small-pagination" aria-label="siguiente diapositiva">
                    <?= avante_get_icon('backward'); ?>
                </button>
                <div class="slideshow-bullets bullets"></div>
                <button class="slideshow-next btn-pagination small-pagination" aria-label="anterior diapositiva">
                    <?= avante_get_icon('forward'); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="cta">
            <span class="span-tag">Desafios</span>
            <h2>¿Te identificas?</h2>
            <p>A muchas empresas les pasa, aunque no siempre lo digan. El problema no es tu capacidad de liderazgo, es que en una emergencia, sin un protocolo claro, las emociones y la confusión toman el control.</p>
            <a href="#" class="btn primary"><?= avante_get_icon('video'); ?>Descubre nuestros cursos</a>
        </div>
    </div>
</section>