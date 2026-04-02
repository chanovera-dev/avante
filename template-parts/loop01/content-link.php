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
        $args = array(
            'timeout' => 8,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'redirection' => 5,
        );

        // Bypass agresivo para firewalls de sitios específicos (ej. Wordfence/Cloudflare)
        if (strpos($url, 'crisisconsultant') !== false || true) {
            $args['headers'] = array(
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                'Cache-Control' => 'max-age=0',
                'Connection' => 'keep-alive',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'Upgrade-Insecure-Requests' => '1',
            );
        }

        $response = wp_remote_get($url, $args);

        $http_code = is_wp_error($response) ? 0 : wp_remote_retrieve_response_code($response);
        $http_status = is_wp_error($response) ? 'Error: ' . $response->get_error_message() : 'Code: ' . $http_code;

        $html = '';
        $used_api = false;

        if ($http_code === 200) {
            $html = wp_remote_retrieve_body($response);
            // Detect Cloudflare or similar challenge screens
            if (stripos($html, '<title>Just a moment</title>') !== false || stripos($html, 'Cloudflare') !== false) {
                $html = ''; 
                $http_status = 'Error: WAF Challenge Detected';
            }
        }

        /**
         * ===================================
         *  WP REST API FALLBACK (IF BLOCKED)
         * ===================================
         */
        if (empty($html) || in_array($http_code, [401, 403, 406, 429])) {
            $parsed = wp_parse_url($url);
            if (!empty($parsed['path'])) {
                $slug = basename(trim($parsed['path'], '/'));
                if ($slug && !empty($parsed['host'])) {
                    // Endpoint estándar para REST API de WordPress
                    $api_url = $parsed['scheme'] . '://' . $parsed['host'] . '/wp-json/wp/v2/posts?slug=' . urlencode($slug) . '&_embed=1';
                    $api_res = wp_remote_get($api_url, array('timeout' => 8, 'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0'));
                    
                    if (!is_wp_error($api_res) && 200 === wp_remote_retrieve_response_code($api_res)) {
                        $json_data = json_decode(wp_remote_retrieve_body($api_res), true);
                        
                        if (!empty($json_data) && is_array($json_data) && isset($json_data[0])) {
                            $post_data = $json_data[0];
                            
                            $title = html_entity_decode(strip_tags($post_data['title']['rendered'] ?? ''));
                            
                            $date_raw = $post_data['date'] ?? '';
                            if ($date_raw) {
                                $ts = strtotime($date_raw);
                                if ($ts) $date = date_i18n('F j, Y', $ts);
                            }
                            
                            $site_name = preg_replace('/^www\./', '', $parsed['host']);
                            
                            // Autor desde array embebido '_embedded' -> 'author'
                            if (!empty($post_data['_embedded']['author'][0]['name'])) {
                                $author_name = $post_data['_embedded']['author'][0]['name'];
                                $author_avatar = $post_data['_embedded']['author'][0]['avatar_urls']['96'] ?? '';
                            }
                            
                            // Imagen destacada
                            if (!empty($post_data['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                                $image = $post_data['_embedded']['wp:featuredmedia'][0]['source_url'];
                            }

                            $http_status = 'Code: 200 (Via WP REST API Bypass)';
                            $date_source = 'WP REST API';
                            $used_api = true;
                        }
                    }
                }
            }
        }

        /**
         * ===================================
         *  PLAN C: PROXY ALLORIGINS (ULTIMATE FALLBACK)
         * ===================================
         * Si el servidor bloqueó tanto el HTML directo como el REST API, pedimos la web
         * a través del proxy gratuito de allorigins para saltar el bloqueo de IP/WAF.
         */
        if (empty($html) && !$used_api) {
            $proxy_url = 'https://api.allorigins.win/raw?url=' . urlencode($url);
            $proxy_res = wp_remote_get($proxy_url, array('timeout' => 12));
            
            if (!is_wp_error($proxy_res) && 200 === wp_remote_retrieve_response_code($proxy_res)) {
                $proxy_html = wp_remote_retrieve_body($proxy_res);
                if (!empty($proxy_html) && stripos($proxy_html, '<title>Just a moment</title>') === false && stripos($proxy_html, 'Cloudflare') === false) {
                    $html = $proxy_html;
                    $http_status = 'Code: 200 (Via Proxy Bypass)';
                }
            }
        }


        if (!empty($html) || $used_api) {

            if (!empty($html)) {

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
                if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/is', $html, $og_title)) {
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
                if (preg_match('/<meta[^>]+(?:property|name)=["\'](?:og:image|twitter:image)["\'][^>]+content=["\']([^"\']+)["\']/is', $html, $meta_image)) {
                    $image = $meta_image[1];
                }

                // Fallback agresivo para imágenes de Elementor/Yoast/Astra
                if (empty($image)) {
                    if (preg_match('/"primaryImageOfPage"\s*:\s*\{[^}]*"@id"\s*:\s*"([^"]+)"/', $html, $j_img)) {
                         if (strpos($j_img[1], 'http') === 0) $image = $j_img[1];
                    }
                    if (empty($image) && preg_match('/"thumbnailUrl"\s*:\s*"([^"]+)"/', $html, $j_thumb)) {
                        $image = $j_thumb[1];
                    }
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

                // ÚLTIMO RECURSO: Buscar imagen de Elementor (muy común si lo anterior falla)
                if (empty($image)) {
                    if (preg_match('/class=["\'][^"\']*elementor-image[^"\']*["\'].*src=["\']([^"\']+)["\']/i', $html, $el_img)) {
                        $image = $el_img[1];
                    }
                }

                /**
                 * ===================================
                 *  EXTERNAL DATE
                 * ===================================
                 */
                
                $date_source = 'Fallback';

                if (empty($date)) {
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    // Standard Search
                    $queries = ["//meta[@property='article:published_time']/@content", "//meta[@property='og:published_time']/@content", "//*[@itemprop='datePublished']/@content"];
                    foreach ($queries as $q) {
                        $node = $xpath->query($q);
                        if ($node && $node->length > 0) { $date = $node->item(0)->nodeValue; break; }
                    }

                    // Búsqueda en JSON-LD (Yoast Graph y Schema)
                    if (empty($date)) {
                        $scripts = $doc->getElementsByTagName('script');
                        foreach ($scripts as $s) {
                            if ($s->getAttribute('type') === 'application/ld+json') {
                                $data = json_decode($s->textContent, true);
                                if ($data) {
                                    if (isset($data['@graph'])) {
                                        foreach ($data['@graph'] as $item) {
                                            if (isset($item['datePublished'])) { $date = $item['datePublished']; }
                                            if (empty($author_name) && isset($item['@type']) && $item['@type'] === 'Person' && isset($item['name'])) { $author_name = $item['name']; }
                                            if (empty($image) && isset($item['@type']) && $item['@type'] === 'ImageObject' && isset($item['url'])) { $image = $item['url']; }
                                        }
                                    } else {
                                        if (isset($data['datePublished'])) $date = $data['datePublished'];
                                        if (empty($author_name) && isset($data['author']['name'])) $author_name = $data['author']['name'];
                                    }
                                }
                            }
                        }
                    }

                    // Secondary Body Search (Exclude Sidebars/Widgets)
                    if (empty($date)) {
                        $sidebar_exclude = "not(ancestor::*[contains(@class, 'sidebar') or contains(@id, 'sidebar') or contains(@class, 'widget') or contains(@class, 'related')])";
                        $date_containers = ['publicate-date', 'published-date', 'post-date', 'entry-date', 'publish-date', 'wp-block-post-date', 'date', 'published'];
                        $xpath_parts = [];
                        foreach ($date_containers as $c) { 
                            $xpath_parts[] = "contains(@class, '$c') or contains(@id, '$c') or contains(@class, '" . str_replace('-', '_', $c) . "')"; 
                        }
                        
                        $nodes = $xpath->query("//*[ " . implode(' or ', $xpath_parts) . " ][" . $sidebar_exclude . "]");
                        foreach ($nodes as $node) {
                            $txt = trim(strip_tags($node->nodeValue ?: $node->textContent));
                            if (empty($txt)) continue;
                            
                            // Look for Spanish or ISO patterns
                            // Updated Spanish Regex to handle commas after the month: "22 junio, 2023"
                            if (preg_match('/\b\d{1,2}\s+(?:de\s+)?(?:enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre),?\s+(?:de\s+)?20\d{2}\b/ui', $txt, $es_match)) {
                                $date = $es_match[0]; break;
                            }
                            if (preg_match('/\b(20\d{2}[-\/]\d{2}[-\/]((0?[1-9]|[12]\d|3[01])))\b/', $txt, $iso_match)) {
                                $date = $iso_match[0]; break;
                            }
                        }
                    }
                    libxml_clear_errors();
                }

                // Normalization & Formatting
                if (!empty($date)) {
                    $es_months = ['enero' => 'january', 'febrero' => 'february', 'marzo' => 'march', 'abril' => 'april', 'mayo' => 'may', 'junio' => 'june', 'julio' => 'july', 'agosto' => 'august', 'septiembre' => 'september', 'setiembre' => 'september', 'octubre' => 'october', 'noviembre' => 'november', 'diciembre' => 'december'];
                    // Remove comma before normalization
                    $date = str_replace(',', '', $date);
                    $date = str_ireplace(array_keys($es_months), array_values($es_months), $date);
                    $date = str_ireplace([' de ', ' del '], [' ', ' '], $date);
                    
                    // Kill time part to avoid timezone shifts of +/- 1 day
                    if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $date, $date_only)) {
                        $date = $date_only[1];
                    }

                    $ts = strtotime($date);
                    if ($ts) $date = date_i18n('F j, Y', $ts);
                }
                // (Scraping end)




                /**
                 * ===================================
                 *  EXTERNAL AUTHOR
                 * ===================================
                 */

                // 1. Meta etiquetas (og / name / author)
                if (empty($author_name)) {
                    if (preg_match('/<meta[^>]+(?:property|name)=["\'](?:article:author|author)["\'][^>]+content=["\']([^"\']+)["\']/is', $html, $m_auth)) {
                        $author_name = $m_auth[1];
                    }
                }
                // 2. JSON-LD Graph (Yoast/Elementor/Astra fallback)
                if (empty($author_name)) {
                   if (preg_match('/"@type"\s*:\s*"Person"[^}]+"name"\s*:\s*"([^"]+)"/i', $html, $j_auth)) {
                       $author_name = $j_auth[1];
                   }
                }
                // 3. Extract from title if it looks like "Title - Author" (already in raw_title_tag)
                if (empty($author_name) && $raw_title_tag && preg_match('/[-–—]\s*([A-ZÀ-ÿ][a-zà-ÿ]+(?:\s[A-ZÀ-ÿ][a-zà-ÿ]+)+)$/u', $raw_title_tag, $match_author)) {
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

                    // Search for common avatar/author image patterns + site branding
                    $avatar_queries = [
                        "//*[contains(@class, 'avatar')]//img",
                        "//img[contains(@class, 'avatar')]",
                        "//img[contains(@class, 'author-image')]",
                        "//*[contains(@class, 'author')]//img",
                        "//meta[@property='og:logo']/@content",
                        "//link[@rel='apple-touch-icon']/@href",
                                           "//link[@rel='shortcut icon']/@href"
                    ];

                    foreach ($avatar_queries as $query) {
                        $nodes = $xpath->query($query);
                        if ($nodes && $nodes->length > 0) {
                            foreach ($nodes as $node) {
                                // Extract from various attributes
                                $src = ($node instanceof DOMAttr) ? $node->nodeValue : ($node->getAttribute('src') ?: $node->getAttribute('data-src') ?: $node->getAttribute('href') ?: $node->getAttribute('content'));

                                if ($src && (!strpos($src, 'gravatar')) && (!strpos($src, 'svg'))) {
                                     if (strpos($src, 'http') !== 0) {
                                         $url_p = wp_parse_url($url);
                                         $base = $url_p['scheme'] . '://' . $url_p['host'];
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

                // Buscar Avatar o Logo como fallback si sigue vacío
                if (empty($author_avatar)) {
                    // Buscar og:image:alt, apple-touch-icon para sitios que no usan author links directos
                    if (preg_match('/<meta[^>]+property=["\']og:image:alt["\'][^>]+content=["\']([^"\']+)["\']|apple-touch-icon.*href=["\']([^"\']+)["\']|icon.*href=["\']([^"\']+)["\']/is', $html, $site_logo)) {
                        $author_avatar = $site_logo[2] ?? ($site_logo[3] ?? '');
                    }
                    if (empty($author_avatar) && preg_match('/<img[^>]+class=["\'][^"\']*[Ll]ogo[^"\']*["\'][^>]+src=["\']([^"\']+)["\']/i', $html, $logo_img)) {
                        $author_avatar = $logo_img[1];
                    }
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

                // Buscar Avatar o Logo como fallback si sigue vacío
                if (empty($author_avatar)) {
                    // Buscar og:image:alt, apple-touch-icon para sitios que no usan author links directos
                    if (preg_match('/<meta[^>]+property=["\']og:image:alt["\'][^>]+content=["\']([^"\']+)["\']|apple-touch-icon.*href=["\']([^"\']+)["\']|icon.*href=["\']([^"\']+)["\']/is', $html, $site_logo)) {
                        $author_avatar = $site_logo[2] ?? ($site_logo[3] ?? '');
                    }
                    if (empty($author_avatar) && preg_match('/<img[^>]+class=["\'][^"\']*[Ll]ogo[^"\']*["\'][^>]+src=["\']([^"\']+)["\']/i', $html, $logo_img)) {
                        $author_avatar = $logo_img[1];
                    }
                    // Si todo falla, permitir un Gravatar si hay uno en la página
                    if (empty($author_avatar) && preg_match('/src=["\']([^"\']+gravatar\.com\/avatar[^"\']+)["\']/i', $html, $gravatar_match)) {
                        $author_avatar = html_entity_decode($gravatar_match[1]);
                    }
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


            } // end html parse parsing block
            
            // Cache for 24 hours (Para ambos, HTML scrapeado o REST API)
            if (!empty($title) || !empty($image)) {
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
            }

        } // end valid response (html or api)
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

<?php 
// DEPILACIÓN RÁPIDA DE ERRORES:
// if (strpos($url, 'crisisconsultant') !== false) {
//     echo '<div style="background:#000; color:#0f0; padding:20px; text-align:left; font-family:monospace; font-size: 12px; margin-bottom: 20px;">';
//     echo '<strong>STATUS HTTP:</strong> ' . esc_html($http_status) . '<br>';
//     echo '<strong>¿HAY HTML?:</strong> ' . (empty($html) ? 'NO' : 'SÍ (' . strlen($html) . ' bytes)') . '<br>';
//     if (!empty($html)) {
//         // Obtenemos los 500 primeros caracteres o el contenido de <title> para verificar qué devuelve
//         preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches);
//         echo '<strong>TÍTULO REAL EN HTML:</strong> ' . esc_html($matches[1] ?? 'Sin etiqueta de título') . '<br>';
//     }
//     echo '</div>';
// }
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
        <!-- Link Preview Image: <?= esc_html($image ?: 'Empty'); ?> | Debug: <?= esc_html($http_status) ?> -->
        <?php if ($image): ?>
            <img class="wp-post-image" src="<?= esc_url($image); ?>" alt="<?= esc_attr($title); ?>" loading="lazy" />
        <?php endif; ?>
    </div>
</article>