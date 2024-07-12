<?php

namespace Asko\Wptf;

use Asko\Collection\Collection;
use WP_Post;
use WP_Query;

class Posts
{
    /**
     * @param array $args
     * @return Collection<WP_Post>
     */
    public static function query(array $args = []): Collection
    {
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            return (new Collection($query->posts))
                ->map(function (WP_Post $post) {
                    $post->post_content = apply_filters('the_content', $post->post_content);
                    $post->content = get_the_content(post: $post);
                    $post->url = get_permalink($post);

                    return $post;
                });
        }

        return new Collection([]);
    }
}