<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */
?>
<div class="loop">
    <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                $loop_design = get_option('avante_loop_design', 'loop');
                $post_format = get_post_format();
                $part = 'archive';

                if ( $post_format ) {
                    if ( locate_template( "template-parts/{$loop_design}/content-{$post_format}.php" ) ) {
                        $part = $post_format;
                    }
                }
                
                if ( get_post_type() === 'participants' ) {
                    $part = 'participants';
                }

                get_template_part( "template-parts/{$loop_design}/content", $part );
            }

            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => avante_get_icon('backward') . esc_html__('Anterior', 'avante'),
                'next_text' => esc_html__('Siguiente', 'avante') . avante_get_icon('forward'),
            ) );
        } else {
            echo '<p>' . esc_html__( 'No se han encontrado artículos', 'avante' ) . '</p>';
        }
    ?>
</div>