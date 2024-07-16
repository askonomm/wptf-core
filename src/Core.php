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

        // Enqueue scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * @return void
     */
    public function enqueue_scripts(): void
    {
        if (WP_DEBUG) {
            wp_enqueue_style('theme-css', get_template_directory_uri() . '/assets/styles.min.css', ver: time());
        } else {
            wp_enqueue_style('theme-css', get_template_directory_uri() . '/assets/styles.min.css');
        }
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

        if (!defined('WP_DEBUG')) {
            define('WP_DEBUG', false);
        }
    }

    /**
     * Register blocks
     *
     * @return void
     */
    public function register_blocks(): void
    {
        /** @var array<string, array<class-string<AcfBaseBlock>|string>> $blocks */
        $blocks = require get_template_directory() . '/src/Config/blocks.php';

        // ACF blocks
        if (!empty($blocks['acf'])) {
            foreach ($blocks['acf'] as $name => $class) {
                $block_instance = new $class();

                // ACF block
                if ($block_instance instanceof AcfBaseBlock) {
                    $this->register_acf_block($name, $block_instance);
                }
            }
        }

        // Gutenberg blocks
        if (!empty($blocks['gutenberg'])) {
            foreach ($blocks['gutenberg'] as $name) {
                require_once get_template_directory() . "/src/Blocks/{$name}/{$name}.php";
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
                echo call_user_func([$block, 'render'], ...$args);
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
}
