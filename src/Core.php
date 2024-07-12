<?php

namespace Wptf\Core;

use Asko\Router\Router;
use Wptf\Core\Blocks\BaseBlock;

class Core
{
    /**
     * Initialize WPTF
     *
     * @return void
     */
    public function init(): void
    {
        // Register blocks
        add_action('acf/init', [$this, 'register_blocks']);

        // Theme support
        add_theme_support('align-wide');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');

        // Register menus
        add_action('init', [$this, 'register_menus']);

        // Load routes and dispatch
        add_action('template_redirect', [$this, 'register_routes']);
    }

    /**
     * Register blocks 
     *
     * @return void
     */
    public function register_blocks(): void
    {
        /** @var array<string, class-string<BaseBlock>> $blocks */
        $blocks = require get_template_directory() . '/src/Config/blocks.php';

        foreach ($blocks as $name => $class) {
            if (function_exists('acf_register_block_type')) {
                $block_instance = new $class();

                acf_register_block_type([
                    'name' => $name,
                    'title' => $block_instance->title,
                    'description' => $block_instance->description,
                    'render_callback' => function (...$args) use ($block_instance) {
                        $response = \call_user_func([$block_instance, 'render'], ...$args);

                        if ($response instanceof Response) {
                            echo $response->make();
                        }
                    },
                    'category' => $block_instance->category,
                    'icon' => $block_instance->icon,
                    'keywords' => $block_instance->keywords,
                    'supports' => $block_instance->supports,
                    'enqueue_assets' => function () use ($block_instance) {
                        wp_enqueue_style('theme-css', get_template_directory_uri() . '/assets/styles.min.css');
                        call_user_func([$block_instance, 'assets']);
                    }
                ]);
            }
        }
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
