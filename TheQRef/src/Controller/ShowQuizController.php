<?php


namespace src\controller;


use src\model\Comment;
use src\model\Quiz;
use src\template\TemplateEngine;
use src\View\CommentView;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();

class ShowQuizController
{

    public function show()
    {
        if (!isLoggedIn()) {
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        $quizId = get("quizId");
        $quiz = Quiz::get($quizId);
        $comments = Comment::getBy("quizId", $quizId);

        $navbar = new NavbarView();
        $page = new TemplateEngine("src/View/pages/quiz_info.html");
        $page->addParam("navbar", $navbar->getHtml());
        $page->addParam("quiz", $quiz);
        $page->addParam("comments", array_map(fn($comment) => new CommentView($comment), $comments));
        echo $page->getHtml();
    }

}