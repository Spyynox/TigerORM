<?php

use TigerORM\TigerORM;

require_once("vendor/autoload.php");

$film = new Film();
$film->title = "Star Wars";
$film->description = "Sci-Fi movie";

$orm = new TigerORM("tigerOrm", "root", "root", "ORMConfig.json");
// $orm->save($film);
$films = $orm->findAll("Film");
$test = $orm->exists("Film", ["title"], ["Star Wars"]);

var_dump($test);