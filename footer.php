<?php
/**
 * The template for displaying the footer
 *
 * @package avante
 * @since 1.0.0
 */

?>
<footer id="main-footer">
    <section class="block middle-footer">
        <div class="content">
            <div class="about">
                <?php
                // $footer_logo = get_option('avante_footer_logo');
                // $footer_title = get_option('avante_footer_title', __('Sobre ', 'avante') . get_bloginfo('name'));

                // if ($footer_logo): ?>
                    <!-- <img class="footer-logo" src="<?php // echo esc_url($footer_logo); ?>" alt="<?php // echo esc_attr(get_bloginfo('name')); ?>"> -->
                <?php // else: ?>
                    <!-- <h3 class="title-section"><?php // echo esc_html($footer_title); ?></h3> -->
                <?php // endif; ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr__('Home', 'avante'); ?>"> 
                    <div class="logo">
                        <div class="ring-wrap">
                            <div class="glow-ring"></div>
                            <div class="scene">
                                <div class="cube">
                                    <div class="face front">
                                        <div class="grid"></div>
                                        <div class="ai-eye">
                                            <div class="pupil"></div>
                                            <span class="node node-1"></span>
                                            <span class="node node-2"></span>
                                            <span class="node node-3"></span>
                                            <span class="node node-4"></span>
                                        </div>
                                    </div>
                                    <div class="face back">
                                        <div class="grid"></div>
                                    </div>
                                    <div class="face left">
                                        <div class="grid"></div>
                                    </div>
                                    <div class="face right">
                                        <div class="grid"></div>
                                    </div>
                                    <div class="face top">
                                        <div class="grid"></div>
                                    </div>
                                    <div class="face bottom">
                                        <div class="grid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-group">
                            <span class="brand-name">The Crisis Academy</span>
                            <div class="divider"></div>
                            <p class="tagline">Liderazgo en tiempos de crisis</p>
                        </div>
                    </div>
                </a>
                <p class="site-bio">
                    <?php
                    $bio_default = __('Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.', 'avante');
                    $bio = get_option('avante_bio');
                    if (false === $bio || empty($bio)) {
                        $bio = get_theme_mod('avante_bio', $bio_default);
                    }
                    echo wp_kses_post($bio);
                    ?>
                </p>
                <?php
                wp_nav_menu(
                    array(
                        'container_id' => 'social',
                        'container_class' => 'social',
                        'theme_location' => 'social',
                    )
                );
                ?>
            </div>
            <div class="other-links">
                <?php
                $footer_menus = ['footer-1', 'footer-2', 'footer-3'];
                $menu_locations = get_nav_menu_locations();

                foreach ($footer_menus as $location):
                    if (isset($menu_locations[$location])):
                        $menu_id = $menu_locations[$location];
                        $menu_obj = wp_get_nav_menu_object($menu_id);
                        $menu_items = wp_get_nav_menu_items($menu_id);

                        if (!empty($menu_items)): ?>
                            <div class="group-links">
                                <h3 class="title-section"><?php echo esc_html($menu_obj->name); ?></h3>
                                <?php
                                wp_nav_menu([
                                    'container' => 'nav',
                                    'container_class' => 'footer',
                                    'theme_location' => $location,
                                ]);
                                ?>
                            </div>
                        <?php endif;
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    </section>
    <section class="block end-footer">
        <div class="content">
            <p>© <?php bloginfo('name');
            echo ' ' . date("Y"); ?> • <?= __('Todos los Derechos Reservados', 'avante') ?>
            </p>
            <div class="credit">
                <p>Diseñado y desarrollado por <a href="https://chano.dev/" target="_blank" rel="noopener noreferrer">@ChanoDEV</a></p>
            </div>
        </div>
    </section>
</footer>
<?php wp_footer(); ?>
</body>

</html>