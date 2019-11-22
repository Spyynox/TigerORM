<?php

class TigerORM {

    public $db;

    function __construct($dbname, $dbuser, $dbpass) {
        $this->db = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $pass);
    }

    public function save($object) {
        
    }

}