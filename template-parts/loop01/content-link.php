<?php
/**
 * Template part for displaying link format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package avante
 * @since 2.0.0
 */

$content = get_the_content();
preg_match('/https?:\/\/[^\s"]+/', $content, $matches);
$url = $matches[0] ?? '';

$title = get_the_title(); // Default title
$image = '';
$date = ''; // New variable for external date
$site_name = ''; // External site name
$author_name = ''; // External author name
$author_avatar = ''; // External author avatar URL
$external_tags = array(); // External tags array
$date_source = 'None'; // Source diagnostic
$raw_date = ''; // Raw date string captured
$http_status = ''; // HTTP diagnostic

if ($url && wp_http_validate_url($url)) {

    // Try to retrieve data from cache
    $transient_key = 'avante_link_preview_' . md5($url);
    $cached_data = get_transient($transient_key);

    if (false !== $cached_data) {
        $title = $cached_data['title'];
        $image = $cached_data['image'];
        $date = $cached_data['date'];
        $site_name = $cached_data['site_name'] ?? '';
        $author_name = $cached_data['author_name'] ?? '';
        $author_avatar = $cached_data['author_avatar'] ?? '';
        $external_tags = $cached_data['external_tags'] ?? array();
        $date_source = 'Cache';
        $raw_date = $cached_data['raw_date'] ?? '';
        $http_status = $cached_data['http_status'] ?? 'Cached';
    } else {
        // Make secure request with WP HTTP API
        $response = wp_remote_get($url, array(
            'timeout' => 5,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'redirection' => 5,
        ));

        $http_status = is_wp_error($response) ? 'Error: ' . $response->get_error_message() : 'Code: ' . wp_remote_retrieve_response_code($response);

        if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
            $html = wp_remote_retrieve_body($response);

            if ($html) {

                /**
                 * ===================================
                 *  RAW TITLE CAPTURE
                 * ===================================
                 */

                $raw_title_tag = '';
                if (preg_match('/<title>(.*?)<\/title>/is', $html, $raw_match)) {
                    $raw_title_tag = html_entity_decode(strip_tags(trim($raw_match[1])));
                }


                /**
                 * ===================================
                 *  EXTERNAL SITE NAME (PRE-EXTRACTION)
                 * ===================================
                 */

                // 1. og:site_name
                if (preg_match('/<meta[^>]+property=["\']og:site_name["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $og_site)) {
                    $site_name = html_entity_decode($og_site[1]);
                }

                // 2. Fetch root domain title (Auth. site name/brand: "title de la página de inicio")
                // Try home fetch if site name is empty OR if it looks like a URL (e.g., carolinaeslava.com)
                if (empty($site_name) || (strpos($site_name, '.') !== false && strpos($site_name, ' ') === false)) {
                    $url_parts = wp_parse_url($url);
                    if (!empty($url_parts['scheme']) && !empty($url_parts['host'])) {
                        $home_url = $url_parts['scheme'] . '://' . $url_parts['host'];
                        $home_response = wp_remote_get($home_url, array('timeout' => 3));
                        
                        if (!is_wp_error($home_response) && 200 === wp_remote_retrieve_response_code($home_response)) {
                            $home_html = wp_remote_retrieve_body($home_response);
                            if (preg_match('/<title>(.*?)<\/title>/is', $home_html, $h_match)) {
                                $h_title = html_entity_decode(strip_tags(trim($h_match[1])));
                                // Correctly strip everything after the first dash or pipe
                                $site_name = trim(preg_replace('/\s*[-–—|].*$/u', '', $h_title));
                            }
                        }
                    }
                }

                // 3. Extract site name from article title suffix as fallback
                if (empty($site_name) && $raw_title_tag && preg_match('/[-–—|]\s*([^|–—-]+)$/u', $raw_title_tag, $title_site)) {
                    $site_name = trim($title_site[1]);
                }


                /**
                 * ===================================
                 *  EXTERNAL TITLE (CLEAN)
                 * ===================================
                 */

                // og:title
                if (preg_match('/<meta property="og:title" content="([^"]+)"/i', $html, $og_title)) {
                    $title = html_entity_decode($og_title[1]);
                }
                // <title>
                elseif ($raw_title_tag) {
                    $title = $raw_title_tag;
                }

                // Dynamic Cleanup: Remove Site Name from Title if it's there
                if (!empty($site_name)) {
                    $quoted_site = preg_quote($site_name, '/');
                    $title = preg_replace('/\s*[-–—|]\s*' . $quoted_site . '$/iu', '', $title);
                }

                // Static Cleanup
                $title = preg_replace('/\s*[-–—|]\s*La Voz de la Región$/i', '', $title);


                /**
                 * ===================================
                 *  EXTERNAL IMAGE
                 * ===================================
                 */

                // 1. og:image (Handles both " and ' and property/content order)
                if (preg_match('/<meta[^>]+(?:property|name)=["\'](?:og:image|twitter:image)["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta_image)) {
                    $image = $meta_image[1];
                }

                // 2. JSON-LD "image" (string or object)
                if (empty($image)) {
                    if (preg_match('/"image"\s*:\s*(?:\{[^}]*"url"\s*:\s*"([^"]+)"|"([^"]+)")/i', $html, $json_image)) {
                        $image = $json_image[1] ?: $json_image[2];
                    }
                }

                // 3. Manually search for images in common content areas
                if (empty($image)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    // Search common WP/Article content containers
                    $content_containers = ['post-body', 'entry-content', 'post-content', 'article-content', 'article_body'];
                    $query_parts = [];
                    foreach ($content_containers as $container) {
                        $query_parts[] = "contains(@class, '$container')";
                    }
                    $image_nodes = $xpath->query("//div[" . implode(' or ', $query_parts) . "]//img");

                    // Fallback to ANY image in the body if specific containers fail
                    if ($image_nodes->length === 0) {
                        $image_nodes = $xpath->query("//body//img");
                    }

                    if ($image_nodes->length > 0) {
                        foreach ($image_nodes as $img) {
                             $src = $img->getAttribute('src') ?: $img->getAttribute('data-src') ?: $img->getAttribute('data-lazy-src') ?: $img->getAttribute('data-original');
                             
                             if ($src && !strpos($src, 'gravatar') && !strpos($src, 'svg')) {
                                 // Convert relative URL to absolute
                                 if (strpos($src, 'http') !== 0) {
                                     $url_parts = wp_parse_url($url);
                                     $base = $url_parts['scheme'] . '://' . $url_parts['host'];
                                     $src = $base . '/' . ltrim($src, '/');
                                 }
                                 
                                 // Simple check to skip very small/icon images (common in sidebars)
                                 $width = $img->getAttribute('width');
                                 if (!$width || $width > 100) {
                                    $image = $src;
                                    break;
                                 }
                             }
                        }
                    }
                    libxml_clear_errors();
                }


                /**
                 * ===================================
                 *  EXTERNAL DATE
                 * ===================================
                 */
                
                $date_source = 'Fallback';

                // 0. URL-based Date (Extremely accurate for WordPress/News URLs)
                if (preg_match('/\/(\d{4})[\/\-](\d{2})[\/\-](\d{2})\//', $url, $url_match)) {
                    $date = $url_match[1] . '-' . $url_match[2] . '-' . $url_match[3];
                }

                if (empty($date)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    // 1. Meta Tags (Prioritized Search)
                    $meta_queries = [
                        "//meta[@property='article:published_time']/@content",
                        "//meta[@name='article:published_time']/@content",
                        "//meta[@property='og:published_time']/@content",
                        "//*[@itemprop='datePublished']/@content",
                        "//meta[@name='date']/@content",
                        "//meta[@name='dcterms.issued']/@content",
                        "//meta[@name='DC.date.issued']/@content",
                        "//meta[@name='citation_date']/@content",
                        "//meta[@name='pubdate']/@content",
                    ];
                    foreach ($meta_queries as $query) {
                        $nodes = $xpath->query($query);
                        foreach ($nodes as $node) {
                            $val = trim($node->nodeValue);
                            if ($val && (strtotime($val) || preg_match('/^\d{4}-\d{2}-\d{2}/', $val))) {
                                $date = $val;
                                break 2;
                            }
                        }
                    }

                    // 2. Focused JSON-LD date extraction (datePublished)
                    if (empty($date)) {
                        $scripts = $doc->getElementsByTagName('script');
                        foreach ($scripts as $script) {
                            if ($script->getAttribute('type') === 'application/ld+json') {
                                if (preg_match('/"(?:datePublished|pubdate)"\s*:\s*"([^"]+)"/i', $script->textContent, $json_match)) {
                                    $date = $json_match[1];
                                    break;
                                }
                            }
                        }
                    }

                    // 3. Body Search (WP Block Themes & News Styles)
                    if (empty($date)) {
                        $date_containers = ['post-body', 'entry-content', 'article', 'post-date', 'wp-block-post-date', 'published', 'time'];
                        $xpath_parts = [];
                        foreach ($date_containers as $c) { $xpath_parts[] = "contains(@class, '$c') or contains(@id, '$c')"; }
                        $nodes = $xpath->query("//*[ " . implode(' or ', $xpath_parts) . " ]");
                        
                        foreach ($nodes as $node) {
                            $txt = trim(strip_tags($node->nodeValue ?: $node->textContent));
                            if (empty($txt)) continue;
                            
                            // Check for Spanish date pattern: "8 de agosto 2023"
                            if (preg_match('/\b\d{1,2}\s+(?:de\s+)?(?:enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)\s+(?:de\s+)?20\d{2}\b/ui', $txt, $es_match)) {
                                $date = $es_match[0];
                                break;
                            }
                        }
                    }

                    // 4. Last Resort: Any meta tag with 'date' or 'time'
                    if (empty($date)) {
                        $nodes = $xpath->query("//meta[contains(@property, 'date') or contains(@name, 'date') or contains(@property, 'time') or contains(@name, 'time')]/@content");
                        foreach ($nodes as $node) {
                            $val = trim($node->nodeValue);
                            if ($val && strtotime($val)) {
                                $date = $val;
                                break;
                            }
                        }
                    }
                    libxml_clear_errors();
                }

                // 4. Hard fallback regex for YYYY-MM-DD or MM/DD/YYYY
                if (empty($date)) {
                    // Try regex first for article:published_time directly from HTML (more robust than DOM for messy sites)
                    if (preg_match('/property=["\']article:published_time["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $reg_pdate)) {
                        $date = $reg_pdate[1];
                    } elseif (preg_match('/name=["\']article:published_time["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $reg_pdate)) {
                        $date = $reg_pdate[1];
                    }

                    if (empty($date)) {
                        $head_sample = substr($html, 0, 5000);
                        if (preg_match('/\b(20\d{2}-\d{2}-\d{2})\b/', $head_sample, $simple_date)) {
                            $date = $simple_date[1];
                        } elseif (preg_match('/\b(0?[1-9]|1[0-2])\/(0?[1-9]|[12]\d|3[01])\/(20\d{2})\b/', $head_sample, $slash_date)) {
                            $date = $slash_date[3] . '-' . $slash_date[1] . '-' . $slash_date[2];
                        }
                    }
                }

                // 5. Cleanup found date (Spanish Translation)
                if (!empty($date)) {
                    $raw_date = $date; // Capture raw string
                    $es_months = ['enero' => 'january', 'febrero' => 'february', 'marzo' => 'march', 'abril' => 'april', 'mayo' => 'may', 'junio' => 'june', 'julio' => 'july', 'agosto' => 'august', 'septiembre' => 'september', 'setiembre' => 'september', 'octubre' => 'october', 'noviembre' => 'november', 'diciembre' => 'december'];
                    $date = str_ireplace(array_keys($es_months), array_values($es_months), $date);
                    $date = str_ireplace([' de ', ' del '], [' ', ' '], $date);
                }

                // Final formatting
                if ($date) {
                    $timestamp = strtotime($date);
                    if ($timestamp && $timestamp > 0) {
                        $date = date_i18n('F j, Y', $timestamp);
                        $date_source = 'Scraped';
                    } else {
                        $date = ''; // Reset if invalid
                    }
                }
                // author/avatar/tags logic (moved back inside)





                /**
                 * ===================================
                 *  EXTERNAL AUTHOR
                 * ===================================
                 */

                // 1. meta property="article:author"
                if (preg_match('/<meta[^>]+property=["\']article:author["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta_author)) {
                    $author_name = $meta_author[1];
                }
                // 2. JSON-LD author
                elseif (preg_match('/"author"\s*:\s*\{[^}]*"name"\s*:\s*"([^"]+)"/i', $html, $json_author)) {
                    $author_name = $json_author[1];
                }
                // 3. meta name="author"
                elseif (preg_match('/<meta[^>]+name=["\']author["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta_name_author)) {
                    $author_name = $meta_name_author[1];
                }
                // 4. Extract from title if it looks like "Title - Author" (already in raw_title_tag)
                elseif ($raw_title_tag && preg_match('/[-–—]\s*([A-ZÀ-ÿ][a-zà-ÿ]+(?:\s[A-ZÀ-ÿ][a-zà-ÿ]+)+)$/u', $raw_title_tag, $match_author)) {
                    $author_name = $match_author[1];
                }

                // 5. Build a DOMXPath search if still empty
                if (empty($author_name)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    // Search for common author patterns
                    $author_queries = [
                        "//*[@rel='author']",
                        "//*[@itemprop='author']",
                        "//*[contains(@class, 'author-name')]",
                        "//*[contains(@class, 'entry-author')]",
                        "//*[contains(@class, 'vcard')]//*[contains(@class, 'fn')]"
                    ];

                    foreach ($author_queries as $query) {
                        $nodes = $xpath->query($query);
                        if ($nodes->length > 0) {
                            $potential_name = trim(strip_tags($nodes->item(0)->textContent));
                            // Basic validation: name should be 2-30 chars and not contain weird chars
                            if (strlen($potential_name) > 3 && strlen($potential_name) < 50 && !preg_match('/[<>{}]/', $potential_name)) {
                                $author_name = $potential_name;
                                break;
                            }
                        }
                    }
                }

                /**
                 * ===================================
                 *  EXTERNAL AUTHOR AVATAR
                 * ===================================
                 */

                if (empty($author_avatar)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    // Search for common avatar/author image patterns
                    $avatar_queries = [
                        "//*[contains(@class, 'avatar')]//img",
                        "//img[contains(@class, 'avatar')]",
                        "//img[contains(@class, 'author-image')]",
                        "//*[contains(@class, 'author')]//img",
                        "//*[contains(@id, 'author')]//img"
                    ];

                    foreach ($avatar_queries as $query) {
                        $nodes = $xpath->query($query);
                        if ($nodes->length > 0) {
                            foreach ($nodes as $node) {
                                $src = $node->getAttribute('src') ?: $node->getAttribute('data-src');
                                if ($src && (!strpos($src, 'gravatar')) && (!strpos($src, 'svg'))) {
                                     // Convert relative URL to absolute
                                     if (strpos($src, 'http') !== 0) {
                                         $url_parts = wp_parse_url($url);
                                         $base = $url_parts['scheme'] . '://' . $url_parts['host'];
                                         $src = $base . '/' . ltrim($src, '/');
                                     }
                                     $author_avatar = $src;
                                     break 2;
                                }
                            }
                        }
                    }
                    libxml_clear_errors();
                }

                /**
                 * ===================================
                 *  EXTERNAL TAGS
                 * ===================================
                 */

                // 1. article:tag meta
                if (preg_match_all('/<meta[^>]+property=["\']article:tag["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta_tags)) {
                    $external_tags = array_unique($meta_tags[1]);
                }

                // 2. rel="tag" links if meta fails
                if (empty($external_tags)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);
                    $tag_nodes = $xpath->query("//a[@rel='tag'] | //*[contains(@class, 'post-tag')] | //*[contains(@class, 'tag-link')]");
                    if ($tag_nodes->length > 0) {
                        foreach ($tag_nodes as $tag_node) {
                            $tag_val = trim(strip_tags($tag_node->textContent));
                            if ($tag_val && strlen($tag_val) < 30) {
                                $external_tags[] = $tag_val;
                            }
                            if (count($external_tags) >= 5) break; 
                        }
                        $external_tags = array_unique($external_tags);
                    }
                    libxml_clear_errors();
                }

                /**
                 * ===================================
                 *  FINAL FALLBACKS (DURING SCRAPE)
                 * ===================================
                 */

                // If no site name, try first part of title (common for "Site Name - Page Title")
                if (empty($site_name) && !empty($raw_title_tag)) {
                    if (preg_match('/^([^-\–—|]+)/u', $raw_title_tag, $site_match)) {
                        $site_name = trim($site_match[1]);
                    }
                }

                // If no author name, use site_name
                if (empty($author_name)) {
                    $author_name = $site_name;
                }

                // If still empty author, use first part of title again (redundancy check)
                if (empty($author_name) && !empty($raw_title_tag)) {
                    if (preg_match('/^([^-\–—|]+)/u', $raw_title_tag, $title_prefix)) {
                        $author_name = trim($title_prefix[1]);
                    }
                }

                // If finally nothing, use 'Editor'
                if (empty($author_name)) {
                    $author_name = __('Editor', 'avante');
                }


                // Cache for 24 hours
                set_transient($transient_key, array(
                    'title' => $title,
                    'image' => $image,
                    'date' => $date,
                    'site_name' => $site_name,
                    'author_name' => $author_name,
                    'author_avatar' => $author_avatar,
                    'external_tags' => $external_tags,
                    'raw_date' => $raw_date,
                    'http_status' => $http_status,
                    'date_source' => $date_source,
                ), DAY_IN_SECONDS);

            } // end html
        } // end response
    } // end transient else
} // end url check

// If no external author/site (even after cache), ensure something is shown
if (empty($site_name) && !empty($url)) {
    $site_name = preg_replace('/^www\./', '', wp_parse_url($url, PHP_URL_HOST));
}
if (empty($author_name)) {
    $author_name = !empty($site_name) ? $site_name : __('Editor', 'avante');
}

// Ensure $author_name doesn't match a generic 'carolinaeslava.com' if we can avoid it
if ($author_name && filter_var($author_name, FILTER_VALIDATE_URL)) {
    $author_name = __('Editor', 'avante');
}


// If no external image, use post featured image
if (empty($image)) {
    $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
}

// Final fallbacks for display
if (empty($date)) {
    $date = get_the_date('F j, Y');
}

// Scraper Activity Debugging
$scraped_title_status = ($title !== get_the_title()) ? 'Scraped' : 'Original/Fallback';
$scraped_site_status = (!empty($site_name)) ? $site_name : 'No Site Name Found';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('glass-post'); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post_body glass-border-bright">
        <div class="post__overlay"></div>
        <div class="post__header">
            <?php
            $post_id = get_the_ID();
            $likes_count = avante_get_likes_count($post_id);
            $has_liked = avante_user_has_liked($post_id);
            echo '<a href="' . esc_url(get_post_format_link('link')) . '" class="format-post-tag">' . avante_get_icon('link') . esc_html(__('Enlace', 'avante')) . '</a>';
            ?>
            <button class="button__like <?= ($has_liked || $likes_count > 0) ? 'liked' : ''; ?>">
                <?= avante_get_icon(($has_liked || $likes_count > 0) ? 'heart-fill' : 'heart'); ?>
                <span class="like-count"><?= $likes_count > 0 ? $likes_count : ''; ?></span>
            </button>
        </div>
        <div class="post--content">
            <div class="post--tags">
                <?php
                if (!empty($external_tags)) {
                    foreach ($external_tags as $tag_name) {
                        echo '<span class="post-tag">' . avante_get_icon('tag') . esc_html($tag_name) . '</span>';
                    }
                } else {
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a class="post-tag" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . avante_get_icon('tag') . esc_html($tag->name) . '</a>';
                        }
                    }
                }
                ?>
            </div>
            <div class="post--date" style="display: flex; align-items: center; gap: 0.5rem;">
                <?= avante_get_icon('date'); ?>
                <p><?= esc_html($date); ?></p>
            </div>
            <!-- Link Preview Debug System: 
                 Date: [<?= esc_html($date); ?>] (Source: <?= esc_html($date_source); ?>) (Raw: <?= esc_html($raw_date ?: 'None'); ?>)
                 Title: [<?= esc_html($title); ?>] (Source: <?= esc_html($scraped_title_status); ?>)
                 Site: [<?= esc_html($scraped_site_status); ?>]
                 HTTP: [<?= esc_html($http_status); ?>]
            -->
            <a href="<?= esc_url($url); ?>" class="post__permalink">
                <h2 class="post__title"><?= esc_html($title); ?></h2>
            </a>
            <div class="post--author">
                <?php 
                if ($author_avatar) {
                    echo '<img class="avatar avatar-70 photo" src="' . esc_url($author_avatar) . '" width="70" height="70" alt="' . esc_attr($author_name) . '" loading="lazy" />';
                } else {
                    echo get_avatar(get_the_author_meta('email'), '70');
                }
                ?>
                <h3 class="author-name">
                    <?= esc_html($author_name); ?>
                </h3>
                <span class="author-description">
                    <?= esc_html(preg_replace('/^www\./', '', wp_parse_url($url, PHP_URL_HOST))); ?>
                </span>
            </div>
        </div>
        <!-- Link Preview Image: <?= esc_html($image ?: 'Empty'); ?> -->
        <?php if ($image): ?>
            <img class="wp-post-image" src="<?= esc_url($image); ?>" alt="<?= esc_attr($title); ?>" loading="lazy" />
        <?php endif; ?>
    </div>
</article>