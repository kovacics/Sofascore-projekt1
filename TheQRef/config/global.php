<?php

require_once dirname(__FILE__) . '/../vendor/functions.php';


spl_autoload_register(function ($classname) {
    $fileName = "./" . str_replace("\\", "/", $classname) . ".php";

    if (!is_readable($fileName)) {
        return false;
    }

    require_once $fileName;

    return true;
});
