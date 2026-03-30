<?php
/**
 * Template Name: Galería con filtros por categorías
 * 
 * Plantilla de inicio con filtros AJAX por categoría y toggle NSFW.
 */

get_header(); ?>

<main id="main" class="site-main homepage-wrapper" role="main">
    <!-- SECCIÓN DE FILTROS -->
    <?php gallery_homepage_breadcrumbs(); ?>

    <!-- GRID DE RESULTADOS -->
    <section class="block posts--body">
        <div class="content">
            <div id="ajax-posts-container">
                <?php
                // 1. Mostrar artículo destacado desde opciones si existe
                $f_img_url = get_option('avante_home_featured_image');

                if (!empty($f_img_url)) : 
                    // Calcular Aspect Ratio con la misma lógica del loop
                    $ratio = 1; // Default cuadrado
                    $width = 300;
                    $height = 300;

                    $f_img_id = attachment_url_to_postid($f_img_url);

                    if ($f_img_id) {
                        $img_data = wp_get_attachment_image_src($f_img_id, 'medium_large');
                        if ($img_data) {
                            $width = $img_data[1];
                            $height = $img_data[2];
                            // Evitar división por cero
                            if ($height > 0) {
                                $ratio = $width / $height;
                            }
                        }
                    }
                    ?>
                    <div class="ajax-item-wrapper featured-home-item" 
                        style="flex-grow: <?php echo esc_attr($ratio * 100); ?>; flex-basis: calc( var(--row-height, 250px) * <?php echo esc_attr($ratio); ?> );" 
                        data-ratio="<?php echo esc_attr($ratio); ?>"
                        data-year="<?php echo date('Y'); ?>">
                        
                        <article class="justified-post" style="padding-bottom: <?php echo esc_attr((1 / $ratio) * 100); ?>%;">
                            <?php if ($f_img_id) : ?>
                                <?php echo wp_get_attachment_image($f_img_id, 'medium_large', false, [
                                    'class' => 'post-thumbnail'
                                ]); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url($f_img_url); ?>" class="post-thumbnail" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endif; ?>

                <?php
                // Query Inicial (Solo Posts normales, Status publish)
                // Reproducimos el estado inicial "Todos + No NSFW"
                $initial_args = [
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => 24, // Forzamos 12 posts
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'tax_query'      => [
                        [
                            'taxonomy' => 'post_format',
                            'field'    => 'slug',
                            'terms'    => ['post-format-image', 'post-format-gallery'],
                            'operator' => 'IN'
                        ]
                    ]
                ];
                $initial_query = new WP_Query($initial_args);

                if ($initial_query->have_posts()) :
                    while ($initial_query->have_posts()) :
                        $initial_query->the_post();
                        // Importante: El wrapper ahora está dentro del template part
                        $loop_design = get_option('avante_loop_design', 'loop');
                        get_template_part('template-parts/' . $loop_design . '/content', 'ajax');
                    endwhile;
                else :
                    echo '<div class="no-results">' . esc_html__('No hay contenido para mostrar.', 'avante') . '</div>';
                endif;
                
                // Guardar max_pages para pasarlo al botón inicial
                $max_pages = $initial_query->max_num_pages;
                wp_reset_postdata();
                ?>
            </div>

            <!-- Botón de Paginación -->
            <div class="pagination-wrapper" style="text-align: center; margin-top: 2rem;">
                <button id="load-more-btn" class="btn" style="margin-inline: auto;" 
                    data-page="1" 
                    data-max-pages="<?php echo esc_attr($max_pages); ?>"
                    style="<?php echo ($max_pages <= 1) ? 'display: none;' : ''; ?>">
                    <span class="btn-text"><?php esc_html_e('Cargar más', 'avante'); ?></span>
                    <span class="btn-loader spinner" style="display: none;"></span>
                </button>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>