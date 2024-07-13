<?php

namespace Wptf\Core\Models;

use Asko\Orm\Column;
use DateTime;

/**
 * @template T
 * @extends Model<T>
 */
class Post extends Model
{
    protected static string $_table = "posts";
    protected static string $_identifier = "id";

    #[Column]
    public int $id;

    #[Column]
    public int $post_author;

    #[Column]
    public DateTime $post_date;

    #[Column]
    public DateTime $post_date_gmt;

    #[Column]
    public string $post_content;

    #[Column]
    public string $post_title;

    #[Column]
    public string $post_excerpt;

    #[Column]
    public string $post_status;

    #[Column]
    public string $comment_status;

    #[Column]
    public string $ping_status;

    #[Column]
    public string $post_password;

    #[Column]
    public string $post_name;

    #[Column]
    public string $to_ping;

    #[Column]
    public string $pinged;

    #[Column]
    public DateTime $post_modified;

    #[Column]
    public DateTime $post_modified_gmt;

    #[Column]
    public string $post_content_filtered;

    #[Column]
    public int $post_parent;

    #[Column]
    public string $guid;

    #[Column]
    public int $menu_order;

    #[Column]
    public string $post_type;

    #[Column]
    public string $post_mime_type;

    #[Column]
    public int $comment_count;
}