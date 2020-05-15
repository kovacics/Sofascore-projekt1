<?php


namespace src\controller;


use src\model\Choice;
use src\model\Question;
use src\template\TemplateEngine;
use src\View\NavbarView;
use src\View\QuestionResultView;
use src\View\QuestionView;

session_start();

class ChallengeController
{
    public function difficulty()
    {
        $navbar = new NavbarView();
        $quizPlay = new TemplateEngine("src/View/pages/quiz_challenge_start.html");
        $quizPlay->addParam("navbar", $navbar->getHtml());
        echo $quizPlay->getHtml();
    }

    public function play()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $qids = post("qids");

            $questionViews = [];
            $i = 1;
            $correctCouter = 0;
            foreach ($qids as $qid) {

                $q = Question::get($qid);
                $userChoiceIds = post($qid);

                $qResView = null; //question result view

                switch ($q->type) {
                    case "1":
                        $correct = Choice::getByFields(['questionId' => $qid, 'correct' => true], Choice::FETCH_ONE);

                        if (!paramExists($userChoiceIds)) {
                            $qResView = new QuestionResultView($q, false, $correct->text, "Empty", $i);
                            break;
                        }

                        $choice = Choice::get($userChoiceIds);

                        if ($choice->correct) {
                            $correctCouter++;
                            $qResView = new QuestionResultView($q, true, $correct->text, $choice->text, $i);

                        } else {
                            $qResView = new QuestionResultView($q, false, $correct->text, $choice->text, $i);
                        }
                        break;

                    case "2":
                        $correctChoiceIDs = [];
                        $correctText = [];

                        $correct = Choice::getByFields(['questionId' => $qid, 'correct' => true]);
                        foreach ($correct as $c) {
                            $correctChoiceIDs[] = $c->id;
                            $correctText[] = $c->text;
                        }
                        if (!paramExists($userChoiceIds)) {
                            $qResView = new QuestionResultView($q, false, implode(",", $correctText), "Empty", $i);
                            break;
                        }

                        $userText = [];

                        foreach ($userChoiceIds as $cid) {
                            $userText[] = Choice::get($cid)->text;
                        }

                        if ($correctChoiceIDs == $userChoiceIds) {
                            $correctCouter++;
                            $qResView = new QuestionResultView($q, true, implode(",", $correctText), implode(",", $userText), $i);

                        } else {
                            $qResView = new QuestionResultView($q, false, implode(",", $correctText), implode(",", $userText), $i);
                        }

                        break;

                    case "3":
                        if (!paramExists($userChoiceIds)) {
                            $qResView = new QuestionResultView($q, false, $q->correctTextAnswer, "Empty", $i);
                            break;
                        }
                        if ($q->correctTextAnswer === trim($userChoiceIds)) {
                            $correctCouter++;
                            $qResView = new QuestionResultView($q, true, $q->correctTextAnswer, $userChoiceIds, $i);

                        } else {
                            $qResView = new QuestionResultView($q, false, $q->correctTextAnswer, $userChoiceIds, $i);
                        }
                }
                $questionViews[] = $qResView;
                $i++;
            }

            $result = $correctCouter / count($qids) * 100;

            $navbar = new NavbarView();

            $quizResultView = new TemplateEngine("src/View/pages/quiz_result.html");
            $quizResultView->addParam("questions", $questionViews);
            $quizResultView->addParam("navbar", $navbar->getHtml());
            $quizResultView->addParam("result", $result);

            echo $quizResultView->getHtml();

        } else {
            $level = get("level");
            switch ($level) {
                case "easy":
                    $n = 5;
                    break;
                case "medium":
                    $n = 10;
                    break;
                case "hard":
                    $n = 15;
                    break;
                default:
                    $n = 5;
            }

            $questions = Question::getAll();
            if(count($questions) === 0){
                $selectedKeys = [];
            }else{
                $selectedKeys = array_rand($questions, $n);
            }

            $quesViews = [];
            foreach ($selectedKeys as $key) {
                $questionView = new QuestionView($questions[$key]);
                $questionView->id = $questions[$key]->id;
                $quesViews[] = $questionView;
            }

            $navbar = new NavbarView();
            $quizPlay = new TemplateEngine("src/View/pages/quiz_challenge.html");
            $quizPlay->addParam("navbar", $navbar->getHtml());
            $quizPlay->addParam("questions", $quesViews);
            echo $quizPlay->getHtml();
        }
    }
}