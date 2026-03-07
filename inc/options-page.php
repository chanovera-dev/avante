<?php
/**
 * Theme Options Page
 *
 * Implements a custom settings page in the WordPress admin panel
 * to manage theme-specific configurations.
 *
 * @package Avante
 * @since Avante 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns the available color themes.
 */
function avante_get_color_themes()
{
    return array(
        'default' => array(
            'name' => __('Clásico', 'avante'),
            'colors' => array(
                'base' => '#FFFFFF',
                'contrast' => '#404954',
                'line' => '#e1e4e8',
                'primary' => '#9dbab9',
                'secondary' => '#d2d6d2',
                'tertiary' => '#eaeeea',
                'background' => '#f5f7f5',
                'button' => '#99BD9F',
                'footer-background' => '#092327',
                'focus' => '#F4442E',
                'bullet' => '#B2D6B6',
                'bullet-active' => '#87AB89',
                'post-shadow-light' => '#40495412 0 -2px 0 inset, #40495412 0px 54px 55px, #40495408 0px -12px 30px, #40495408 0px 4px 6px, #40495408 0px 12px 13px, #40495408 0px -3px 5px',
                'post-shadow' => '#40495420 0px 54px 55px, #4049540f 0px -12px 30px, #4049540f 0px 4px 6px, #40495416 0px 12px 13px, #4049540b 0px -3px 5px',
                'post-shadow-hover' => '#40495440 0px 54px 55px, #4049541f 0px -12px 30px, #4049541f 0px 4px 6px, #4049542b 0px 12px 13px, #40495417 0px -3px 5px',
            ),
        ),
        'zuky' => array(
            'name' => __('Zuky', 'avante'),
            'colors' => array(
                'base' => '#FFFFFF',
                'contrast' => '#655731',
                'line' => '#ebdfbe',
                'primary' => '#feda7c',
                'secondary' => '#fdecbd',
                'tertiary' => '#fef4db',
                'background' => '#fffbf0',
                'button' => '#AF3E4D',
                'footer-background' => '#3F0D12',
                'focus' => '#F90093',
                'bullet' => '#feda7c',
                'bullet-active' => '#cbae63',
                'post-shadow-light' => '#65573112 0 -2px 0 inset, #65573112 0px 54px 55px, #65573108 0px -12px 30px, #65573108 0px 4px 6px, #65573108 0px 12px 13px, #65573108 0px -3px 5px',
                'post-shadow' => '#65573120 0px 54px 55px, #6557310f 0px -12px 30px, #6557310f 0px 4px 6px, #65573116 0px 12px 13px, #6557310b 0px -3px 5px',
                'post-shadow-hover' => '#65573140 0px 54px 55px, #6557311f 0px -12px 30px, #6557311f 0px 4px 6px, #6557312b 0px 12px 13px, #65573117 0px -3px 5px',
            ),
        ),
        'dark' => array(
            'name' => __('Oscuro', 'avante'),
            'colors' => array(
                'base' => '#1a1a1a',
                'contrast' => '#ffffff',
                'line' => 'color-mix(in srgb, currentColor 60%, transparent)',
                'primary' => '#feda7c',
                'secondary' => '#333333',
                'tertiary' => '#2d2d2d',
                'background' => '#121212',
                'button' => '#FF5252',
                'footer-background' => '#000000',
                'focus' => '#F90093',
                'bullet' => '#B2D6B6',
                'bullet-active' => '#cbae63',
                'post-shadow-light' => '#00000012 0 -2px 0 inset, #00000012 0px 54px 55px, #00000008 0px -12px 30px, #00000008 0px 4px 6px, #00000008 0px 12px 13px, #00000008 0px -3px 5px',
                'post-shadow' => '#ffffff20 0px 54px 55px, #ffffff0f 0px -12px 30px, #ffffff0f 0px 4px 6px, #ffffff16 0px 12px 13px, #ffffff0b 0px -3px 5px',
                'post-shadow-hover' => '#ffffff40 0px 54px 55px, #ffffff1f 0px -12px 30px, #ffffff1f 0px 4px 6px, #ffffff2b 0px 12px 13px, #ffffff17 0px -3px 5px',
            ),
        ),
        'ocean' => array(
            'name' => __('Océano', 'avante'),
            'colors' => array(
                'base' => '#f0f8ff',
                'contrast' => '#003366',
                'line' => 'color-mix(in srgb, currentColor 60%, transparent)',
                'primary' => '#0077be',
                'secondary' => '#e1f5fe',
                'tertiary' => '#b3e5fc',
                'background' => '#e0f7fa',
                'button' => '#01579b',
                'footer-background' => '#00254d',
                'focus' => '#00bcd4',
                'bullet' => '#B2D6B6',
                'bullet-active' => '#0077be',
                'post-shadow-light' => '#00336612 0 -2px 0 inset, #00336612 0px 54px 55px, #00336608 0px -12px 30px, #00336608 0px 4px 6px, #00336608 0px 12px 13px, #00336608 0px -3px 5px',
                'post-shadow' => '#00336620 0px 54px 55px, #0033660f 0px -12px 30px, #0033660f 0px 4px 6px, #00336616 0px 12px 13px, #0033660b 0px -3px 5px',
                'post-shadow-hover' => '#00336640 0px 54px 55px, #0033661f 0px -12px 30px, #0033661f 0px 4px 6px, #0033662b 0px 12px 13px, #00336617 0px -3px 5px',
            ),
        ),
        'sakura' => array(
            'name' => __('Sakura', 'avante'),
            'colors' => array(
                'base' => '#fff5f7',
                'contrast' => '#5d3b3e',
                'line' => 'color-mix(in srgb, currentColor 60%, transparent)',
                'primary' => '#ffb7c5',
                'secondary' => '#ffe4e8',
                'tertiary' => '#ffd1dc',
                'background' => '#fff0f3',
                'button' => '#d85d6b',
                'footer-background' => '#4a2c2e',
                'focus' => '#ff69b4',
                'bullet' => '#B2D6B6',
                'bullet-active' => '#ffb7c5',
                'post-shadow-light' => '#5d3b3e12 0 -2px 0 inset, #5d3b3e12 0px 54px 55px, #5d3b3e08 0px -12px 30px, #5d3b3e08 0px 4px 6px, #5d3b3e08 0px 12px 13px, #5d3b3e08 0px -3px 5px',
                'post-shadow' => '#5d3b3e20 0px 54px 55px, #5d3b3e0f 0px -12px 30px, #5d3b3e0f 0px 4px 6px, #5d3b3e16 0px 12px 13px, #5d3b3e0b 0px -3px 5px',
                'post-shadow-hover' => '#5d3b3e40 0px 54px 55px, #5d3b3e1f 0px -12px 30px, #5d3b3e1f 0px 4px 6px, #5d3b3e2b 0px 12px 13px, #5d3b3e17 0px -3px 5px',
            ),
        ),
        'forest' => array(
            'name' => __('Bosque', 'avante'),
            'colors' => array(
                'base' => '#f1f8e9',
                'contrast' => '#1b5e20',
                'line' => 'color-mix(in srgb, currentColor 60%, transparent)',
                'primary' => '#8bc34a',
                'secondary' => '#dcedc8',
                'tertiary' => '#c5e1a5',
                'background' => '#f9fbe7',
                'button' => '#388e3c',
                'footer-background' => '#1b3320',
                'focus' => '#4caf50',
                'bullet' => '#B2D6B6',
                'bullet-active' => '#8bc34a',
                'post-shadow-light' => '#1b5e2012 0 -2px 0 inset, #1b5e2012 0px 54px 55px, #1b5e2008 0px -12px 30px, #1b5e2008 0px 4px 6px, #1b5e2008 0px 12px 13px, #1b5e2008 0px -3px 5px',
                'post-shadow' => '#1b5e2020 0px 54px 55px, #1b5e200f 0px -12px 30px, #1b5e200f 0px 4px 6px, #1b5e2016 0px 12px 13px, #1b5e200b 0px -3px 5px',
                'post-shadow-hover' => '#1b5e2040 0px 54px 55px, #1b5e201f 0px -12px 30px, #1b5e201f 0px 4px 6px, #1b5e202b 0px 12px 13px, #1b5e2017 0px -3px 5px',
            ),
        ),
    );
}

