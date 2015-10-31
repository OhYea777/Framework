<?php
/**
 * Created by PhpStorm.
 * User: Vengeance
 * Date: 10/31/2015
 * Time: 10:45 PM
 */

namespace Framework\Database;

use Framework\Misc\Config;
use Framework\Util\ClassUtils;

class Database {

    /** @var AbstractDatabase */
    private static $instance;

    private static $databaseMappings = [
        'sql' => SQLDatabase::class,
        'nosql' => MongoDatabase::class
    ];

    /**
     * @return AbstractDatabase
     */
    public static function getInstance() {
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            $databaseType = strtolower(Config::get('database', 'type'));

            if (array_key_exists($databaseType, self::$databaseMappings)) {
                if (ClassUtils::isClass($databaseClass = self::$databaseMappings[$databaseType], AbstractDatabase::class)) {
                    return new $databaseClass(Config::get('database', 'host'), Config::get('database', 'port'), Config::get('database', 'name'), Config::get('database', 'user'), Config::get('database', 'pass'));
                }
            }

            die ('Unknown database type');
        }
    }

}