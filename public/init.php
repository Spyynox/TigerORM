<?php

use TigerORM\TigerORM;

require_once("vendor/autoload.php");

$film = new Film();
$film->title = "Avatar";
$film->description = "Adventure movie";

var_dump($film);

$orm = new TigerORM("tigerOrm", "root", "root", __DIR__."/public/ORMConfig.json");
$orm->save($film);
$films = $orm->findAll("Film");

foreach ($films as $key => $film) {
    echo $film->title;
}