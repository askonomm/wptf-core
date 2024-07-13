<?php

namespace Wptf\Core\Models;

use Asko\Orm\Column;

/**
 * @template T
 * @extends Model<T>
 */
class PostMeta extends Model
{
    protected static string $_table = "postmeta";
    protected static string $_identifier = "meta_id";

    #[Column]
    public int $meta_id;

    #[Column]
    public int $post_id;

    #[Column]
    public string $meta_key;

    #[Column]
    public string $meta_value;

    public function value()
    {
        // json_decode if json
        if (json_validate($this->meta_value)) {
            return json_decode($this->meta_value, true);
        }

        // Unserialize if serialized
        if (is_serialized($this->meta_value)) {
            return unserialize($this->meta_value);
        }

        // Return as is
        return $this->meta_value;
    }
}