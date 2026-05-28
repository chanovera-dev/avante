<?php
$sectionTitle = get_field('how_it_works_section_title');
$cta = get_field('how_it_works_cta');
?>
<section id="how-works" class="block">
    <!-- Radar Background -->
    <div class="radar-bg">
        <div class="radar-grid"></div>
        <div class="radar-axis radar-axis-x"></div>
        <div class="radar-axis radar-axis-y"></div>
        <div class="radar-circle radar-circle-1"></div>
        <div class="radar-circle radar-circle-2"></div>
        <div class="radar-circle radar-circle-3"></div>
        <div class="radar-circle radar-circle-4"></div>
        <div class="radar-beam"></div>
        <!-- <div class="radar-blip blip-1"></div>
        <div class="radar-blip blip-2"></div>
        <div class="radar-blip blip-3"></div>
        <div class="radar-blip blip-4"></div>
        <div class="radar-blip blip-5"></div> -->
    </div>

    <div class="content">
        <div class="logo object-reveal">
            <div class="ring-wrap">
                <div class="glow-ring"></div>
                <div class="scene">
                    <div class="cube">
                        <div class="face front">
                            <div class="grid"></div>
                            <div class="ai-eye">
                                <div class="pupil"></div>
                                <span class="node node-1"></span>
                                <span class="node node-2"></span>
                                <span class="node node-3"></span>
                                <span class="node node-4"></span>
                            </div>
                        </div>
                        <div class="face back">
                            <div class="grid"></div>
                        </div>
                        <div class="face left">
                            <div class="grid"></div>
                        </div>
                        <div class="face right">
                            <div class="grid"></div>
                        </div>
                        <div class="face top">
                            <div class="grid"></div>
                        </div>
                        <div class="face bottom">
                            <div class="grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="span-pretext pretext-reveal"><?php echo esc_html($sectionTitle); ?></span>
        <h2 class="title-section title-reveal">Tres soluciones para fortalecer tu preparación ante una crisis</h2>
        <div class="how-works--cards-container">
            <?php
                if (have_rows('how_it_works_cards')) : 
                    while (have_rows('how_it_works_cards')) : the_row();
                        $card_num = get_row_index();
                        $title = get_sub_field('how_it_works_title');
                        $description = get_sub_field('how_it_works_description');
                        $content = get_sub_field('how_it_works_content');
                        $buttonLabel = get_sub_field('how_it_works_button_label');
                        ?>
                        <article class="how-it-works--card card-reveal">
                            <div class="how-it-works--card-content">
                                <div class="card-number"><?php echo esc_html($card_num); ?></div>
                                    <h3><?php echo esc_html($title); ?></h3>
                                <?php echo apply_filters('the_content', $description); ?>
                            </div>
                            <?php if ($content && $buttonLabel): ?>
                                <button class="btn-more-info" data-title="<?php echo esc_attr($title); ?>">
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