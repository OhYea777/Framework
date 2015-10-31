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

namespace app\Models;

use Framework\Models\AbstractModel;

class UserModel extends AbstractModel {

    /**
     * @var int
     * @DB [type = "int", length = "11", defaultType = "auto_inc", index = "primary"]
     */
    public $id;

    /**
     * @var string
     * @DB [length = "36", index = "unique"]
     */
    public $uuid;

    /**
     * @var string
     * @DB [length = "64", index = "unique"]
     */
    public $username;

    /**
     * @var string
     * @DB
     */
    public $firstname;

    /**
     * @var string
     * @DB
     */
    public $lastname;

    /**
     * @var string
     * @DB [index = "unique"]
     */
    public $email;

    /**
     * @var string
     * @DB [length = "60"]
     */
    public $password_hash;

    public static function getTableName() {
        return 'users';
    }

}