<?php
/**
 * Properties List Template Part
 *
 * This template part displays the loop of property posts for the archive page.
 * It includes support for AJAX pagination and handles the display of property cards.
 *
 * @package Avante
 * @since Avante 1.0.0
 */
?>
<div class="loop properties--list">
    <?php
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

        $args = array(
            'post_type'      => 'property',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
            'paged'          => $paged,
        );

        $query = new WP_Query($args);

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $loop_design = get_option('avante_loop_design', 'loop');
                get_template_part('template-parts/' . $loop_design . '/content', 'property');
            }

            // Output pagination — IMPORTANT: use $paged here
            echo '<nav class="navigation pagination" aria-label="Posts pagination">';
            echo '<h2 class="screen-reader-text">Posts pagination</h2>';
            echo '<div class="nav-links">';
            echo paginate_links(array(
                'total'   => $query->max_num_pages,
                'current' => $paged, // <-- FIX: use $paged (value from AJAX)
                'format'  => '?paged=%#%',
                'prev_text' => avante_get_icon('backward') . esc_html__('Anterior', 'avante'),
                'next_text' => esc_html__('Siguiente', 'avante') . avante_get_icon('forward'),
            ));
            echo '</div></nav>';
        } else {
            echo '<p>No se encontraron propiedades.</p>';
        }

        wp_reset_postdata();
    ?>
</div>