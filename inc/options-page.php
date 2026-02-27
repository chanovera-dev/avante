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
        __('Datos del tema', 'avante'), // Page title
        __('Datos del tema', 'avante'), // Menu title
        'manage_options',                // Capability
        'avante-options',                  // Menu slug
        'avante_render_options_page',      // Callback function
        'dashicons-admin-generic',       // Icon
        60                               // Position
    );
}
add_action('admin_menu', 'avante_add_options_page');

/**
 * Enqueue Media Uploader scripts for the options page.
 */
function avante_options_media_scripts($hook) {
    if ('toplevel_page_avante-options' !== $hook) {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'avante_options_media_scripts');

/**
 * Register settings, sections, and fields.
 */
function avante_register_settings()
{
    register_setting('avante_options_group', 'avante_ga_id', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
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
        'avante_site_data_section',
        __('Datos del Sitio', 'avante'),
        'avante_section_callback',
        'avante-options'
    );

    add_settings_field(
        'avante_ga_id',
        __('Google Analytics ID', 'avante'),
        'avante_ga_id_render',
        'avante-options',
        'avante_site_data_section'
    );

    add_settings_field(
        'avante_footer_title',
        __('Título Sección Pie de Página', 'avante'),
        'avante_footer_title_render',
        'avante-options',
        'avante_site_data_section'
    );

    add_settings_field(
        'avante_bio',
        __('Biografía Corta', 'avante'),
        'avante_bio_render',
        'avante-options',
        'avante_site_data_section'
    );

    add_settings_field(
        'avante_home_featured_image',
        __('Imagen destacada Home (URL)', 'avante'),
        'avante_home_featured_image_render',
        'avante-options',
        'avante_site_data_section'
    );

    // Color Section
    add_settings_section(
        'avante_colors_section',
        __('Colores del Tema', 'avante'),
        'avante_colors_section_callback',
        'avante-options'
    );

    add_settings_field(
        'avante_theme_preset',
        __('Preajustes de Tema', 'avante'),
        'avante_theme_preset_render',
        'avante-options',
        'avante_colors_section'
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
    );

    foreach ($color_labels as $color_id => $label) {
        add_settings_field(
            'avante_color_' . $color_id,
            $label,
            'avante_color_render',
            'avante-options',
            'avante_colors_section',
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
    // Support color-mix
    if (strpos($color, 'color-mix') !== false) {
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
    echo '<p>' . __('Define la información básica de tu sitio web.', 'avante') . '</p>';
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
 * Render the featured image field.
 */
function avante_home_featured_image_render()
{
    $value = get_option('avante_home_featured_image');
    ?>
    <div class="avante-media-uploader">
        <input type="text" name="avante_home_featured_image" id="avante_home_featured_image" value="<?php echo esc_attr($value); ?>" class="large-text" style="display: none;">
        <div class="avante-media-preview" style="margin-bottom: 10px;">
            <?php if ($value) : ?>
                <img src="<?php echo esc_url($value); ?>" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button avante-upload-button" id="avante_upload_btn"><?php _e('Seleccionar imagen', 'avante'); ?></button>
        <button type="button" class="button avante-remove-button" id="avante_remove_btn" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php _e('Quitar imagen', 'avante'); ?></button>
        <p class="description"><?php _e('Selecciona una imagen de la biblioteca de medios.', 'avante'); ?></p>
    </div>
    <?php
}

/**
 * Colors section callback.
 */
function avante_colors_section_callback()
{
    echo '<p>' . __('Selecciona un preajuste o personaliza cada color individualmente.', 'avante') . '</p>';
}

/**
 * Render Theme Preset selector.
 */
function avante_theme_preset_render()
{
    $themes = avante_get_color_themes();
    echo '<select id="avante_theme_selector">';
    echo '<option value="">' . __('Seleccionar preajuste...', 'avante') . '</option>';
    foreach ($themes as $id => $theme) {
        echo '<option value="' . esc_attr($id) . '" data-colors="' . esc_attr(json_encode($theme['colors'])) . '">' . esc_html($theme['name']) . '</option>';
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
    $default = $themes['default']['colors'][$id] ?? '#000000';
    $value = get_option('avante_color_' . $id);
    
    if (empty($value)) {
        $value = $default;
    }

    echo '<div class="avante-color-picker-wrapper" style="display:flex; align-items:center; gap:10px;">';
    echo '<input type="color" name="avante_color_' . $id . '" id="avante_color_' . $id . '" value="' . esc_attr($value) . '">';
    echo ' <code>' . esc_html($value) . '</code>';
    echo '<button type="button" class="button avante-reset-color" data-id="' . esc_attr($id) . '" data-default="' . esc_attr($default) . '">' . __('Resetear', 'avante') . '</button>';
    echo '</div>';
}

/**
 * Render the options page HTML.
 */
function avante_render_options_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('avante_options_group');
            do_settings_sections('avante-options');
            submit_button(__('Guardar Cambios', 'avante'));
            ?>
        </form>
    </div>
    <script>
        document.getElementById('avante_theme_selector').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            if (!selected.value) return;

            var colors = JSON.parse(selected.getAttribute('data-colors'));
            for (var id in colors) {
                var input = document.getElementById('avante_color_' + id);
                if (input) {
                    input.value = colors[id];
                    // Tambien actualizar el label de texto si existe
                    var code = input.nextElementSibling;
                    if (code && code.tagName === 'CODE') {
                        code.textContent = colors[id];
                    }
                }
            }
        });

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
            var mediaUploader;
            $('#avante_upload_btn').click(function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media({
                    title: '<?php _e("Seleccionar Imagen", "avante"); ?>',
                    button: { text: '<?php _e("Usar esta imagen", "avante"); ?>' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#avante_home_featured_image').val(attachment.url);
                    $('.avante-media-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">');
                    $('#avante_remove_btn').show();
                });
                mediaUploader.open();
            });

            $('#avante_remove_btn').click(function(e) {
                e.preventDefault();
                $('#avante_home_featured_image').val('');
                $('.avante-media-preview').empty();
                $(this).hide();
            });
        });
    </script>
    <?php
}