/**
 * Register the Theme Options page in the admin menu.
 */
function avante_add_options_page()
{
    add_menu_page(
        __('Avante theme', 'avante'), // Page title
        __('Avante theme', 'avante'), // Menu title
        'manage_options',                // Capability
        'avante-options',                  // Menu slug
        'avante_render_options_page',      // Callback function
        'dashicons-admin-generic',       // Icon
        60                               // Position
    );

    add_submenu_page(
        'avante-options',
        __('Información', 'avante'),
        __('Información', 'avante'),
        'manage_options',
        'avante-options',
        'avante_render_options_page'
    );

    add_submenu_page(
        'avante-options',
        __('Ajustes', 'avante'),
        __('Ajustes', 'avante'),
        'manage_options',
        'avante-settings',
        'avante_render_settings_page'
    );
}
add_action('admin_menu', 'avante_add_options_page');

/**
 * Enqueue Media Uploader scripts for the options page.
 */
function avante_options_media_scripts($hook) {
    if ('toplevel_page_avante-options' !== $hook && 'avante-theme_page_avante-settings' !== $hook) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style('avante-admin-options', get_template_directory_uri() . '/assets/css/admin-options.css', [], time());
}
add_action('admin_enqueue_scripts', 'avante_options_media_scripts');

/**
 * Register settings, sections, and fields.
 */
