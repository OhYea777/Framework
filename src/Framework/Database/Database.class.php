<?php
/**
 *    Copyright 2015 OhYea777
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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