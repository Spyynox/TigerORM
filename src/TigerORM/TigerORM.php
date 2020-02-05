<?php

namespace TigerORM;

class TigerORM {

    private $db;
    private $config;

    function __construct($dbname, $dbuser, $dbpass, $configFile) {
        $this->db = new \PDO('mysql:host=localhost:8889;dbname='.$dbname, $dbuser, $dbpass);
        $content = file_get_contents(__DIR__.'/../../public/'.$configFile);
        $this->config = json_decode($content, true);
    }

    public function save($object) {
        $table = get_class($object);
        $fields = array_keys($this->config[$table]);
        $values = get_object_vars($object);
        $questionMarks = [];
        foreach ($fields as $key => $value) {
            array_push($questionMarks, "?");
        }

        $query = "INSERT INTO ".$table."(".implode(", ", $fields).") VALUES (".implode(", ", $questionMarks).")";
        $req = $this->executeQuery($query, array_values($values));
        $objs = $this->fetchAllObjects($req, $class);
    }

    public function findAll($class) {
        $query = 'SELECT * FROM '.$class;
        $req = $this->executeQuery($query, []);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findOne($class, $id) {
        $query = 'SELECT * FROM '.$class.' WHERE id=?';
        $req = $this->executeQuery($query, [$id]);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findBy($class, $fields, $values) {
        $where = array_map(array($this,'addQuestionMarks'), $fields);
        $where = implode(" AND ", $where);
        $query = 'SELECT * FROM '.$class.' WHERE '.$where;
        $req = $this->executeQuery($query, $values);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findAllOrdered($class, $orderBy) {
        $query = 'SELECT * FROM  '.$class.' ORDER BY '.$orderBy;
        $req = $this->executeQuery($query, []);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function findByOrdered($class, $fields, $values, $orderBy) {
        $where = array_map(array($this,'addQuestionMarks'), $fields);
        $where = implode(" AND ", $where);
        $query = 'SELECT * FROM '.$class.' WHERE '.$where.' ORDER BY '.$orderBy;
        $req = $this->executeQuery($query, $values);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function update($class, $fieldsToUpdate, $newValues, $conditionFields, $conditionValues) {
        $updates = array_map(array($this,'addQuestionMarks'), $fieldsToUpdate);
        $updates = implode(", ", $updates);
        $where = array_map(array($this,'addQuestionMarks'), $conditionFields);
        $where = implode(" AND ", $where);
        $query = 'UPDATE '.$class.' SET '.$updates.' WHERE '.$where;
        $values = array_merge($newValues, $conditionValues);
        $req = $this->executeQuery($query, $values);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function delete($class, $fields, $values) {
        $where = array_map(array($this,'addQuestionMarks'), $fields);
        $where = implode(" AND ", $where);
        $query = "DELETE FROM ".$class." WHERE ".$where;
        $req = $this->executeQuery($query, $values);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function count($class, $fieldToCount) {
        $query = "SELECT COUNT(".$fieldToCount.") FROM ".$class;
        $req = $this->executeQuery($query, []);

        return $req->fetchColumn();
    }

    public function exists($class, $fields, $values) {
        $where = array_map(array($this,'addQuestionMarks'), $fields);
        $where = implode(" AND ", $where);
        $query = "SELECT * FROM ".$class." WHERE EXISTS ( SELECT * FROM ".$class." WHERE ".$where.")";
        $req = $this->executeQuery($query, $values);
        
        return $req->fetchColumn();
    }

    private function executeQuery($query, $values) {
        $date = \date("Y-M-d hh:mm");
        try { 
            $req = $this->db->prepare($query);

            if (!empty($values)) {
                $req->execute($values);
            }
            else {
                $req->execute();
            }

            $this->addLogs(false, $query, $date, date("Y-M-d hh:mm") - $date);
            return $req;
        }
        catch(PDOException $e) {
            $this->addLogs(true, $query, $date, $e->getMessage());
            return [];
        }
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

    public static function addQuestionMarks($field) {
        return $field."=?";
    }

    private function addLogs($isError, $request, $date, $details) {
        if (!$isError) {
            \file_put_contents("request.log", $request." at ".$date." : ".$details."\n", FILE_APPEND);
        }
        else {
            \file_put_contents("error.log", $request." at ".$date." : ".$details."\n", FILE_APPEND);
        }
    }

}