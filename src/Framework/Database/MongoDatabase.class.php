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

class MongoDatabase extends AbstractDatabase {

    /** @var \MongoDB */
    private $db;

    public function __construct($host, $port, $name, $username, $password) {
        try {
            $client = new \MongoClient("mongodb://{$host}:{$port}");
            $this->db = new \MongoDB($client, $name);
            $this->db->authenticate($username, $password);
        } catch (\MongoException $e) {
            die ('Connection failed: ' . $e->getMessage());
        }
    }

}