<?php


namespace src\controller;


use src\model\ChoiceUserAnswer;
use src\model\Question;
use src\model\Quiz;
use src\model\QuizPlay;
use src\model\UserAnswer;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ForbiddenView;
use src\View\NavbarView;
use src\View\QuestionView;

session_start();

class QuizPlayController
{

    public function play()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $quizId = post("quizId");
            $questions = Question::getBy('quizId', $quizId);

            //save quiz play
            $quizPlay = new QuizPlay();
            $quizPlay->quizId = $quizId;
            $quizPlay->userId = userID();
            $quizPlay->save();

            // check all quiz questions
            foreach ($questions as $q) {
                $choices = post($q->id);
                if (paramExists($choices)) {
                    $userAnswer = new UserAnswer();
                    $userAnswer->questionId = $q->id;
                    $userAnswer->quizPlayId = $quizPlay->getPrimaryKey();
                    $userAnswer->textAnswer = null;
                    $userAnswer->save();

                    switch ($q->type) {
                        case "1":
                            $cua = new ChoiceUserAnswer();
                            $cua->userAnswerId = $userAnswer->getPrimaryKey();
                            $cua->choiceId = $choices;
                            $cua->save();
                            break;
                        case "2":
                            foreach ($choices as $choiceId) {
                                $cua = new ChoiceUserAnswer();
                                $cua->userAnswerId = $userAnswer->getPrimaryKey();
                                $cua->choiceId = $choiceId;
                                $cua->save();
                            }
                            break;
                        case "3":
                            $userAnswer->textAnswer = $choices;
                            $userAnswer->save();
                    }
                }
            }


            //all saved
            $route = Route::get("show-res")->generate();
            redirect("$route?quizPlayId=" . $quizPlay->getPrimaryKey());

        } else {

            $navbar = new NavbarView();

            $quizId = get("quizId");
            $quiz = Quiz::get($quizId);

            if ($quiz->private) {
                $forbidden = new ForbiddenView();
                echo $forbidden->getHtml();
                return;
            }

            $questions = Question::getBy('quizId', $quizId);

            $quesViews = [];
            foreach ($questions as $question) {
                $questionView = new QuestionView($question);
                $quesViews[] = $questionView;
            }

            $quizPlay = new TemplateEngine("src/View/pages/quiz_play.html");
            $quizPlay->addParam("navbar", $navbar->getHtml());
            $quizPlay->addParam("quiz", $quiz);
            $quizPlay->addParam("questions", $quesViews);
            echo $quizPlay->getHtml();

        }
    }
}

