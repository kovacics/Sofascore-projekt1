<?php


namespace src\controller;


use src\DB\DAOSQL;
use src\db\DBPool;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ForbiddenView;
use src\View\NavbarView;
session_start();

class QuizListController
{

    private DAOSQL $quizDAO;

    public function __construct()
    {
        $this->quizDAO = new DAOSQL(DBPool::getPDO());
    }

    public function list()
    {
        if(!isLoggedIn()){
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        $navbar = new NavbarView();

        $page = new TemplateEngine("src/View/pages/quiz_list.html");

        $page->addParam("myQuizes", $this->quizDAO->getAllCreatedByUser(userID()));
        $page->addParam("otherQuizes", $this->quizDAO->getAllCreatedByOtherUsers(userID()));
        $page->addParam("navbar", $navbar->getHtml());

        $page->addParam("show_quiz_route", Route::get("show-quiz")->generate());
        $page->addParam("edit_quiz_route", Route::get("edit-quiz")->generate());
        $page->addParam("play_quiz_route", Route::get("play-quiz")->generate());
        $page->addParam("delete_quiz_route", Route::get("delete-quiz")->generate());
        echo $page->getHtml();
    }

}