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

namespace Framework\Controllers;

use Framework\Database\Database;
use Framework\Input\Session;
use Framework\Util\ClassUtils;

class ControllerManager {

    public static function getController($class, $params, $saveInSession = false) {
        if (ClassUtils::isClass($class, AbstractController::class) && !empty($modelClass = call_user_func($class . '::getModelClass'))  && !empty($data = Database::getInstance()->get(call_user_func($modelClass . '::getTableName'), $params))) {
            return self::getControllerFromArray($class, $modelClass, $data, $saveInSession);
        }

        return false;
    }

    public static function getControllerFromSession($class) {
        if (ClassUtils::isClass($class, AbstractController::class) && !empty($modelClass = call_user_func($class . '::getModelClass'))  && Session::exists($class)) {
            $array = [];
            $vars = get_class_vars($modelClass);

            foreach ($vars as $var => $ignored) {
                if (Session::exists($var)) {
                    $array[$var] = Session::get($var);
                }
            }

            return self::getControllerFromArray($class, $modelClass, $array);
        }

        return false;
    }

    private static function getControllerFromArray($class, $modelClass, $data, $saveInSession = false) {
        if ($saveInSession) {
            Session::set($class, true);
        }

        if (array_key_exists(0, $data) && is_array($data[0])) $data = $data[0];

        $model = new $modelClass;

        foreach ($data as $key => $value) {
            if ($saveInSession) {
                Session::set($key, $value);
            }

            $model->$key = $value;
        }

        return new $class($model);
    }

    /**
     * @param AbstractController $controller
     * @return bool
     */
    public static function saveController($controller) {
        if (is_a($controller, AbstractController::class)) {
            $vars = get_object_vars($controller->getModel());

            Database::getInstance()->insert($controller->getModel()->getTableName(), $vars);

            return true;
        }

        return false;
    }

}