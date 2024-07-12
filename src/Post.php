<?php

namespace Wptf\Core;

use WP_Post;

class Post
{
    /**
     * @param int $id
     * @return WP_Post|null
     */
    public static function byId(int $id): ?WP_Post
    {
        $post = get_post($id);

        if (!$post) {
            return null;
        }

        $post->post_content = apply_filters('the_content', $post->post_content);
        $post->content = get_the_content(post: $post);
        $post->url = get_permalink($post);

        return $post;
    }

    /**
     * @param string $name
     * @param array $args
     * @return WP_Post|null
     */
    public static function byName(string $name, array $args = []): ?WP_Post
    {
        $posts = get_posts([...$args, [
            'name' => $name,
            'post_status' => 'publish',
            'posts_per_page' => 1
        ]]);

        if (empty($posts)) {
            return null;
        }

        $post = $posts[0];
        $post->post_content = apply_filters('the_content', $post->post_content);
        $post->content = get_the_content(post: $post);
        $post->url = get_permalink($post);

        return $post;
    }
}