<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Avante
 * @since Avante 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php if (!function_exists('avante_seo_should_skip_generic_meta_description') || !avante_seo_should_skip_generic_meta_description()) : ?>
    <meta name="description" content="<?php echo esc_attr(get_bloginfo('description', 'display')); ?>">
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (function_exists('wp_body_open')) {
        wp_body_open();
    } ?>
    <header id="main-header" role="banner" aria-label="<?php echo esc_attr__('Main header', 'avante'); ?>">
        <div class="glass-backdrop"></div>
        <div class="block">
            <div class="content">
                <div class="site-brand">
                    <?php
                    // if (!has_custom_logo()) {
                    //     printf('<a href="%s" aria-label="%s">%s</a>', esc_url(home_url('/')), esc_attr__('Home', 'avante'), esc_html(get_bloginfo('name')));
                    // } else {
                    //     the_custom_logo();
                    // }
                    ?>
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
                </div>
                <div class="avante-navigation">
                        <?php
                        $menu_html = wp_nav_menu( array(
                            'theme_location'  => 'primary',
                            'container'       => 'nav',
                            'container_class' => 'main-navigation',
                            'echo'            => false,
                            'fallback_cb'     => false,
                        ) );

                        if ( $menu_html ) {
                            // insertar el backdrop justo después de la apertura del <nav ...>
                            $backdrop = '<div class="glass-backdrop glass-bright" aria-hidden="true"></div>';
                            $menu_html = preg_replace(
                                '/(<nav\b[^>]*class=["\\\'][^"\\\']*main-navigation[^"\\\']*["\\\'][^>]*>)/i',
                                '$1' . $backdrop,
                                $menu_html,
                                1
                            );
                            echo $menu_html;
                        }
                    ?>
                    <form role="search" method="get" class="avante-custom-searchform" id="avante-custom-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="section">
                            <label class="screen-reader-text" for="s"><?php esc_html__('Buscar', 'avante'); ?></label>
                            <input class="wp-block-search__input" type="text" value="" name="s" id="s" placeholder="<?php esc_html_e('Buscar', 'avante'); ?>">
                            <div class="buttons-container">
                                <button type="submit" id="searchsubmit" value="Search" aria-label="Activate the search">
                                    <?= avante_get_icon('search'); ?>
                                </button>
                                <button type="button" class="close-mobile-searchform" onclick="closeCustomSearchform()" aria-label="Close mobile search" style="background: transparent; border: none; padding: 0; cursor: pointer;"></button>
                            </div>
                        </div>
                    </form>
                </div>
                <button id="search-mobile__button" class="search-mobile__button" onclick="toggleCustomSearchform()" aria-label="Open search">
                    <div class="icon--wrapper">
                        <div class="bar"></div>
                    </div>
                </button>
                <?php if (has_nav_menu('primary')) : ?>
                    <button id="menu-mobile__button" class="menu-mobile__button" onclick="toggleMenuMobile()" aria-label="Open mobile menu">
                        <span class="bar"></span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>