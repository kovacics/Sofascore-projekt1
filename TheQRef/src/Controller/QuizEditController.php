<?php


namespace src\controller;


use src\model\Choice;
use src\model\Question;
use src\model\Quiz;
use src\template\TemplateEngine;
use src\View\EditQuestionView;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();


class QuizEditController
{

    public function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $quizId = post("quizId");

            if (!isLoggedIn() || Quiz::get($quizId)->creatorId != userID()) {
                $forbidden = new ForbiddenView();
                echo $forbidden->getHtml();
                return;
            }

            $quiz = Quiz::get($quizId);
            $questions = Question::getBy('quizId', $quizId);


            $name = post("name");
            if (paramExists($name)) {
                $quiz->name = $name;
            }

            $description = post("description");
            if (paramExists($description)) {
                $quiz->description = $description;
            }

            $private = post("private");
            if (paramExists($private) && $private === "true") {
                $quiz->private = true;
            } else {
                $quiz->private = false;
            }

            $canBeCommented = post("canBeCommented");
            if (paramExists($canBeCommented) && $canBeCommented === "true") {
                $quiz->canBeCommented = true;
            } else {
                $quiz->canBeCommented = false;
            }
            $quiz->save();


            foreach ($questions as $q) {
                $allChoices = Choice::getBy('questionId', $q->id);
                $newCorrect = post($q->id);

                if (!paramExists($newCorrect)) {
                    foreach ($allChoices as $c) {
                        $c->correct = false;
                        $c->save();
                    }
                    continue;
                }

                if (paramExists($newCorrect)) {

                    switch ($q->type) {
                        case "1":
                            foreach ($allChoices as $c) {
                                // new correct = single id value
                                if ($c->id == $newCorrect) {
                                    $c->correct = true;
                                } else {
                                    $c->correct = false;
                                }
                                $c->save();
                            }
                            break;
                        case "2":
                            foreach ($allChoices as $c) {
                                // new correct = array of ids
                                if (in_array($c->id, $newCorrect)) {
                                    $c->correct = true;
                                } else {
                                    $c->correct = false;
                                }
                                $c->save();
                            }
                            break;
                        case "3":
                            $q->correctTextAnswer = $newCorrect;
                            $q->save();
                    }
                }
            }

            header("Refresh:0");


        } else {
            $navbar = new NavbarView();

            $quizId = get("quizId");
            $quiz = Quiz::get($quizId);

            if (!isLoggedIn() || Quiz::get($quizId)->creatorId != userID()) {
                $forbidden = new ForbiddenView();
                echo $forbidden->getHtml();
                return;
            }

            $questions = Question::getBy('quizId', $quizId);

            $canBeCommentedPlaceholder = $quiz->canBeCommented ? "checked" : "";
            $privatePlaceholder = $quiz->private ? "checked" : "";

            $quesViews = [];
            foreach ($questions as $question) {
                $questionView = new EditQuestionView($question);
                $quesViews[] = $questionView;
            }

            $page = new TemplateEngine("src/View/pages/quiz_edit.html");
            $page->addParam("navbar", $navbar->getHtml());
            $page->addParam("quiz", $quiz);
            $page->addParam("questions", $quesViews);
            $page->addParam("canBeCommentedPlaceholder", $canBeCommentedPlaceholder);
            $page->addParam("privatePlaceholder", $privatePlaceholder);

            echo $page->getHtml();
        }

    }

}