<?php
$sectionTitle = get_field('how_it_works_section_title');
$cta = get_field('how_it_works_cta');
?>
<section id="how-works" class="block">
    <div class="content">
        <span class="span-pretext scramble-letters"><?php echo esc_html($sectionTitle); ?></span>
        <h2 class="title-section">Tres soluciones para fortalecer tu preparación ante una crisis</h2>
        <div class="how-works--cards-container">
            <?php
                if (have_rows('how_it_works_cards')) : 
                    while (have_rows('how_it_works_cards')) : the_row();
                        $logo = get_sub_field('how_it_works_logo');
                        $title = get_sub_field('how_it_works_title');
                        $description = get_sub_field('how_it_works_description');
                        $content = get_sub_field('how_it_works_content');
                        $buttonLabel = get_sub_field('how_it_works_button_label');
                        ?>
                        <article class="how-it-works--card animate-in--scale-up glass-border-bright">
                            <div class="how-it-works--card-content"><img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" width="64px" height="64px" loading="lazy">
                            <h3><?php echo esc_html($title); ?></h3>
                            <?php echo apply_filters('the_content', $description); ?></div>
                            <?php if ($content && $buttonLabel): ?>
                                <button class="btn-more-info btn primary" data-title="<?php echo esc_attr($title); ?>">
                                    <?= avante_get_icon('info-circle'); ?>
                                    <?php echo esc_html($buttonLabel); ?>
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