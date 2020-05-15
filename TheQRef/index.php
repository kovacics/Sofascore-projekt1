<?php

use src\dispatch\DefaultDispatcher;
use src\model\abstractModel\NotFoundException;
use src\route\Route;

require_once './config/global.php';
require_once './config/routes.php';


try {
    DefaultDispatcher::instance()->dispatch();
} catch (NotFoundException $e) {
    redirect(Route::get("Not found")->generate());
}
