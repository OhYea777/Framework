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
use Framework\Util\HashUtils;
use Framework\Util\Utils;

class CookieController extends AbstractController {

    public function __construct($model) {
        parent::__construct($model);
    }

    public static function generateCookie($uuid) {
        echo 'IP: ' . Utils::getClientIP();

        $model = new CookieModel();
        $token = str_shuffle(sha1(md5(HashUtils::generateString(50))));
        $toVerify = hash("sha512", $token . Utils::getClientIP());

        $model->user_uuid = $uuid;
        $model->expiry = date(DATE_FORMAT, time() + Config::get('cookie', 'timeout'));
        $model->token = $toVerify;

        Cookie::set(Config::get('cookie', 'name'), $token, Config::get('cookie', 'timeout'));

        ControllerManager::saveController(new CookieController($model));
    }

    public static function verifyCookie() {
        if (Cookie::exists(Config::get('cookie', 'name'))) {
            $token = Cookie::get(Config::get('cookie', 'name'));
            $toVerify = hash("sha512", $token . Utils::getClientIP());

            if ($data = Database::getInstance()->get(call_user_func(self::getModelClass() . '::getTableName'), ['token' => $toVerify], ['user_uuid'])) {
                if (array_key_exists(0, $data) && is_array($data[0])) $data = $data[0];

                if (array_key_exists('user_uuid', $data)) {
                    return $data['user_uuid'];
                }
            }

            Cookie::remove(Config::get('cookie', 'name'));
        }

        return false;
    }

    /**
     * @return CookieModel
     */
    public function getModel() {
        return parent::getModel();
    }

    public static function getModelClass() {
        return CookieModel::class;
    }

}