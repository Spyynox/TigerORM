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

    public function findAll($class) {
        $req = $pdo->prepare('SELECT * FROM '.$class);
        $req->execute();
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findOne($class, $id) {
        $req = $pdo->prepare('SELECT * FROM '.$class.' WHERE id=?');
        $req->execute($id);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findBy($class, $fields, $values) {
        $where = array_map($this->addQuestionMarks, $fields);
        $req = $pdo->prepare('SELECT * FROM '.$class.' WHERE '.$where);
        $req->execute($id);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findAllOrdered($class, $orderBy) {
        $req = $pdo->prepare('SELECT * FROM  '.$class.' ORDER BY '.$orderBy);
        $req->execute();
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findByOrdered($class, $fields, $values, $orderBy) {
        $where = array_map($this->addQuestionMarks, $fields);
        $req = $pdo->prepare('SELECT * FROM '.$class.' WHERE '.$where.' ORDER BY '.$orderBy);
        $req->execute($id);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    private function fetchAllObjects($req, $class) {
        $objs = [];
        $isDone = false;

        while (!$isDone) {
            $object = $req->fetchObject($class);

            if ($object != false) {
                array_push($objs, $object);
            }
            else {
                $isDone = true;
            }
            
        }

        return $objs;
    }

    private function addQuestionMarks($field) {
        return $field."=?";
    }

    // see fetchObject PDO

}