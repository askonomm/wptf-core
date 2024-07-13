<?php

namespace Wptf\Core\Models;

use Asko\Orm\BaseModel;
use Asko\Orm\Drivers\MysqlDriver;

/**
 * @template T
 * @extends BaseModel<T>
 */
class Model extends BaseModel
{
    public function __construct()
    {
        global $wpdb;

        parent::__construct(new MysqlDriver(
            host: DB_HOST,
            name: DB_NAME,
            user: DB_USER,
            password: DB_PASSWORD,
            port: DB_PORT,
            prefix: $wpdb->prefix,
        ));
    }
}