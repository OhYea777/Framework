<?php
/**
 * Created by PhpStorm.
 * User: Vengeance
 * Date: 10/31/2015
 * Time: 10:49 PM
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