<?php

namespace Wptf\Core\Blocks;

use Wptf\Core\Response;

class AcfBaseBlock implements AcfBlock
{
    /**
     * Block title
     *
     * @var string $title
     */
    public string $title = '';

    /**
     * Block description
     *
     * @var string $description
     */
    public string $description = '';

    /**
     * Block category
     *
     * @var string $category
     */
    public string $category = '';

    /**
     * Block icon
     *
     * @var string $icon
     */
    public string $icon = '';

    /**
     * Block keywords
     *
     * @var array $keywords
     */
    public array $keywords = [];

    /**
     * Block supports
     *
     * @var array $supports
     */
    public array $supports = [];

    /**
     * Render block
     *
     * @param array $block
     * @return Response
     */
    public function render(array $block, string $content, bool $is_preview, int $post_id): Response
    {
        return new Response;
    }

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function assets(): void
    {
        // Enqueue assets here
    }
}