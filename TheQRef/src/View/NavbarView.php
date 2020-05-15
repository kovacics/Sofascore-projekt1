<?php


namespace src\View;

use src\model\User;
use src\template\TemplateEngine;

if (!isset($_SESSION)) {
    session_start();
}

class NavbarView implements View
{
    function getHtml(): string
    {
        if (isLoggedIn()) {
            $navbar = new TemplateEngine("src/View/components/navbar.html");
            $user = User::get(userID());
            $navbar->addParam("firstName", __(ucfirst($user->firstName)));
            $navbar->addParam("lastName", __(ucfirst($user->lastName)));
        } else {
            $navbar = new TemplateEngine("src/View/components/navbar_not_logged_in.html");
        }
        return $navbar->getHtml();
    }
}

