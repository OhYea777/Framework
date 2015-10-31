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

class Database {

    /** @var \PDO */
    public $db;


    /** @var Database */
    private static $_instance;

    public function __construct($host, $port, $name, $user, $pass) {
        try {
            $this->db = new \PDO("mysql:dbname={$name};host={$host};port={$port}", $user, $pass);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die ('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * @return Database Returns the instance of the database
     */
    public static function getInstance() {
        return isset(self::$_instance) ? self::$_instance : self::$_instance = new Database(Config::get('mysql', 'host'), Config::get('mysql', 'port'), Config::get('mysql', 'name'), Config::get('mysql', 'user'), Config::get('mysql', 'pass'));
    }

    /*
     * @params = array('column' => 'row');
     * @returnData = array('column');
     */
    public function get($table, $params = NULL, $returnData = NULL, $like = FALSE) {
        $table = Config::get('mysql', 'prefix') . $table;

        if ($params) {
            $paramStr = '';

            if (!$like) {
                foreach ($params as $p) {
                    $paramStr .= '`' . array_search($p, $params) . '` = :' . array_search($p, $params) . ' AND ';
                }

                $paramStr = substr($paramStr, 0, -4);
                $stmt = $this->db->prepare('SELECT ' . ($returnData ? implode(', ', $returnData) : '*') . ' FROM `' . $table . '` WHERE ' . $paramStr);

                try {
                    $stmt->execute($params);
                } catch (\PDOException $e) {
                    return printf("Fatal SQL Error: %s", $e->getMessage());
                }
            }
            else {
                $nParams = [];

                foreach ($params as $p) {
                    $paramStr .= '`' . array_search($p, $params) . '` LIKE ? AND ';

                    array_push($nParams, "%{$p}%");
                }

                $paramStr = substr($paramStr, 0, -5);
                $stmt = $this->db->prepare('SELECT ' . ($returnData ? implode(', ', $returnData) : '*') . ' FROM `' . $table . '` WHERE ' . $paramStr);

                try {
                    $stmt->execute($nParams);
                } catch (\PDOException $e) {
                    return printf("Fatal SQL Error: %s", $e->getMessage());
                }
            }

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        else {
            $stmt = $this->db->prepare('SELECT ' . ($returnData ? implode(', ', $returnData) : '*') . ' FROM `' . $table . '`');

            try {
                $stmt->execute($params);
            } catch (\PDOException $e) {
                return printf("Fatal SQL Error: %s", $e->getMessage());
            }

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    /*
     * @params = array('Column' => 'value');
     */
    public function insert($table, $params) {
        $table = Config::get('mysql', 'prefix') . $table;

        try {
            $update = '';

            foreach ($params as $col => $val) {
                if (!empty($update)) $update .= ', ';

                $update .= '`' . $col . '` = :' . $col;
            }

            $stmt = $this->db->prepare('INSERT INTO ' . $table . '(`' . (implode('`, `', array_keys($params))) . '`) VALUES(:' . implode(', :', array_keys($params)) . ') ON DUPLICATE KEY UPDATE ' . $update);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            return printf("Fatal SQL Error: %s", $e->getMessage());
        }

        return true;
    }

}