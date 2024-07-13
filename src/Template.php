<?php

namespace Wptf\Core;

class Template
{
    /**
     * @param mixed ...$args
     * @return string
     */
    public static function acf_get_field(...$args): string
    {
        if (!function_exists('get_field')) {
            return '';
        }

        return get_field(...$args);
    }

    /**
     * @param mixed ...$args
     * @return array
     */
    public static function acf_get_fields(...$args): array
    {
        if (!function_exists('get_fields')) {
            return [];
        }

        return get_fields(...$args);
    }

    /**
     * @return string
     */
    public static function language_attributes($doctype = 'html'): string
    {
        return get_language_attributes($doctype);
    }

    /**
     * @param string $show
     * @return string
     */
    public static function bloginfo(string $show, $filter = 'raw'): string
    {
        return get_bloginfo($show, $filter);
    }

    /**
     * @return string
     */
    public static function tailwind(): string
    {
        $url_to_theme = get_template_directory_uri();

        return <<<HTML
            <link href="{$url_to_theme}/assets/styles.min.css" rel="stylesheet">
        HTML;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function url(string $path, $scheme = null): string
    {
        return home_url($path, $scheme);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function asset(string $path): string
    {
        // Prepend / if it's not there
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return get_template_directory_uri() . '/assets' . $path;
    }

    /**
     * @param $args
     * @return void
     */
    public static function wp_nav_menu($args): void
    {
        wp_nav_menu($args[0] ?? []);
    }

    /**
     * @return void
     */
    public static function wpml_language_switcher(): void
    {
        do_action('wpml_add_language_selector');
    }

    /**
     * @return string
     */
    public static function wpml_current_language(): string
    {
        return apply_filters('wpml_current_language', null);
    }

    public static function the_content(): void
    {
        the_content();
    }

    public static function wp_title(): void
    {
        wp_title();
    }

    public static function wp_head(): void
    {
        wp_head();
    }

    public static function wp_footer(): void
    {
        wp_footer();
    }

    public static function __(string $text): string
    {
        return __($text, 'wptf');
    }
}