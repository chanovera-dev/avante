<?php
/**
 * Template part for displaying audio format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avante
 * @since Avante 1.0.0
 */
$tags = get_the_tags();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body">
        <?php
        if (!$tags) {
            ?>
            <div class="post__header">
                <?php
                $post_id = get_the_ID();
                $likes_count = avante_get_likes_count($post_id);
                $has_liked = avante_user_has_liked($post_id);
                echo '<a href="' . esc_url(get_post_format_link('audio')) . '" class="format-post-tag" aria-label="' . esc_attr__('Ver todas las noticias en formato audio', 'avante') . '">' . avante_get_icon('audio') . esc_html(__('Audio', 'avante')) . '</a>';
                ?>
                <?php echo avante_render_like_button(); ?>
            </div>
            <?php
        }
        ?>
        <div class="post__content glass-border-bright">
            <?php
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail(null, 'loop-thumbnail', ['alt' => get_the_title(), 'loading' => 'lazy']);
            }
            ?>
            <div class="post__backdrop"></div>
            <div class="post__overlay"></div>
            <?php the_title('<h2 class="post--title">', '</h2>'); ?>
            <div class="post-audio-wrapper">
                <?php
                // =========================================
                // GET FIRST AUDIO WITHOUT BREAKING THE LOOP
                // =========================================
                $post_obj = get_post();
                $content = $post_obj->post_content;
                $first_audio_html = '';

                // 1) HTML5 audio
                if (!$first_audio_html && preg_match('/<audio.*?<\/audio>/is', $content, $match)) {
                    $first_audio_html = $match[0];
                }

                // 2) [audio] shortcode
                if (!$first_audio_html && has_shortcode($content, 'audio')) {
                    $first_audio_html = do_shortcode('[audio]');
                }

                // 3) Gutenberg blocks
                if (!$first_audio_html) {
                    $blocks = parse_blocks($content);
                    foreach ($blocks as $block) {
                        if ($block['blockName'] === 'core/audio' && !empty($block['attrs']['src'])) {
                            $first_audio_html = '<audio controls src="' . esc_url($block['attrs']['src']) . '"></audio>';
                            break;
                        }
                        if ($block['blockName'] === 'core/embed' && !empty($block['attrs']['url'])) {
                            // Audio embeds (Spotify, Soundcloud)
                            if (strpos($block['attrs']['url'], 'spotify.com') !== false || strpos($block['attrs']['url'], 'soundcloud.com') !== false) {
                                $first_audio_html = wp_oembed_get($block['attrs']['url']);
                                break;
                            }
                        }
                    }
                }

                if ($first_audio_html) {
                    echo $first_audio_html;
                }
                ?>
            </div>
        </div>
        <?php
        
        if ($tags) {
            ?>
            <div class="post_footer">
            <div class="format-type">
                <?php echo '<a href="' . esc_url(get_post_format_link('audio')) . '" class="format-post-tag" aria-label="' . esc_attr__('Ver todas las noticias en formato audio', 'avante') . '">' . avante_get_icon('audio') . esc_html__('Audio', 'avante') . '</a>'; ?>
            </div>
            <div class="post--tags__wrapper">
                <div class="post--tags">
                    <?php
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="post-tag small">' . avante_get_icon('tag') . esc_html($tag->name) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php echo avante_render_like_button(); ?>
        </div>
        <?php
        }
        ?>
    </div>
</article>