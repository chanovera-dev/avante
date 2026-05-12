<?php
/**
 * Template part for the Crisis Academy News section.
 */

// Guardar la consulta global original para restaurarla después
global $wp_query;
$original_query = $wp_query;

// Crear una nueva WP_Query para obtener los últimos 6 CPTs 'news'
$args = [
    'post_type'      => 'news',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'no_found_rows'  => true, // Evita la paginación para optimizar la consulta
];

$wp_query = new WP_Query($args);
?>

<section id="news" class="block posts--body">
    <div class="content">
        <?php
        // Llama al template part de loop del tema
        get_template_part('templates/archive/wp', 'loop');
        ?>
    </div>
</section>

<?php
// Restaurar la consulta global original y reiniciar datos del post
$wp_query = $original_query;
wp_reset_postdata();
?>