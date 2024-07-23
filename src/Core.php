<?php

namespace Wptf\Core;

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
		// Register ACF blocks
		add_action('acf/init', [$this, 'register_acf_blocks']);

		// Register Gutenberg blocks
		$this->register_blocks();
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public function register_acf_blocks(): void
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
				call_user_func([$block, 'assets']);
			}
		]);
	}

	/**
	 * @return void
	 */
	public function register_blocks(): void
	{
		/** @var array<string, array<class-string<AcfBaseBlock>|string>> $blocks */
		$blocks = require get_template_directory() . '/src/Config/blocks.php';

		// Gutenberg blocks
		if (!empty($blocks['gutenberg'])) {
			foreach ($blocks['gutenberg'] as $name) {
				if (file_exists(get_template_directory() . "/src/Blocks/{$name}/{$name}.php")) {
					require_once get_template_directory() . "/src/Blocks/{$name}/{$name}.php";
				}
			}
		}
	}
}
