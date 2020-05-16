<?php


namespace src\controller;


use src\DB\DAOSQL;
use src\db\DBPool;
use src\model\User;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();

class ProfileController
{
    private DAOSQL $quizDAO;

    public function __construct()
    {
        $this->quizDAO = new DAOSQL(DBPool::getPDO());
    }

    public function profile()
    {
        if(!isLoggedIn()){
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        $navbar = new NavbarView();

        $page = new TemplateEngine("./src/View/pages/show_profile.html");
        $page->addParam("navbar", $navbar->getHtml());
        $page->addParam("user", User::get(userID()));
        $page->addParam("password_change_route", Route::get("passChange")->generate());

        $quizPlays = $this->quizDAO->getAllByUser(userID());
        $page->addParam("quizPlays", $quizPlays);

        echo $page->getHtml();
    }

}






