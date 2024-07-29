<?php

namespace Wptf\Core;

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
		$this->register_blocks();
	}

	/**
	 * @return void
	 */
	public function register_blocks(): void
	{
		/** @var array $blocks */
		$block_paths = glob(get_template_directory() . '/src/Blocks/*', GLOB_ONLYDIR);

		if (!empty($block_paths)) {
			foreach ($block_paths as $path) {
				// Block name from directory name
				$name = basename($path);

				// Gutenberg block
				if (file_exists(get_template_directory() . "/src/Blocks/{$name}/{$name}.php")) {
					require_once get_template_directory() . "/src/Blocks/{$name}/{$name}.php";
				}

				// ACF block
				if (file_exists(get_template_directory() . "/src/Blocks/{$name}/block.json")) {
					add_action('init', function() use($name) {
						register_block_type(get_template_directory() . "/src/Blocks/{$name}");
					});
				}
			}
		}
	}
}
