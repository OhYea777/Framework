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

class Session implements IStorage {
    /**
     * @param string $name The storage name
     * @return bool Returns whether the value exists
     */
    public static function exists($name) {
        return isset($_SESSION[$name]);
    }

    /**
     * @param string $name The storage name
     * @param mixed $default The default value for the storage
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function get($name, $default = false) {
        if (!self::exists($name)) return $default;

        return $_SESSION[$name];
    }

    /**
     * @param string $name The storage name
     * @param mixed $value The value for the storage
     * @param bool $overwrite Whether to overwrite the current value
     */
    public static function set($name, $value, $overwrite = true) {
        if (!self::exists($name) || $overwrite) {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * @param string $name The storage name to remove
     * @param mixed $default The default value for the storage
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function remove($name, $default = false) {
        $value = self::get($name);

        unset($_SESSION[$name]);

        return $value ? $value : $default;
    }

    /**
     * @param string $name The storage name
     * @param mixed $value The value for the storage
     * @param bool $overwrite Whether to overwrite the current value
     * @return bool|mixed Returns the value of the storage, if it exists, otherwise returns the default value
     */
    public static function flash($name, $value = '', $overwrite = true) {
        if (self::exists($name)) {
            return self::remove($name);
        }
        else {
            self::set($name, $value, $overwrite);
        }

        return false;
    }

}

?>