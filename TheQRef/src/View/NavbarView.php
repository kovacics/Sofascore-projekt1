<?php


namespace src\View;

use src\model\User;
use src\route\Route;
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

            $navbar->addParam("home_route", "./" . Route::get("indexEmpty")->generate());
            $navbar->addParam("profile_route", Route::get("profile")->generate());
            $navbar->addParam("logout_route", Route::get("logout")->generate());
            $navbar->addParam("new_quiz_route", Route::get("newQuiz")->generate());
            $navbar->addParam("quiz_list_route", Route::get("quizList")->generate());
            $navbar->addParam("quiz_stats_route", Route::get("quizStats")->generate());
            $navbar->addParam("challenge_route", Route::get("challenge-quiz")->generate());
        } else {
            $navbar = new TemplateEngine("src/View/components/navbar_not_logged_in.html");
            $navbar->addParam("home_route", "./" . Route::get("indexEmpty")->generate());
            $navbar->addParam("login_route", Route::get("login")->generate());
            $navbar->addParam("register_route", Route::get("register")->generate());


        }
        return $navbar->getHtml();
    }
}

