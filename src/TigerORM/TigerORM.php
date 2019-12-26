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
        $where = array_map($this->addQuestionMarks, $fields).\join(" AND ");
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
        $where = array_map($this->addQuestionMarks, $fields).\join(" AND ");
        $req = $pdo->prepare('SELECT * FROM '.$class.' WHERE '.$where.' ORDER BY '.$orderBy);
        $req->execute($id);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function update($class, $fieldsToUpdate, $newValues, $conditionField, $conditionValue) {
        $updates = \array_map($this->addQuestionMarks, $fieldsToUpdate).\join(", ");
        $where = \array_map($this->addQuestionMarks, $conditionField).\join(" AND ");
        $req = $pdo->prepare('UPDATE '.$class.' SET '.$updates.' WHERE '.$where);
        $req->execute(array_push($newValues, $conditionValue));
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function delete($class, $fields, $values) {
        $where = \array_map($this->addQuestionMarks, $fields).\join(" AND ");
        $req = $pdo->prepare("DELETE FROM ".$class." WHERE ".$where);
        $req->execute($values);
        $objs = $this->fetchAllObjects($req, $class);

        return $objs;
    }

    public function count($class, $fieldToCount) {
        $req = $pdo->prepare("SELECT COUNT(".$fieldToCount.") FROM ".$class);
        $req->execute();

        return $req->fetchColumn();
    }

    public function exists($class, $fields, $values) {
        $where = \array_map($this->addQuestionMarks, $fields).\join(" AND ");
        $req = $pdo->prepare("SELECT * FROM ".$class." WHERE EXISTS ( SELECT * FROM ".$class." WHERE ".$where);
        $req->execute($values);
        
        return $req->fetchColumn();
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
    // see https://stackoverflow.com/questions/19898688/how-to-create-a-logfile-in-php for the logs
    // see https://stackoverflow.com/questions/15275689/error-checking-for-pdo-prepared-statements for the errors
    // https://www.php.net/manual/fr/pdo.errorinfo.php

}