function avante_register_settings()
{
    register_setting('avante_options_group', 'avante_theme_preset', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'default',
    ));

    register_setting('avante_options_group', 'avante_ga_id', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    register_setting('avante_options_group', 'avante_header_height', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '30',
    ));

    register_setting('avante_options_group', 'avante_bio', array(
        'type' => 'string',
        'sanitize_callback' => 'wp_kses_post',
    ));

    register_setting('avante_options_group', 'avante_footer_title', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => __('Sobre ', 'avante') . get_bloginfo('name'),
    ));

    register_setting('avante_options_group', 'avante_home_featured_image', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
    ));

    register_setting('avante_options_group', 'avante_footer_logo', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
    ));

    register_setting('avante_options_group', 'avante_rounded', array(
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default' => false,
    ));

    register_setting('avante_options_group', 'avante_loop_design', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'loop',
    ));

    // Color Settings
    $themes = avante_get_color_themes();
    $default_colors = $themes['default']['colors'];

    foreach ($default_colors as $color_id => $default_value) {
        register_setting('avante_options_group', 'avante_color_' . $color_id, array(
            'type' => 'string',
            'sanitize_callback' => 'avante_sanitize_color',
            'default' => $default_value,
        ));
    }

    add_settings_section(
        'avante_readme_section',
        __('', 'avante'),
        'avante_readme_section_callback',
        'avante-options'
    );

    add_settings_section(
        'avante_head_section',
        __('HEAD', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_section(
        'avante_header_section',
        __('HEADER', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_section(
        'avante_footer_section',
        __('FOOTER', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_section(
        'avante_homepage_ajax_section',
        __('HOMEPAGE AJAX', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_section(
        'avante_archive_design_section',
        __('DISEÑO DE ARCHIVO', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_section(
        'avante_colors_themes_section',
        __('TEMAS DE COLOR', 'avante'),
        '__return_empty_string',
        'avante-settings'
    );

    add_settings_field(
        'avante_ga_id',
        __('Google Analytics ID', 'avante'),
        'avante_ga_id_render',
        'avante-settings',
        'avante_head_section'
    );

    add_settings_field(
        'avante_header_height',
        __('Alto del logo (px)', 'avante'),
        'avante_header_height_render',
        'avante-settings',
        'avante_header_section'
    );

    add_settings_field(
        'avante_footer_title',
        __('Título del footer', 'avante'),
        'avante_footer_title_render',
        'avante-settings',
        'avante_footer_section'
    );

    add_settings_field(
        'avante_bio',
        __('Biografía Corta', 'avante'),
        'avante_bio_render',
        'avante-settings',
        'avante_footer_section'
    );

    add_settings_field(
        'avante_home_featured_image',
        __('Imagen destacada Home (URL)', 'avante'),
        'avante_home_featured_image_render',
        'avante-settings',
        'avante_homepage_ajax_section'
    );

    add_settings_field(
        'avante_footer_logo',
        __('Logo del footer (esto sustituye el título del footer)', 'avante'),
        'avante_footer_logo_render',
        'avante-settings',
        'avante_footer_section'
    );

    add_settings_field(
        'avante_rounded',
        __('Activar Formas Redondeadas', 'avante'),
        'avante_rounded_render',
        'avante-settings',
        'avante_archive_design_section'
    );

    add_settings_field(
        'avante_loop_design',
        __('Diseño del Loop (Posts)', 'avante'),
        'avante_loop_design_render',
        'avante-settings',
        'avante_archive_design_section'
    );

    add_settings_field(
        'avante_theme_preset',
        __('Preajustes de Tema', 'avante'),
        'avante_theme_preset_render',
        'avante-settings',
        'avante_colors_themes_section'
    );

    $color_labels = array(
        'base' => __('Color Base (Blanco/Claro)', 'avante'),
        'contrast' => __('Color de Contraste (Texto)', 'avante'),
        'line' => __('Color de Líneas', 'avante'),
        'primary' => __('Color Primario', 'avante'),
        'secondary' => __('Color Secundario', 'avante'),
        'tertiary' => __('Color Terciario', 'avante'),
        'background' => __('Fondo del Sitio', 'avante'),
        'button' => __('Color de Botón', 'avante'),
        'footer-background' => __('Fondo del Pie de Página', 'avante'),
        'focus' => __('Color de Enfoque (Focus)', 'avante'),
        'bullet' => __('Indicador (Bullet)', 'avante'),
        'bullet-active' => __('Indicador Activo (Bullet)', 'avante'),
        'post-shadow-light' => __('Sombra de Post [light]', 'avante'),
        'post-shadow' => __('Sombra de Post', 'avante'),
        'post-shadow-hover' => __('Sombra de Post [hover]', 'avante'),
    );

    foreach ($color_labels as $color_id => $label) {
        add_settings_field(
            'avante_color_' . $color_id,
            $label,
            'avante_color_render',
            'avante-settings',
            'avante_colors_themes_section',
            array('id' => $color_id)
        );
    }
}
add_action('admin_init', 'avante_register_settings');

/**
 * Custom color sanitization to allow both hex and color-mix.
 */
function avante_sanitize_color($color)
{
    // Support color-mix or complex shadow strings (containing spaces)
    if (strpos($color, 'color-mix') !== false || strpos($color, ' ') !== false) {
        return wp_kses_post($color);
    }

    // Support 3, 4, 6, or 8 digit hex colors
    if (preg_match('/^#([A-Fa-f0-9]{3,4}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', $color)) {
        return $color;
    }

    return sanitize_hex_color($color);
}

/**
 * Section callback.
 */
function avante_section_callback()
{
    echo '<p>' . __('Ajustes generales del tema Avante.', 'avante') . '</p>';
}

/**
 * README Section callback to display README.md content.
 */
function avante_readme_section_callback()
{
    $readme_path = get_template_directory() . '/README.md';
    if (!file_exists($readme_path)) {
        echo '<p>' . __('El archivo README.md no fue encontrado.', 'avante') . '</p>';
        return;
    }

    $content = file_get_contents($readme_path);
    
    // Basic Markdown to HTML converter
    $content = esc_html($content);
    
    // Headers
    $content = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $content);
    $content = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $content);
    $content = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $content);
    
    // Bold
    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
    
    // Code blocks
    $content = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $content);
    
    // Lists
    $content = preg_replace('/^- (.*)$/m', '<li>$1</li>', $content);
    $content = preg_replace('/(<li>.*<\/li>)+/s', '<ul>$0</ul>', $content);

    // Links (simple [text](url))
    $content = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" target="_blank">$1</a>', $content);

    // Parrafos (cada línea que no empieza con etiqueta HTML)
    $lines = explode("\n", $content);
    foreach ($lines as &$line) {
        if (!empty($line) && !preg_match('/^<.*?>/', $line)) {
            $line = '<p>' . $line . '</p>';
        }
    }
    $content = implode("\n", $lines);

    echo '<div class="avante-readme-content">';
    echo $content;
    echo '</div>';
}

/**
 * Render the Footer Title field.
 */
function avante_footer_title_render()
{
    $default = __('Sobre ', 'avante') . get_bloginfo('name');
    $value = get_option('avante_footer_title', $default);

    echo '<input type="text" name="avante_footer_title" value="' . esc_attr($value) . '" class="regular-text">';
    echo '<p class="description">' . __('Este título aparecerá sobre la biografía en el pie de página.', 'avante') . '</p>';
}

/**
 * Render the Bio field.
 */
function avante_bio_render()
{
    $default = __('Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.', 'avante');
    
    // Try to get from option first, then from theme_mod, finally use default.
    $value = get_option('avante_bio');
    if (false === $value || empty($value)) {
        $value = get_theme_mod('avante_bio', $default);
    }

    echo '<textarea name="avante_bio" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('Este texto aparecerá en el pie de página.', 'avante') . '</p>';
}

/**
 * Render the GA ID field.
 */
function avante_ga_id_render()
{
    $default = 'G-0000000000';
    
    // Try to get from option first, then from theme_mod, finally use default.
    $value = get_option('avante_ga_id');
    if (false === $value || empty($value)) {
        $value = get_theme_mod('avante_ga_id', $default);
    }

    echo '<input type="text" name="avante_ga_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="G-XXXXXXXXXX">';
    echo '<p class="description">' . __('Ingresa tu ID de Google Analytics (ej. G-XXXXXXXXXX).', 'avante') . '</p>';
}

/**
 * Render the Header Height field.
 */
function avante_header_height_render()
{
    $default = '30';
    $value = get_option('avante_header_height', $default);

    echo '<input type="text" name="avante_header_height" value="' . esc_attr($value) . '" class="small-text" placeholder="63.44">';
    echo '<span> px</span>';
    echo '<p class="description">' . __('Ajusta la altura del logo principal.', 'avante') . '</p>';
}

/**
 * Render the featured image field.
 */
function avante_home_featured_image_render()
{
    $value = get_option('avante_home_featured_image');
    ?>
    <div class="avante-media-uploader" data-target="avante_home_featured_image">
        <input type="text" name="avante_home_featured_image" id="avante_home_featured_image" value="<?php echo esc_attr($value); ?>" class="large-text" style="display: none;">
        <div class="avante-media-preview" style="margin-bottom: 10px;">
            <?php if ($value) : ?>
                <img src="<?php echo esc_url($value); ?>" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button avante-upload-button"><?php _e('Seleccionar imagen', 'avante'); ?></button>
        <button type="button" class="button avante-remove-button" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php _e('Quitar imagen', 'avante'); ?></button>
        <p class="description"><?php _e('Selecciona una imagen de la biblioteca de medios.', 'avante'); ?></p>
    </div>
    <?php
}

/**
 * Render the footer logo field.
 */
function avante_footer_logo_render()
{
    $value = get_option('avante_footer_logo');
    ?>
    <div class="avante-media-uploader" data-target="avante_footer_logo">
        <input type="text" name="avante_footer_logo" id="avante_footer_logo" value="<?php echo esc_attr($value); ?>" class="large-text" style="display: none;">
        <div class="avante-media-preview" style="margin-bottom: 10px;">
            <?php if ($value) : ?>
                <img src="<?php echo esc_url($value); ?>" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button avante-upload-button"><?php _e('Seleccionar imagen', 'avante'); ?></button>
        <button type="button" class="button avante-remove-button" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php _e('Quitar imagen', 'avante'); ?></button>
        <p class="description"><?php _e('Selecciona un logo para el pie de página.', 'avante'); ?></p>
    </div>
    <?php
}

/**
 * Render the rounded checkbox field.
 */
function avante_rounded_render()
{
    $value = get_option('avante_rounded');
    ?>
    <input type="checkbox" name="avante_rounded" id="avante_rounded" value="1" <?php checked(1, $value); ?>>
    <label for="avante_rounded"><?php _e('Activar las curvas en las formas del sitio.', 'avante'); ?></label>
    <p class="description"><?php _e('Si se activa, se cargará el estilo "rounded-shapes" para suavizar los bordes de los elementos.', 'avante'); ?></p>
    <?php
}

/**
 * Render the loop design dropdown field.
 */
function avante_loop_design_render()
{
    $value = get_option('avante_loop_design', 'loop');
    $template_parts_dir = get_template_directory() . '/template-parts/';
    
    // Scan for directories starting with 'loop'
    $loops = array();
    if (is_dir($template_parts_dir)) {
        $dirs = scandir($template_parts_dir);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..' && is_dir($template_parts_dir . $dir) && strpos($dir, 'loop') === 0) {
                $loops[] = $dir;
            }
        }
    }

    if (empty($loops)) {
        $loops = array('loop'); // Fallback
    }

    echo '<select name="avante_loop_design" id="avante_loop_design">';
    foreach ($loops as $loop) {
        echo '<option value="' . esc_attr($loop) . '" ' . selected($value, $loop, false) . '>' . esc_html($loop) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . __('Selecciona el diseño para el listado de posts (carpetas loop, loop2, etc. en template-parts).', 'avante') . '</p>';
}


/**
 * Render Theme Preset selector.
 */
function avante_theme_preset_render()
{
    $themes = avante_get_color_themes();
    $active = get_option('avante_theme_preset', 'default');
    echo '<select name="avante_theme_preset" id="avante_theme_selector">';
    echo '<option value="">' . __('Seleccionar preajuste...', 'avante') . '</option>';
    foreach ($themes as $id => $theme) {
        $selected = selected($active, $id, false);
        echo '<option value="' . esc_attr($id) . '" data-colors="' . esc_attr(json_encode($theme['colors'])) . '" ' . $selected . '>' . esc_html($theme['name']) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . __('Al seleccionar uno, se actualizarán los selectores de abajo.', 'avante') . '</p>';
}

/**
 * Render individual color picker.
 */
function avante_color_render($args)
{
    $id = $args['id'];
    $themes = avante_get_color_themes();
    $active_preset = get_option('avante_theme_preset', 'default');
    $default = $themes[$active_preset]['colors'][$id] ?? ($themes['default']['colors'][$id] ?? '#000000');
    $value = get_option('avante_color_' . $id);
    
    if (empty($value)) {
        $value = $default;
    }

    echo '<div class="avante-color-picker-wrapper" style="display:flex; align-items:center; gap:10px;">';
    if (strpos($id, 'shadow') !== false) {
        echo '<input type="text" name="avante_color_' . $id . '" id="avante_color_' . $id . '" value="' . esc_attr($value) . '" class="regular-text">';
    } else {
        echo '<input type="color" name="avante_color_' . $id . '" id="avante_color_' . $id . '" value="' . esc_attr($value) . '">';
    }
    echo ' <code>' . esc_html($value) . '</code>';
    echo '<button type="button" class="button avante-reset-color" data-id="' . esc_attr($id) . '" data-default="' . esc_attr($default) . '">' . __('Resetear', 'avante') . '</button>';
    echo '</div>';
}

/**
 * Render the settings page HTML.
 */
function avante_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(__('Ajustes del Tema', 'avante')); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('avante_options_group');
            do_settings_sections('avante-settings');
            submit_button(__('Guardar Ajustes', 'avante'));
            ?>
        </form>
    </div>
    <script>
        // Lógica de Preajustes de Tema
        if (document.getElementById('avante_theme_selector')) {
            document.getElementById('avante_theme_selector').addEventListener('change', function() {
                var selected = this.options[this.selectedIndex];
                if (!selected.value) return;

                var colors = JSON.parse(selected.getAttribute('data-colors'));
                for (var id in colors) {
                    var input = document.getElementById('avante_color_' + id);
                    if (input) {
                        input.value = colors[id];
                        var code = input.nextElementSibling;
                        if (code && code.tagName === 'CODE') {
                            code.textContent = colors[id];
                        }
                    }
                }
            });
        }

        // Lógica para los botones de resetear
        document.querySelectorAll('.avante-reset-color').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var defaultValue = this.getAttribute('data-default');
                var input = document.getElementById('avante_color_' + id);
                if (input) {
                    input.value = defaultValue;
                    var code = input.nextElementSibling;
                    if (code && code.tagName === 'CODE') {
                        code.textContent = defaultValue;
                    }
                }
            });
        });

        // Actualizar el texto del hex cuando cambia el color picker manualmente
        document.querySelectorAll('input[type="color"]').forEach(function(picker) {
            picker.addEventListener('input', function() {
                var code = this.nextElementSibling;
                if (code && code.tagName === 'CODE') {
                    code.textContent = this.value;
                }
            });
        });

        // --- Lógica del Media Uploader ---
        jQuery(document).ready(function($) {
            $('.avante-upload-button').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var wrapper = button.closest('.avante-media-uploader');
                var targetId = wrapper.data('target');
                var input = $('#' + targetId);
                var preview = wrapper.find('.avante-media-preview');
                var removeBtn = wrapper.find('.avante-remove-button');

                var mediaUploader = wp.media({
                    title: '<?php _e("Seleccionar Imagen", "avante"); ?>',
                    button: { text: '<?php _e("Usar esta imagen", "avante"); ?>' },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    preview.html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">');
                    removeBtn.show();
                });

                mediaUploader.open();
            });

            $('.avante-remove-button').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var wrapper = button.closest('.avante-media-uploader');
                var targetId = wrapper.data('target');
                $('#' + targetId).val('');
                wrapper.find('.avante-media-preview').empty();
                button.hide();
            });
        });
    </script>
    <?php
}

/**
 * Render the options page HTML (README / Información).
 */
function avante_render_options_page()
{
    ?>
    <div class="wrap">
        
        <?php
        do_settings_sections('avante-options');
        ?>
    </div>
    <?php
}