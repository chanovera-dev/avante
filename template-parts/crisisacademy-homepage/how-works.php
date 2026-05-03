<?php
$cta = get_field('how_it_works_cta');
?>
<section id="how-works" class="block">
    <div class="content">
        <span class="span-pretext scramble-letters">¿Cómo funciona?</span>
        <div class="cards">
            <?php
                if (have_rows('how_it_works_cards')) : 
                    while (have_rows('how_it_works_cards')) : the_row();
                        $title = get_sub_field('how_it_works_title');
                        $description = get_sub_field('how_it_works_description');
                        $content = get_sub_field('how_it_works_content');
                        ?>
                        <article class="how-it-works--card animate-in--scale-up glass-border-bright">
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php echo esc_html($description); ?></p>
                            <?php if ($content): ?>
                                <button class="btn-more-info" data-title="<?php echo esc_attr($title); ?>">
                                    <?= avante_get_icon('info-circle'); ?>
                                </button>
                                <div class="modal-complete-content" style="display: none;">
                                    <?php echo apply_filters('the_content', $content); ?>
                                </div>
                            <?php endif; ?>
                        </article>
                        <?php
                    endwhile;
                else:
                    echo '<p>No se encontraron tarjetas.</p>';
                endif;
            ?>
            <div class="cta-section">
                <?php echo apply_filters('the_content', $cta); ?>
            </div>
        </div>
    </div>
</section>

<div id="how-works--complete" class="how-works-modal" aria-hidden="true">
    <div class="how-works-modal-overlay"></div>
    <div class="how-works-modal-container">
        <button class="how-works-modal-close" aria-label="Cerrar modal">&times;</button>
        <div class="how-works-modal-content">
            <div class="modal-wysiwyg"></div>
        </div>
    </div>
</div>