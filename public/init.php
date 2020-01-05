<?php

use TigerORM\TigerORM;

require_once("vendor/autoload.php");

$film = new Film();
$film->title = "Star Wars";
$film->description = "Sci-Fi movie";

$orm = new TigerORM("tigerOrm", "root", "root", "ORMConfig.json");
$orm->save($film); // saves the object to the db
$films = $orm->findAll("Film"); // finds all movies
$starWars = $orm->findOne("Film", 1); // finds movie with id = 1
$starWars1 = $orm->findBy("Film", ["title"], ["Star Wars"]); // find film where title = Star Wars
$ordered = $orm->findAllOrdered("Film", "title"); // finds all movies ordered by title
$orm->update("Film", ["title"], ["Star Wars I"], ["title"], ["Star Wars"]); // sets the title to Star Wars I for the movies where the title is Star Wars
$test = $orm->exists("Film", ["title"], ["Star Wars"]); // returns true or false if movie with title = Star Wars exists

var_dump($test);