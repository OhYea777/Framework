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

require_once('Misc/Config.class.php');

spl_autoload_register(function($class) {
    $parts = explode('\\', $class);

    if (count($parts) >= 2) {
        if (strtolower($parts[0]) == 'framework') {
            $parts = array_slice($parts, 1);

            $class = implode($parts, DIRECTORY_SEPARATOR);

            require_once(__DIR__ . DIRECTORY_SEPARATOR . $class . '.class.php');
        }
    }
});