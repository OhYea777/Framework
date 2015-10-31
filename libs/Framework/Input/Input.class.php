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

namespace Framework\Input;

class Input implements IStorage {

    const POST = 0;
    const GET = 1;
    const REQUEST = 2;


    /** @var string */
    private static $_method = self::GET;

    /**
     * @param string $name The storage name
     * @return bool Returns whether the value exists
     */
    public static function exists($name) {
        return isset(self::getArray()[$name]);
    }

    /**
     * @param string $name The storage name
     * @param mixed $default The default value for the storage
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function get($name, $default = false) {
        if (!self::exists($name)) return $default;

        return self::getArray()[$name];
    }

    /**
     * @param int $method The method type to be used for getting input
     */
    public static function setMethodType($method = self::GET) {
        self::$_method = $method == self::POST || $method == self::GET || $method == self::REQUEST ? $method : self::GET;
    }

    /**
     * @return array Returns the array to be user for input
     */
    private static function getArray() {
        switch (self::$_method) {
            case self::POST:
                return $_POST;
            case self::REQUEST:
                return $_REQUEST;
            default:
                return $_GET;
        }
    }

}

?>