<?php

require_once 'vendor/autoload.php';

spl_autoload_register(function($class) {

    $root = $_SERVER['DOCUMENT_ROOT'];
    $path = $root ."/src/". str_replace("\\", "/", $class) .".php";

    if (file_exists($path)) {
        require_once($path);
    } else {
        die("Error: class={$class} does not exists");
    }
});