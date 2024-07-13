<?php

namespace Wptf\Core;

use Asko\Router\Router;
use Wptf\Core\Blocks\AcfBaseBlock;

class Core
{
    /**
     * Initialize WPTF
     *
     * @return void
     */
    public function init(): void
    {
        // Stubs
        $this->stubs();

        // Register blocks
        add_action('acf/init', [$this, 'register_blocks']);

        // Theme support
        add_theme_support('align-wide');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5');

        // Register menus
        add_action('init', [$this, 'register_menus']);

        // Load routes and dispatch
        add_action('template_redirect', [$this, 'register_routes']);
    }

    /**
     * @return void
     */
    private function stubs(): void
    {
        if (!defined('DB_HOST')) {
            define('DB_HOST', 'localhost');
        }

        if (!defined('DB_NAME')) {
            define('DB_NAME', '');
        }

        if (!defined('DB_USER')) {
            define('DB_USER', '');
        }

        if (!defined('DB_PASSWORD')) {
            define('DB_PASSWORD', '');
        }

        if (!defined('DB_PORT')) {
            define('DB_PORT', 3306);
        }
    }

    /**
     * Register blocks
     *
     * @return void
     */
    public function register_blocks(): void
    {
        /** @var array<string, class-string<AcfBaseBlock>> $blocks */
        $blocks = require get_template_directory() . '/src/Config/blocks.php';

        foreach ($blocks as $name => $class) {
            $block_instance = new $class();

            // ACF block
            if ($block_instance instanceof AcfBaseBlock) {
                $this->register_acf_block($name, $block_instance);
            }
        }
    }

    /**
     * @param string $name
     * @param AcfBaseBlock $block
     * @return void
     */
    private function register_acf_block(string $name, AcfBaseBlock $block): void
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        acf_register_block_type([
            'name' => $name,
            'title' => $block->title,
            'description' => $block->description,
            'render_callback' => function (...$args) use ($block) {
                $response = call_user_func([$block, 'render'], ...$args);

                if ($response instanceof Response) {
                    echo $response->make();
                }
            },
            'category' => $block->category,
            'icon' => $block->icon,
            'keywords' => $block->keywords,
            'supports' => $block->supports,
            'enqueue_assets' => function () use ($block) {
                wp_enqueue_style('theme-css', get_template_directory_uri() . '/assets/styles.min.css');
                call_user_func([$block, 'assets']);
            }
        ]);
    }

    /**
     * Register menus
     *
     * @return void
     */
    public function register_menus(): void
    {
        $config = require get_template_directory() . '/src/Config/general.php';

        register_nav_menus($config['menus'] ?? []);
    }

    /**
     * Load routes and dispatch
     *
     * @return void
     */
    public function register_routes(): void
    {
        $router = new Router();
        $routes_fn = require get_template_directory() . '/src/Config/routes.php';
        $routes_fn($router);

        try {
            $response = $router->dispatch();

            if ($response instanceof Response) {
                echo $response->make();
            } else {
                echo $response;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        exit();
    }
}
