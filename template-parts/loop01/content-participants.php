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
            $company = get_field('company');
            $tag_text = $company ? $company : __('Participante', 'avante');
            echo '<a href="' . esc_url(get_post_type_archive_link('participants')) . '" class="format-post-tag">' . esc_html($tag_text) . '</a>';
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
                echo '<a href="' . get_permalink() . '"><h3 class="author-name">';
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
            <?php if ( $others = get_field('others') ) : ?>
                <div class="participant__others">
                   <?php echo wp_kses_post( $others ); ?>
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
