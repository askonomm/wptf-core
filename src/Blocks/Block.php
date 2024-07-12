<?php

namespace Asko\Wptf\Blocks;

use Asko\Wptf\Response;

interface Block
{
    /**
     * Render block
     *
     * @param array $block
     * @return Response
     */
    public function render(array $block, string $content, bool $is_preview, int $post_id): Response;

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function assets(): void;
}