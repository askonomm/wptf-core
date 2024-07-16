<?php

namespace Wptf\Core\Blocks;

use Wptf\Core\Response;

interface AcfBlock
{
    /**
     * Render block
     *
     * @param array $block
     * @return string
     */
    public function render(array $block, string $content, bool $is_preview, int $post_id): string;

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function assets(): void;
}