<?php
/**
 * SEO Meta Tags & Structured Data
 *
 * Handles all on-page SEO for the Crisis Academy theme:
 *  - Optimized <title> via document_title_parts filter
 *  - Meta description, canonical, hreflang
 *  - Open Graph & Twitter Card meta tags
 *  - JSON-LD structured data (Organization, Course, FAQPage, WebSite)
 *
 * @package Avante
 * @subpackage SEO
 * @since 1.1.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

/*
 * =========================================================================
 * HELPERS
 * =========================================================================
 */

/**
 * Check whether the current page uses the Crisis Academy Homepage template.
 *
 * @return bool
 */
function avante_is_crisis_homepage() {
    return is_page_template('templates/crisisacademy-homepage.php');
}

/*
 * =========================================================================
 * 1. TITLE TAG OPTIMIZATION
 * =========================================================================
 */

/**
 * Override the document <title> for the Crisis Academy homepage.
 *
 * @param array $title_parts The document title parts.
 * @return array Modified title parts.
 */
function avante_seo_document_title_parts($title_parts) {
    if (avante_is_crisis_homepage()) {
        $title_parts['title'] = 'Manejo de Crisis y Comunicación de Crisis en México';
        $title_parts['site']  = 'The Crisis Academy';
    }
    return $title_parts;
}
add_filter('document_title_parts', 'avante_seo_document_title_parts');

/**
 * Use a pipe separator instead of the default dash.
 *
 * @return string
 */
function avante_seo_title_separator() {
    return '|';
}
add_filter('document_title_separator', 'avante_seo_title_separator');

/*
 * =========================================================================
 * 2. META TAGS (description, canonical, hreflang, OG, Twitter)
 * =========================================================================
 */

/**
 * Output SEO meta tags in <head>.
 */
function avante_seo_meta_tags() {
    if (!avante_is_crisis_homepage()) {
        return; // Only optimize the homepage for now.
    }

    $site_url    = home_url('/');
    $current_url = home_url($_SERVER['REQUEST_URI']);
    $site_name   = 'The Crisis Academy';

    // — Meta description —
    $meta_description = 'Especialización en manejo de crisis, comunicación de crisis y protección reputacional. '
        . 'Capacitación con simulaciones, certificación profesional y consultoría en México.';

    // — OG Image: use featured image or a fallback —
    $og_image = '';
    if (has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(null, 'full');
    }

    // — Canonical —
    echo '<link rel="canonical" href="' . esc_url($site_url) . '" />' . "\n";

    // — hreflang for Mexico targeting —
    echo '<link rel="alternate" hreflang="es-MX" href="' . esc_url($site_url) . '" />' . "\n";
    echo '<link rel="alternate" hreflang="es" href="' . esc_url($site_url) . '" />' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($site_url) . '" />' . "\n";

    // — Meta description —
    // Remove the generic one from header.php by outputting a specific one
    echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\n";

    // — Geo meta tags for Mexico —
    echo '<meta name="geo.region" content="MX" />' . "\n";
    echo '<meta name="geo.placename" content="México" />' . "\n";
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />' . "\n";

    // — Open Graph —
    echo '<meta property="og:locale" content="es_MX" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="Manejo de Crisis y Comunicación de Crisis en México | The Crisis Academy" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($meta_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($site_url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    }

    // — Twitter Card —
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="Manejo de Crisis y Comunicación de Crisis en México | The Crisis Academy" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($meta_description) . '" />' . "\n";
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'avante_seo_meta_tags', 1);

/**
 * Remove the generic meta description from header.php when we output our own.
 * This works by filtering the output buffer, but a simpler approach is to
 * conditionally skip it in header.php. We'll handle it via a helper flag.
 */
function avante_seo_should_skip_generic_meta_description() {
    return avante_is_crisis_homepage();
}

/*
 * =========================================================================
 * 3. JSON-LD STRUCTURED DATA (Schema.org)
 * =========================================================================
 */

/**
 * Output JSON-LD structured data in the <head>.
 */
