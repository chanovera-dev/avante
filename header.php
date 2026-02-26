<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <header id="main-header">
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
    <meta name="description" content="<?php echo esc_attr(get_bloginfo('description', 'display')); ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (function_exists('wp_body_open')) {
        wp_body_open();
    } ?>
    <header id="main-header" role="banner" aria-label="<?php echo esc_attr__('Main header', 'avante'); ?>"></header>