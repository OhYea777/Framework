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

class Cookie implements IStorage {

    public function __construct($uuid = null, $expiry = ONE_DAY) {
        if (!empty($uuid)) {
            $this->user_uuid = $uuid;
            $this->token = sha1(Util::generateString(35));
            $this->expiry = date(DATE_FORMAT, time() + $expiry);
        }
    }

    /**
     * @param string $name The storage name
     * @return bool Returns whether the value exists
     */
    public static function exists($name) {
        return isset($_COOKIE[$name]);
    }

    /**
     * @param string $name The storage name
     * @param mixed $default The default value for the storage
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function get($name, $default = false) {
        if (!self::exists($name)) return $default;

        return $_COOKIE[$name];
    }


    /**
     * @param string $name The storage name to remove
     * @param mixed $default The default value for the storage
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function remove($name, $default = false) {
        $value = self::get($name);

        echo 1;

        self::set($name, null, -1);

        return $value ? $value : $default;
    }

    /**
     * @param string $name The name of the cookie
     * @param string $value The value of the cookie
     * @param int|string $timeout Time until the cookie expires
     * @param string $location The location to save the cookie
     * @param string|bool $domain The domain to save the cookie
     * @return bool Returns whether the cookie has been set
     */
    public static function set($name, $value, $timeout = ONE_DAY, $location = '/') {
        if (is_numeric($timeout)) {
            $timeout = time() + $timeout;
        }
        else {
            $timeout = strtotime($timeout);
        }

        if ($return = setcookie($name, $value, $timeout, $location)) $_COOKIE[$name] = $value;

        return $return ? $timeout : $return;
    }

}

?>