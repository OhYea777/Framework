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
use Framework\Util\StringUtils;

abstract class AbstractDatabaseObject implements IDatabaseObject {

    public static function generateBaseSQL() {
        $parser = new DatabaseAnnotationParser(get_called_class());
        $cols = $parser->getAsArray();

        $sql = '<pre>CREATE TABLE IF NOT EXISTS `' . Config::get('mysql', 'prefix') . call_user_func(get_called_class() . '::getTableName') . '` (<br>';

        foreach ($cols as $colName => $colInfo) {
            if ($colName == '__indexes__') continue;

            $sql .= "&Tab;`$colName` {$colInfo['type']}({$colInfo['length']})" . ($colInfo['null'] ? ' NOT ' : ' ') . 'NULL' . ($colInfo['defaulttype'] == 'AUTO_INC' ? ' AUTO_INCREMENT' : " DEFAULT " . ($colInfo['null'] ? "'{$colInfo['default']}'" : "NULL")) . ",<br>";
        }

        foreach ($cols['__indexes__'] as $col => $indexInfo) {
            switch ($indexInfo) {
                case 'PRIMARY': {
                    $sql .= "&Tab;PRIMARY KEY (`$col`),<br>";
                }
                    break;
                default: {
                    $sql .= "&Tab;UNIQUE INDEX `$col` (`$col`),<br>";
                }
            }
        }

        return substr($sql, 0, -5) . "<br>)<br>COLLATE='utf8_general_ci'<br>ENGINE=InnoDB;</pre>";
    }

}

interface IDatabaseObject {

    public static function getTableName();

}

class DatabaseAnnotationParser {

    private $reflectionClass;
    private $indexes = [];

    private static $typeToSQL = ['STRING' => 'VARCHAR', 'INTEGER' => 'INT'];

    public function __construct($class) {
        $this->reflectionClass = new \ReflectionClass($class);
    }

    private function getParts($reflectionProperty) {
        $stripped = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", '', str_replace('/', '', str_replace("*", '', $reflectionProperty->getDocComment())))));

        if (StringUtils::stringStartsWith($stripped, '@')) $stripped = substr($stripped, 1);

        return array_values(array_filter(explode('@', $stripped)));
    }

    private function getArgs($annotationParams) {
        return array_values(array_filter(explode(' ', trim(str_replace(['[', ']', ',', '=', '"', '\''], [''], $annotationParams)))));
    }

    private function handleProperty($reflectionProperty) {
        $parts = $this->getParts($reflectionProperty);

        for ($i = 0; $i < count($parts); $i++) {
            $annotationType = substr($parts[$i], 0, 2);

            if (strtoupper($annotationType) != 'DB') continue;

            $annotationParams = substr($parts[$i], strpos(strtoupper($parts[$i]), 'DB') + 2);
            $args = $this->getArgs($annotationParams);

            if (count($args) % 2 != 0) continue;

            $return = ['type' => 'VARCHAR', 'length' => '255', 'defaulttype' => 'value', 'default' => '0', 'null' => 'true'];

            for ($j = 0; $j < count($args); $j += 2) {
                $type = strtolower($args[$j]);
                $value = strtoupper($args[$j + 1]);

                switch ($type) {
                    case 'type': {
                        $return[$type] = array_key_exists($value, self::$typeToSQL) ? self::$typeToSQL[$value] : $value;
                    }
                        break;
                    case 'index': {
                        $this->indexes[$reflectionProperty->getName()] = $value;
                    }
                        break;
                    default: {
                        $return[$type] = $value;
                    }
                }
            }

            $return['null'] = strtolower($return['null']) == 'true' ? true : false;

            return $return;
        }

        return false;
    }

    public function getAsArray() {
        $array = [];

        foreach ($this->reflectionClass->getProperties() as $reflectionProperty) {
            if ($args = $this->handleProperty($reflectionProperty)) {
                $array[$reflectionProperty->getName()] = $args;
            }
        }

        $array['__indexes__'] = $this->indexes;

        return $array;
    }

}