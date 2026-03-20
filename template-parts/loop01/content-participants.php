<?php
/**
 * Template part for displaying participant posts
 *
 * @package Avante
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body glass-border-bright">
        <div class="post__backdrop"></div>
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            $global_excluded = get_option('storytelling_global_excluded_metrics', array());
            if (!is_array($global_excluded)) $global_excluded = array();
            $label_to_db_key = array('Lenguaje no verbal'=>'m_lenguaje_no_verbal','Dirige la entrevista'=>'m_dirige_entrevista','Mensajes memorables'=>'m_mensajes','Preguntas incisivas'=>'m_preguntas_incisivas','Frases citables'=>'m_frases_citables','Usa datos, cifras'=>'m_usa_datos','Valores e historias'=>'m_habla_valores');
            $label_to_acf_key = array('Lenguaje no verbal'=>'no_verbal_language','Dirige la entrevista'=>'manage_interview','Mensajes memorables'=>'memorable_messages','Preguntas incisivas'=>'incisive_questions','Frases citables'=>'soundbites_messages','Usa datos, cifras'=>'show_data','Valores e historias'=>'show_storytelling');
            $total_score = 0;
            $valid_count = 0;
            foreach ( $label_to_acf_key as $label => $acf_key ) {
                $db_key = $label_to_db_key[ $label ];
                if ( in_array( $db_key, $global_excluded ) ) continue;
                $local_excluded = json_decode(get_post_meta($post_id, 'excluded_metrics', true), true);
                if ( is_array($local_excluded) && in_array($db_key, $local_excluded) ) continue;
                $val = get_field( $acf_key, $post_id );
                if (empty($val) || $val === 'No hay datos') continue;
                $score = 0;
                if ( $val === 'Buen vocero/a' ) $score = 2.5;
                elseif ( $val === 'Experto/a' ) $score = 5;
                elseif ( $val === 'Manejo insuficiente' ) $score = 1;
                $total_score += $score;
                $valid_count++;
            }
            $average = $valid_count > 0 ? round($total_score / $valid_count, 1) : 0;
            $average_text = $valid_count > 0 ? number_format($average, 1) : 'S/D';

            echo '<div class="format-post-tag" title="Promedio de competencias" style="opacity:1;">' . avante_get_icon('star') . esc_html('Promedio vocería:') .'<span>' . esc_html($average_text) . '</span></div>';
            ?>
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post--content">
            <div class="post--author participant">
                <?php
                $avatar_id = get_field('avatar');
                    if ( $avatar_id ) {
                        // Output the image safely letting WordPress handle attributes
                        echo wp_get_attachment_image( $avatar_id, 'thumbnail', false, array( 'alt' => get_the_title(), 'class' => 'avatar' ) );
                    }
                echo '<a href="' . get_permalink() . '" class="post_permalink"><h3 class="author-name">';
                the_title();
                echo '</h3></a><span class="author-description">';
                if ( $position = get_field('position') ) : 
                    echo esc_html($position);
                endif;
                echo ' | ';
                if ( $company = get_field('company_name') ) : 
                    echo esc_html($company);
                endif;
                echo '</span>';
                ?>
            </div>
            <?php 
            $pr = get_field('personal_ranking');
            $ir = get_field('institutional_ranking');
            if ( $pr || $ir ) : 
            ?>
            <div class="participant__rankings">
                <?php if ( $pr ) : ?>
                    <div title="Ranking Personal"><strong>Ranking Personal:</strong> <?php echo esc_html($pr); ?></div>
                <?php endif; ?>
                <?php if ( $ir ) : ?>
                    <div title="Ranking Institucional"><strong>Ranking Institucional:</strong> <?php echo esc_html($ir); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if ( $observations = get_field('observations') ) : ?>
                <div class="participant__others">
                   <?php echo wp_kses_post( $observations ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        if (has_post_thumbnail()) {
            echo get_the_post_thumbnail(null, 'loop-thumbnail', ['alt' => get_the_title(), 'loading' => 'lazy']);
        }
        ?>
    </div>
</article>
