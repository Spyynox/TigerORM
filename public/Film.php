<?php

class Film {

    public $title;
    public $description;

    function __construct($title, $description) {
        $this->title = $title;
        $this->description = $description;
    }

}