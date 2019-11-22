<?php

$film = new Film("Avatar", "Adventure movie");

$orm = new TigerORM("tigerOrm", "root", "root");
$orm->save($film);