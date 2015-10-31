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
use Framework\Input\Cookie;
use Framework\Misc\Config;
use Framework\Models\CookieModel;
use Framework\Models\UserModel;
use Framework\Util\HashUtils;
use Framework\Util\Utils;

class UserController extends AbstractController {

    public function __construct($model) {
        parent::__construct($model);
    }

    /**
     * @return UserModel
     */
    public function getModel() {
        return parent::getModel();
    }

    public static function getLoggedInController() {
        if ($controller = ControllerManager::getControllerFromSession(__CLASS__)) {
            return $controller;
        } else if (Cookie::exists(Config::get('cookie', 'name'))) {
            /** @var CookieController $cookieController */
            if ($uuid = CookieController::verifyCookie()) {
                return ControllerManager::getController(__CLASS__, ['uuid' => $uuid], true);
            }
        }

        return false;
    }

    public static function getUUIDBy($params) {
        return Database::getInstance()->get(call_user_func(self::getModelClass() . '::getTableName'), $params, ['uuid', 'password_hash']);
    }

    public static function getUUIDByUsername($username) {
        return self::getUUIDBy(['username' => $username]);
    }

    public static function getUUIDByEmail($email) {
        return self::getUUIDBy(['email' => $email]);
    }

    public static function login($unem, $password, $rememberMe = false) {
        if (!empty($data = self::getUUIDByUsername($unem)) || !empty($data = self::getUUIDByEmail($unem))) {
            if (count($data) == 1) $data = $data[0];

            if (HashUtils::verifyHash($password, @$data['password_hash'])) {
                /** @var UserController $userController */
                if ($userController = ControllerManager::getController(__CLASS__, ['uuid' => @$data['uuid']], true)) {
                    if ($rememberMe) {
                        CookieController::generateCookie($userController->getModel()->uuid);
                    }

                    return $userController;
                }
            }
        }

        return false;
    }

    public static function register($firstname, $lastname, $username, $email, $password) {
        if ($uuid = self::getUUIDByUsername($username)) {
            echo 'Username already Exists';
        } else if ($uuid = self::getUUIDByEmail($email)) {
            echo 'Email already exists';
        } else {
            $modelClass = self::getModelClass();

            /** @var UserModel $userModel */
            $userModel = new $modelClass;

            $userModel->uuid = HashUtils::generateUUID();
            $userModel->username = $username;
            $userModel->firstname = $firstname;
            $userModel->lastname = $lastname;
            $userModel->email = $email;
            $userModel->password_hash = HashUtils::hashPassword($password);

            echo 'Registered user';

            return ControllerManager::saveController(new UserController($userModel));
        }

        return false;
    }

    public static function getModelClass() {
        return UserModel::class;
    }

}