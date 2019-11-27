<?php

namespace TigerORM;

class TigerORM {

    private $db;
    private $config;

    function __construct($dbname, $dbuser, $dbpass, $configFile) {
        $this->db = new \PDO('mysql:host=localhost;dbname='.$dbname, $user, $pass);
        $content = file_get_contents($configFile);
        $this->config = json_decode($content);
    }

    public function save($object) {
        $table = get_class($object);
        $fields = array_keys($this->config[$table]);
        $values = get_object_vars($object);
        $questionMarks = [];
        foreach ($fields as $key => $value) {
            array_push($questionMarks, "?");
        }

        $req = $PDO->prepare("INSERT INTO ".$table."(".implode(", ", $fields).") VALUES (".implode(", ", $questionMarks).")");
        $req->execute($values);
    }

    // see fetchObject PDO

}