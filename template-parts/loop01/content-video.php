<?php
/**
 * Template part for displaying video format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
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
            echo '<a href="' . esc_url(get_post_format_link('video')) . '" class="format-post-tag">' . avante_get_icon('video') . esc_html(__('Video', 'avante')) . '</a>';
            ?>
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post__content">
            <?php get_template_part('templates/single/tags'); ?>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= get_the_date('F j, Y'); ?></p>
            </div>
            <a href="<?= get_the_permalink(); ?>" class="post__permalink">
                <?php the_title('<h2 class="post__title">', '</h2>'); ?>
            </a>
            <?php get_template_part('templates/single/author'); ?>
        </div>
        <?php
        // =========================================
        // GET FIRST VIDEO WITHOUT BREAKING THE LOOP
        // =========================================
        
        $post_obj = get_post();
        $content = $post_obj->post_content;

        $first_video_html = ''; // ← stores the video
        
        // 1) iframe
        if (!$first_video_html && preg_match('/<iframe.*?<\/iframe>/is', $content, $match)) {
            $first_video_html = '<div class="post-video-wrapper">' . $match[0] . '</div>';
        }

        // 2) HTML5 video
        if (!$first_video_html && preg_match('/<video.*?<\/video>/is', $content, $match)) {
            $first_video_html = '<div class="post-video-wrapper">' . $match[0] . '</div>';
        }

        // 3) [video] shortcode
        if (!$first_video_html && has_shortcode($content, 'video')) {
            $first_video_html = do_shortcode('[video]');
        }

        // 4) Gutenberg blocks
        if (!$first_video_html) {
            $blocks = parse_blocks($content);
            foreach ($blocks as $block) {
                if ($block['blockName'] === 'core/video' && !empty($block['attrs']['src'])) {
                    $first_video_html = '<div class="post-video-wrapper"><video controls src="' . esc_url($block['attrs']['src']) . '"></video></div>';
                    break;
                }
                if ($block['blockName'] === 'core/embed' && !empty($block['attrs']['url'])) {
                    $embed = wp_oembed_get($block['attrs']['url']);
                    if ($embed) {
                        $first_video_html = '<div class="post-video-wrapper">' . $embed . '</div>';
                    }
                    break;
                }
            }
        }

        // 5) [embed] shortcode
        if (!$first_video_html && preg_match('/(?:\[embed[^\]]*\])(.*?)(?:\[\/embed\])/is', $content, $match)) {
            $embed = wp_oembed_get(trim(strip_tags($match[1])));
            if ($embed) {
                $first_video_html = '<div class="post-video-wrapper">' . $embed . '</div>';
            }
        }

        // 6) Plain YouTube/Vimeo URLs (oEmbed fallback)
        if (!$first_video_html && preg_match('/(?:https?:\/\/(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)[a-zA-Z0-9_-]{11}|https?:\/\/(?:www\.)?vimeo\.com\/\d+)/i', $content, $match)) {
            $embed = wp_oembed_get($match[0]);
            if ($embed) {
                $first_video_html = '<div class="post-video-wrapper">' . $embed . '</div>';
            }
        }

        // --------------------------------------------
        // PRINT THE VIDEO (only if it exists)
        // --------------------------------------------
        if ($first_video_html) {
            // Inject native lazy loading into any iframes to prevent heavy scripts from loading early
            $first_video_html = str_replace('<iframe', '<iframe loading="lazy"', $first_video_html);
            echo $first_video_html;
        }
        ?>
    </div>
</article>