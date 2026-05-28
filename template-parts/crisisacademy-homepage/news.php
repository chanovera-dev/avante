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
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'no_found_rows'  => true, // Evita la paginación para optimizar la consulta
];

$wp_query = new WP_Query($args);
?>

<section id="news" class="block posts--body">
    <div class="content">
        
        <header class="section-header">
            <span class="span-pretext pretext-reveal">Centro de Inteligencia</span>
            <h2 class="title-section title-reveal">Actualidad Global y Análisis</h2>
            <p class="description-section object-reveal">Mantente informado de los últimos eventos, análisis e impacto de crisis y escándalos.</p>
        </header>

        <?php
        global $wpdb;
        
        // Find which post formats are currently used by published 'news' posts (cached using transients)
        $used_formats_slugs = get_transient('crisisacademy_used_formats_slugs');
        if ( false === $used_formats_slugs ) {
            $used_formats_slugs = $wpdb->get_col("
                SELECT DISTINCT t.slug 
                FROM {$wpdb->terms} t 
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id 
                INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID 
                WHERE p.post_type = 'news' AND p.post_status = 'publish' AND tt.taxonomy = 'post_format'
            ");
            set_transient('crisisacademy_used_formats_slugs', $used_formats_slugs, HOUR_IN_SECONDS);
        }
        
        // Check if there are standard 'news' posts without any specific post_format term (cached using transients)
        $has_standard = get_transient('crisisacademy_has_standard_news');
        if ( false === $has_standard ) {
            $has_standard = $wpdb->get_var("
                SELECT p.ID 
                FROM {$wpdb->posts} p 
                WHERE p.post_type = 'news' AND p.post_status = 'publish'
                AND NOT EXISTS (
                    SELECT 1 FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tr.object_id = p.ID AND tt.taxonomy = 'post_format'
                )
                LIMIT 1
            ");
            set_transient('crisisacademy_has_standard_news', $has_standard, HOUR_IN_SECONDS);
        }
        
        $format_strings = get_post_format_strings();
        ?>
        <div class="news-filters">
            <button class="news-filter-btn active" data-format="all"><?= esc_html__('Todos', 'avante'); ?></button>
            
            <?php if ($has_standard) : ?>
                <button class="news-filter-btn" data-format="standard"><?= esc_html__('Artículos', 'avante'); ?></button>
            <?php endif; ?>
            
            <?php foreach ($used_formats_slugs as $slug) : 
                $format = str_replace('post-format-', '', $slug);
                $label = isset($format_strings[$format]) ? $format_strings[$format] : ucfirst($format);
            ?>
                <button class="news-filter-btn" data-format="<?= esc_attr($format); ?>"><?= esc_html($label); ?></button>
            <?php endforeach; ?>
        </div>

        <div id="news-results" class="news-results-container">
            <?php
            // Llama al template part de loop del tema
            get_template_part('templates/archive/wp', 'loop');
            ?>
        </div>
    </div>
</section>

<?php
// Restaurar la consulta global original y reiniciar datos del post
$wp_query = $original_query;
wp_reset_postdata();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.news-filter-btn');
    const resultsContainer = document.getElementById('news-results');

    if (!filterBtns.length || !resultsContainer) return;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const format = this.getAttribute('data-format');
            
            // Show loading state
            resultsContainer.style.opacity = '0.5';
            
            const formData = new FormData();
            formData.append('action', 'filter_news_posts');
            formData.append('format', format);
            
            // Ensure avante_likes_obj.ajax_url exists, if not use a fallback
            const ajaxUrl = (typeof avante_likes_obj !== 'undefined' && avante_likes_obj.ajax_url) 
                ? avante_likes_obj.ajax_url 
                : '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultsContainer.innerHTML = data.data.html;
                }
            })
            .catch(error => {
                console.error('Error filtering news:', error);
            })
            .finally(() => {
                resultsContainer.style.opacity = '1';
                
                // Re-trigger layout/animations if there are any specific to your theme
                if (typeof window.reinitializeAnimations === 'function') {
                    window.reinitializeAnimations();
                }
                
                // Re-trigger intersection observer animations for newly loaded items
                if (typeof window.animateIn === 'function') {
                    window.animateIn('.post, .nsfw, .detras-del-espejo, .participants, .ajax-item-wrapper, .quotes-heading, .type-news, .page');
                }
            });
        });
    });
});
</script>