<?php

use Asko\Wptf\Template;

return [
    'get_field' => [Template::class, 'get_field'],
    'get_fields' => [Template::class, 'get_fields'],
    'language_attributes' => [Template::class, 'language_attributes'],
    'bloginfo' => [Template::class, 'bloginfo'],
    'tailwind' => [Template::class, 'tailwind'],
    'url' => [Template::class, 'url'],
    'asset' => [Template::class, 'asset'],
    'wp_nav_menu' => [Template::class, 'wp_nav_menu'],
    'language_switcher' => [Template::class, 'wpml_language_switcher'],
    'current_language' => [Template::class, 'wpml_current_language'],
    'the_content' => [Template::class, 'the_content'],
    '__' => [Template::class, '__'],
    'wp_title' => [Template::class, 'wp_title'],
    'wp_head' => [Template::class, 'wp_head'],
    'wp_footer' => [Template::class, 'wp_footer'],
];