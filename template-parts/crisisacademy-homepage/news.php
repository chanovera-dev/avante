<?php
/**
 * Template part for the Crisis Academy News section.
 * Generates a newspaper-like grid fetched from 'news' Custom Post Type.
 *
 * @package Avante
 */

$args = [
    'post_type'      => 'news',
    'posts_per_page' => 13,
    'post_status'    => 'publish',
    'tax_query'      => [
        [
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => ['post-format-video'],
            'operator' => 'NOT IN',
        ]
    ]
];

$news_query = new WP_Query($args);
$news_posts = $news_query->posts;

// Safely release query back data
wp_reset_postdata();

/**
 * Helper to safely shift an item from array for display.
 */
function ca_get_next_news_item(&$posts) {
    return !empty($posts) ? array_shift($posts) : null;
}

/**
 * Fetches Open Graph metadata from a URL and caches it.
 */
function ca_fetch_url_metadata($url) {
    $transient_key = 'ca_og_meta_' . md5($url);
    $meta = get_transient($transient_key);
    if (false !== $meta) {
        return $meta;
    }

    $meta = [
        'title' => '',
        'image' => '',
        'author' => '',
        'date' => '',
        'excerpt' => '',
        'site_name' => ''
    ];

    $response = wp_remote_get($url, ['timeout' => 5]);
    if (is_wp_error($response)) {
        set_transient($transient_key, $meta, HOUR_IN_SECONDS);
        return $meta;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code >= 400) {
        set_transient($transient_key, $meta, HOUR_IN_SECONDS);
        return $meta;
    }

    $html = wp_remote_retrieve_body($response);
    if (!$html) {
        set_transient($transient_key, $meta, HOUR_IN_SECONDS);
        return $meta;
    }

    if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*property=["\']og:title["\'][^>]*>/i', $html, $matches)) {
        $meta['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    } elseif (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
        $meta['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*property=["\']og:image["\'][^>]*>/i', $html, $matches)) {
        $meta['image'] = $matches[1];
    }

    if (preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*property=["\']og:description["\'][^>]*>/i', $html, $matches)) {
        $meta['excerpt'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    } elseif (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*name=["\']description["\'][^>]*>/i', $html, $matches)) {
        $meta['excerpt'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('/<meta[^>]*name=["\']author["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*name=["\']author["\'][^>]*>/i', $html, $matches)) {
        $meta['author'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    } elseif (preg_match('/<meta[^>]*property=["\']article:author["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
        $meta['author'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('/<meta[^>]*property=["\']og:site_name["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches) || preg_match('/<meta[^>]*content=["\']([^"\']+)["\'][^>]*property=["\']og:site_name["\'][^>]*>/i', $html, $matches)) {
        $meta['site_name'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('/<meta[^>]*property=["\']article:published_time["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
        $meta['date'] = strtotime($matches[1]);
    }

    set_transient($transient_key, $meta, 12 * HOUR_IN_SECONDS);
    return $meta;
}

/**
 * Gets normalized post data, handling link post formats.
 */
function ca_get_news_data($post) {
    $data = [
        'title' => get_the_title($post),
        'url' => get_permalink($post),
        'excerpt' => get_the_excerpt($post),
        'author' => get_the_author_meta('display_name', $post->post_author),
        'time' => get_the_time('U', $post),
        'image' => get_the_post_thumbnail_url($post, 'large'),
        'image_medium' => get_the_post_thumbnail_url($post, 'medium'),
        'image_thumb' => get_the_post_thumbnail_url($post, 'thumbnail'),
    ];

    if (has_post_format('link', $post)) {
        $content = wp_strip_all_tags($post->post_content);
        $url = preg_match('/https?:\/\/[^\s"\'<>]+/', $content, $matches) ? $matches[0] : '';
        if ($url) {
            $data['url'] = $url;
            $og_meta = ca_fetch_url_metadata($url);
            if (!empty($og_meta['title'])) $data['title'] = $og_meta['title'];
            if (!empty($og_meta['excerpt'])) $data['excerpt'] = $og_meta['excerpt'];
            
            if (!empty($og_meta['author'])) {
                $data['author'] = $og_meta['author'];
            } elseif (!empty($og_meta['site_name'])) {
                $data['author'] = $og_meta['site_name'];
            } else {
                $host = parse_url($url, PHP_URL_HOST);
                if ($host) {
                    $host = preg_replace('/^www\./', '', $host);
                    $data['author'] = ucfirst($host);
                }
            }

            if (!empty($og_meta['date'])) $data['time'] = $og_meta['date'];
            if (!empty($og_meta['image'])) {
                $data['image'] = $og_meta['image'];
                $data['image_medium'] = $og_meta['image'];
                $data['image_thumb'] = $og_meta['image'];
            }
        }
    }
    return $data;
}

/**
 * Fetches latest videos from a YouTube RSS feed, with transient caching.
 */
function ca_get_youtube_feed($channel_id, $count = 2) {
    $transient_key = 'ca_yt_feed_limit_' . $count . '_' . md5($channel_id);
    $cached = get_transient($transient_key);
    if ($cached !== false) return $cached;

    $feed_url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . urlencode($channel_id);
    $response = wp_remote_get($feed_url, ['timeout' => 10]);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    
    // Suppress warnings just in case simplexml_load_string encounters broken XML
    $xml = @simplexml_load_string($body);
    if (!$xml) return [];

    $videos = [];
    $i = 0;
    
    // The entries list typically holds the videos
    if (isset($xml->entry)) {
        foreach ($xml->entry as $entry) {
            if ($i >= $count) break;
            
            $ns_media = $entry->children('http://search.yahoo.com/mrss/');
            $ns_yt    = $entry->children('http://www.youtube.com/xml/schemas/2015');
            
            $videos[] = [
                'title' => (string)$entry->title,
                'link'  => isset($entry->link['href']) ? (string)$entry->link['href'] : '',
                'id'    => isset($ns_yt->videoId) ? (string)$ns_yt->videoId : '',
                'thumb' => isset($ns_media->group->thumbnail) ? (string)$ns_media->group->thumbnail->attributes()->url : ''
            ];
            $i++;
        }
    }

    set_transient($transient_key, $videos, 6 * HOUR_IN_SECONDS);
    return $videos;
}

// Hand distribute retrieved posts to defined slots
$left_col_items   = [ca_get_next_news_item($news_posts), ca_get_next_news_item($news_posts), ca_get_next_news_item($news_posts), ca_get_next_news_item($news_posts)];
$featured_post    = ca_get_next_news_item($news_posts);
$middle_sub_items = [ca_get_next_news_item($news_posts), ca_get_next_news_item($news_posts)];
$trending_items   = array_splice($news_posts, 0, 6);

// Fetch YouTube feed for the sidebar tab (Carolina Eslava Media Trainer)
$yt_videos = ca_get_youtube_feed('UCBi2aE8Fp0jsPthP0xo1crQ', 2);

// Fetch a specific video news post for the media block
$video_query = new WP_Query([
    'post_type'      => 'news',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'tax_query'      => [
        [
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => 'post-format-video'
        ]
    ]
]);
$right_bottom = !empty($video_query->posts) ? $video_query->posts[0] : null;
wp_reset_postdata();

?>

<section id="ca-news-section" class="block">
    <div class="content">
        <div class="news-grid">

            <!-- COLUMNA IZQUIERDA -->
            <aside class="news-col left-col">
                <?php foreach (array_filter($left_col_items) as $item) : 
                    setup_postdata($GLOBALS['post'] =& $item);
                    $ndata = ca_get_news_data($item);
                ?>
                    <article class="news-item vertical-list-item">
                        <h3 class="news-title"><a href="<?php echo esc_url($ndata['url']); ?>"><?php echo esc_html(!empty($ndata['title']) ? $ndata['title'] : get_the_title()); ?></a></h3>
                        <div class="news-excerpt">
                            <?php echo wp_trim_words($ndata['excerpt'], 25, '...'); ?>
                        </div>
                        <footer class="news-meta">
                            <span class="author">Por <?php echo esc_html($ndata['author']); ?></span>
                            <span class="time">Hace <?php echo human_time_diff($ndata['time'], current_time('timestamp')); ?></span>
                        </footer>
                    </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </aside>

            <!-- COLUMNA CENTRAL -->
            <main class="news-col center-col">
                
                <!-- Noticia Destacada (Main Story) -->
                <?php if ($featured_post) : 
                    setup_postdata($GLOBALS['post'] =& $featured_post); 
                    $ndata = ca_get_news_data($featured_post);
                ?>
                <article class="main-story">
                    <div class="thumbnail-wrapper">
                        <?php if ($ndata['image']) : ?>
                            <img src="<?php echo esc_url($ndata['image']); ?>" alt="" class="main-feat-img">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/800x450" alt="Placeholder" class="main-feat-img">
                        <?php endif; ?>
                    </div>
                    <h2 class="main-story-title"><a href="<?php echo esc_url($ndata['url']); ?>"><?php echo esc_html(!empty($ndata['title']) ? $ndata['title'] : get_the_title()); ?></a></h2>
                    <div class="main-story-excerpt">
                         <?php echo wp_trim_words($ndata['excerpt'], 35, '...'); ?>
                    </div>
                    <footer class="news-meta">
                        <span class="author">Por <span class="highlight"><?php echo esc_html($ndata['author']); ?></span></span>
                        <span class="time">Hace <?php echo human_time_diff($ndata['time'], current_time('timestamp')); ?></span>
                    </footer>
                </article>
                <?php wp_reset_postdata(); endif; ?>

                <!-- Bloques inferiores bajo la principal -->
                <div class="middle-sub-row">
                    <?php foreach (array_filter($middle_sub_items) as $item) : 
                        setup_postdata($GLOBALS['post'] =& $item);
                        $ndata = ca_get_news_data($item);
                    ?>
                    <article class="horiz-article">
                        <div class="article-thumb">
                            <?php if ($ndata['image_thumb']) : ?>
                                <img src="<?php echo esc_url($ndata['image_thumb']); ?>" alt="">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/180x120" alt="">
                            <?php endif; ?>
                        </div>
                        <div class="article-body">
                            <h3 class="h-title"><a href="<?php echo esc_url($ndata['url']); ?>"><?php echo esc_html(!empty($ndata['title']) ? $ndata['title'] : get_the_title()); ?></a></h3>
                            <div class="h-excerpt">
                                <?php echo wp_trim_words($ndata['excerpt'], 18, '...'); ?>
                            </div>
                            <footer class="news-meta">
                                <span class="author">Por <span class="highlight"><?php echo esc_html($ndata['author']); ?></span></span>
                                <span class="time">Hace <?php echo human_time_diff($ndata['time'], current_time('timestamp')); ?></span>
                            </footer>
                        </div>
                    </article>
                    <?php endforeach; wp_reset_postdata(); ?>
                </div>
            </main>

            <!-- COLUMNA DERECHA -->
            <aside class="news-col right-col">
                
                <!-- Pestañas de tendencias -->
                <div class="trending-container" id="ca-news-tabs-wrapper">
                    <nav class="trending-tabs">
                        <a href="#" class="tab active" data-tab="trend">TRENDING TOPIC</a>
                        <a href="#" class="tab" data-tab="youtube">YOUTUBE</a>
                    </nav>
                    
                    <ul class="trending-feed active" id="tab-view-trend">
                        <?php $rank = 1; foreach (array_filter($trending_items) as $item) : 
                            setup_postdata($GLOBALS['post'] =& $item);
                            $ndata = ca_get_news_data($item);
                        ?>
                        <li class="trend-entry">
                            <div class="trend-rank">#<?php echo $rank++; ?></div>
                            <div class="trend-content">
                                <h4><a href="<?php echo esc_url($ndata['url']); ?>"><?php echo esc_html(!empty($ndata['title']) ? $ndata['title'] : get_the_title()); ?></a></h4>
                            </div>
                        </li>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>

                    <div class="trending-feed" id="tab-view-youtube">
                        <?php if (!empty($yt_videos)) : foreach ($yt_videos as $vid) : ?>
                        <div class="youtube-visual-entry" style="margin-bottom: 1.5rem;">
                            <article class="glass-post format-video news" style="opacity: 1; transform: none; position: relative;">
                                <div class="post_body glass-border-bright" style="min-height: 200px;">
                                    <div class="post__backdrop"></div>
                                    <div class="post__overlay"></div>
                                    
                                    <div class="post__header">
                                        <a href="<?php echo esc_url($vid['link']); ?>" target="_blank" class="format-post-tag">
                                            <?php echo avante_get_icon('video'); ?> YouTube
                                        </a>
                                    </div>
                                    
                                    <div class="post__content">
                                        <a href="<?php echo esc_url($vid['link']); ?>" class="post__permalink" target="_blank">
                                            <h2 class="post__title"><?php echo esc_html($vid['title']); ?></h2>
                                        </a>
                                    </div>

                                    <div class="post-video-wrapper">
                                        <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($vid['id']); ?>" 
                                                loading="lazy" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; else: ?>
                            <div class="trend-entry"><div class="trend-content"><p><?php _e('No hay videos disponibles en este momento.', 'avante'); ?></p></div></div>
                        <?php endif; ?>
                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const wrapper = document.getElementById('ca-news-tabs-wrapper');
                        if (!wrapper) return;
                        const tabs = wrapper.querySelectorAll('.tab');
                        const feeds = wrapper.querySelectorAll('.trending-feed');
                        tabs.forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                tabs.forEach(t => t.classList.remove('active'));
                                feeds.forEach(f => f.classList.remove('active'));
                                this.classList.add('active');
                                const target = document.getElementById('tab-view-' + this.getAttribute('data-tab'));
                                if(target) target.classList.add('active');
                            });
                        });
                    });
                    </script>
                </div>

                <!-- Bloque multimedia inferior -->
                <?php if ($right_bottom) : 
                    setup_postdata($GLOBALS['post'] =& $right_bottom); 
                    get_template_part('template-parts/loop01/content', 'video');
                wp_reset_postdata(); endif; ?>

            </aside>
        </div>
    </div>
</section>
