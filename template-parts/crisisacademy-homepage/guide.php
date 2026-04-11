<section id="guide" class="block">
    <div class="content">
        <div class="container glass-border-bright">
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                    if (have_rows('crisis_guide')):
                        $count = 0;
                        while (have_rows('crisis_guide')):
                            the_row(); 
                            $image = get_sub_field('crisis_guide_image');
                            $count++;
                            ?>

                            <article id="guide-item-<?= $count; ?>" class="guide-item post">
                                <div class="guide-content">
                                    <?php
                                    if ($image) {
                                        $img_id = is_array($image) ? $image['ID'] : $image;
                                        echo wp_get_attachment_image($img_id, 'full', false, ['loading' => 'lazy']);
                                    }
                                    ?>
                                </div>
                            </article>

                        <?php endwhile;
                    else:
                        echo '<p>No se encontraron guías.</p>';
                    endif;
                    ?>
                </div>
            </div>
            <?php if (have_rows('crisis_guide')): ?>
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
        <div class="guide-content">
            <span class="span-tag">Visualización</span>
            <h2>Así se ve tu Guía de Crisis</h2>
            <p>En The Crisis Academy respaldamos tu preparación mediante servicios especializados de certificaciones, auditorías y creación de manuales a la medida.</p>
            <p>Porque cada empresa tiene riesgos únicos. Estos son los elementos reales que estructurarán tu respuesta. ¿Estás preparado?</p>
        </div>
    </div>
</section>