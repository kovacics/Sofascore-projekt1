<?php

namespace src\dispatch;

use src\model\abstractModel\NotFoundException;
use src\route\Route;

class DefaultDispatcher implements Dispatcher
{
    private static ?DefaultDispatcher $instance = null;

    private function __construct()
    {
    }

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new DefaultDispatcher();
        }
        return self::$instance;
    }

    /**
     * @throws NotFoundException
     */
    function dispatch(): void
    {
        $request = $_SERVER["REQUEST_URI"];

        $request = explode("QRef/", $request);
        $request = $request[1];

        if (($pos = strpos($request, "?")) !== false) {
            $request = substr($request, 0, $pos);
        }

        /**
         * @var Route $matched
         */
        $matched = null;

        foreach (Route::get() as $route) {
            if ($route->match($request)) {
                $matched = $route;
                break;
            }
        }

        if ($matched === null) {
            throw new NotFoundException();
        }

        $controller = "src\controller\\" . ucfirst($matched->getValue("controller"));
        $action = $matched->getValue("method");

        $ctl = null;

        try {
            $ctl = new $controller;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        if (!is_callable(array($ctl, $action))) {
            throw new NotFoundException();
        }

        $ctl->$action();
    }
}