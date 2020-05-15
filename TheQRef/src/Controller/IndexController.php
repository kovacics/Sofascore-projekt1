<?php
declare(strict_types=1);

namespace src\controller;

use src\template\TemplateEngine;
use src\View\NavbarView;

session_start();


class IndexController
{

    public function index()
    {
        $navbar = new NavbarView();

        $template = new TemplateEngine("src/View/pages/index.html");
        $template->addParam("navbar", $navbar->getHtml());

        echo $template->getHtml();
    }

}