function avante_seo_json_ld() {
    if (!avante_is_crisis_homepage()) {
        return;
    }

    $site_url  = home_url('/');
    $site_name = 'The Crisis Academy';
    $logo_url  = '';

    // Try to get the custom logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
    }

    $schemas = [];

    // ── 1. Organization Schema ──
    $organization = [
        '@type'       => 'EducationalOrganization',
        '@id'         => $site_url . '#organization',
        'name'        => $site_name,
        'alternateName' => 'Academia de Crisis',
        'url'         => $site_url,
        'description' => 'Academia especializada en entrenamiento estratégico para el manejo de crisis reputacionales, comunicación de riesgos y control de narrativa en México y Latinoamérica.',
        'knowsAbout'  => [
            'Manejo de crisis',
            'Comunicación de crisis',
            'Crisis de reputación',
            'Comunicación estratégica',
            'Gestión de riesgos reputacionales',
            'Vocería en crisis',
        ],
        'areaServed' => [
            [
                '@type' => 'Country',
                'name'  => 'México',
            ],
            [
                '@type' => 'Country',
                'name'  => 'Latinoamérica',
            ],
        ],
        'inLanguage' => 'es',
        'sameAs'     => [],
    ];

    if ($logo_url) {
        $organization['logo'] = [
            '@type'      => 'ImageObject',
            'url'        => $logo_url,
            'contentUrl' => $logo_url,
        ];
        $organization['image'] = $logo_url;
    }

    // Add social links from the social menu
    $social_menu_locations = get_nav_menu_locations();
    if (isset($social_menu_locations['social'])) {
        $social_items = wp_get_nav_menu_items($social_menu_locations['social']);
        if ($social_items) {
            foreach ($social_items as $item) {
                $organization['sameAs'][] = $item->url;
            }
        }
    }

    if (empty($organization['sameAs'])) {
        unset($organization['sameAs']);
    }

    $schemas[] = $organization;

    // ── 2. WebSite Schema (with SearchAction for sitelinks) ──
    $schemas[] = [
        '@type'           => 'WebSite',
        '@id'             => $site_url . '#website',
        'name'            => $site_name,
        'alternateName'   => 'Academia de Crisis',
        'url'             => $site_url,
        'inLanguage'      => 'es-MX',
        'publisher'       => ['@id' => $site_url . '#organization'],
        'potentialAction' => [
            [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'        => 'EntryPoint',
                    'urlTemplate'  => $site_url . '?s={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ];

    // ── 3. WebPage Schema ──
    $schemas[] = [
        '@type'       => 'WebPage',
        '@id'         => $site_url . '#webpage',
        'url'         => $site_url,
        'name'        => 'Manejo de Crisis y Comunicación de Crisis en México | The Crisis Academy',
        'description' => 'Especialización en manejo de crisis, comunicación de crisis y protección reputacional. Capacitación con simulaciones, certificación profesional y consultoría en México.',
        'isPartOf'    => ['@id' => $site_url . '#website'],
        'about'       => ['@id' => $site_url . '#organization'],
        'inLanguage'  => 'es-MX',
    ];

    // ── 4. Course Schema (for the certification program) ──
    $schemas[] = [
        '@type'           => 'Course',
        '@id'             => $site_url . '#course',
        'name'            => 'Especialización en Comunicación para Manejo de Crisis',
        'description'     => 'Programa integral de 6 módulos con simulación de alta intensidad y certificación profesional en manejo de crisis, comunicación estratégica y control de narrativa.',
        'provider'        => ['@id' => $site_url . '#organization'],
        'educationalLevel'=> 'Professional',
        'inLanguage'      => 'es',
        'courseMode'       => ['online', 'onsite'],
        'offers'          => [
            '@type'         => 'Offer',
            'category'      => 'Paid',
            'priceCurrency' => 'MXN',
            'availability'  => 'https://schema.org/InStock',
        ],
        'hasCourseInstance' => [
            '@type'      => 'CourseInstance',
            'courseMode'  => 'online',
            'courseWorkload' => 'PT30H',
            'instructor' => [
                '@type' => 'Person',
                'name'  => 'Instructores The Crisis Academy',
            ],
        ],
        'about' => [
            'Manejo de crisis',
            'Comunicación de crisis',
            'Crisis de reputación',
            'Vocería en crisis',
            'Comunicación estratégica',
        ],
    ];

    // ── 5. FAQPage Schema (dynamic from ACF repeater) ──
    $faq_entries = [];

    // We need to get the page that uses the homepage template
    global $post;
    if ($post && have_rows('homepage_faqs', $post->ID)) {
        while (have_rows('homepage_faqs', $post->ID)) {
            the_row();
            $question = get_sub_field('question');
            $answer   = get_sub_field('answer');

            if ($question && $answer) {
                $faq_entries[] = [
                    '@type'          => 'Question',
                    'name'           => wp_strip_all_tags($question),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => wp_strip_all_tags($answer),
                    ],
                ];
            }
        }
    }

    if (!empty($faq_entries)) {
        $schemas[] = [
            '@type'      => 'FAQPage',
            '@id'        => $site_url . '#faq',
            'mainEntity' => $faq_entries,
        ];
    }

    // ── Output all schemas as a single @graph ──
    $json_ld = [
        '@context' => 'https://schema.org',
        '@graph'   => $schemas,
    ];

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    echo "\n</script>\n";
}
add_action('wp_head', 'avante_seo_json_ld', 2);
