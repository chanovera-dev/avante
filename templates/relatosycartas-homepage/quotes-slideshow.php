<?php
$args = array(
    'post_type' => 'quote',
    'posts_per_page' => 7,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
);

$quotes_query = new WP_Query($args);

if ($quotes_query->have_posts()): ?>
<div class="content quotes-heading">
    <div class="container glass-border-bright">
        <div class="post__overlay"></div>
        <div class="clouds--wrapper">
            <div class="clouds">
                <div class="c1 one"></div>
                <div class="c1 two"></div>
                <div class="c1 three"></div>
                <div class="c1 four"></div>
                <div class="c2 one"></div>
                <div class="c2 two"></div>
                <div class="c2 three"></div>
                <div class="c2 four"></div>
            </div>
        </div>
        <div class="tree--wrapper">
            <img class="tree0" src="<?php echo get_template_directory_uri() ?>/assets/img/tree-min-0.webp" alt="" srcset="" loading="lazy">
            <img class="tree1" src="<?php echo get_template_directory_uri() ?>/assets/img/tree-min-1.webp" alt="" srcset="" loading="lazy">
            <img class="tree2" src="<?php echo get_template_directory_uri() ?>/assets/img/tree-min-2.webp" alt="" srcset="" loading="lazy">
        </div>
        <div class="grass--wrapper">
            <?php 
            // for ($i = 0; $i < 600; $i++): 
            //     $h = rand(25, 60);
            //     $speed = rand(20, 50) / 10;
            //     $delay = rand(0, 50) / -10;
            //     $left = ($i / 6);
            //     $rot = rand(-15, 15);
            // ?>
                <!-- <div class="blade" style="--h: <?= $h ?>px; --speed: <?= $speed ?>s; --delay: <?= $delay ?>s; --rot: <?= $rot ?>deg; left: <?= $left ?>%;"></div> -->
            <?php //endfor; ?>
        </div>
        <div class="slideshow--wrapper">
            <div class="slideshow">
                <?php
                while ($quotes_query->have_posts()):
                    $quotes_query->the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('quote-item'); ?>>
                        <div class="quote-content">
                            <?php the_content(); ?>
                        </div>
                    </article>

                <?php endwhile;
                wp_reset_postdata();
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
<?php endif; ?>