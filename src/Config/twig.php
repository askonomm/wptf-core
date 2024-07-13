<?php

use Wptf\Core\Template;

return [
    // WPTF
    'styles' => [Template::class, 'styles'],
    'url' => [Template::class, 'url'],
    'asset' => [Template::class, 'asset'],

    // WordPress
    'language_attributes' => [Template::class, 'language_attributes'],
    'bloginfo' => [Template::class, 'bloginfo'],
    'wp_nav_menu' => [Template::class, 'wp_nav_menu'],
    '__' => [Template::class, '__'],
    'wp_title' => [Template::class, 'wp_title'],
    'wp_head' => [Template::class, 'wp_head'],
    'wp_footer' => [Template::class, 'wp_footer'],

    // ACF
    'acf_get_field' => [Template::class, 'acf_get_field'],
    'acf_get_fields' => [Template::class, 'acf_get_fields'],

    // WPML
    'wpml_language_switcher' => [Template::class, 'wpml_language_switcher'],
    'wpml_current_language' => [Template::class, 'wpml_current_language'],
];