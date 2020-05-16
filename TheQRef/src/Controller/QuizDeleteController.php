<?php


namespace src\controller;


use src\model\Quiz;
use src\route\Route;
use src\View\ForbiddenView;

session_start();


class QuizDeleteController
{

    public function delete()
    {
        $quizId = get("quizId");

        if (!isLoggedIn() || Quiz::get($quizId)->creatorId != userID()) {
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        Quiz::deleteWithPK($quizId);

        redirect(Route::get("quizList")->generate());
    }

}