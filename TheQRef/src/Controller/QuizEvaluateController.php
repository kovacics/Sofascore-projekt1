<?php


namespace src\controller;


use src\model\Choice;
use src\model\ChoiceUserAnswer;
use src\model\Comment;
use src\model\Question;
use src\model\Quiz;
use src\model\QuizPlay;
use src\model\UserAnswer;
use src\template\TemplateEngine;
use src\View\CommentAddFormView;
use src\View\CommentView;
use src\View\ForbiddenView;
use src\View\NavbarView;
use src\View\QuestionResultView;

session_start();

class QuizEvaluateController
{

    public function show()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isLoggedIn()) {
                $forbidden = new ForbiddenView();
                echo $forbidden->getHtml();
                return;
            }
            $text = post("comment");
            $quizId = post("quizId");

            $comment = new Comment();
            $comment->quizId = $quizId;
            $comment->userId = userID();
            $comment->text = $text;
            $comment->save();
            header("Refresh:0");

        } else {

            $quizPlayId = get("quizPlayId");
            $quizPlay = QuizPlay::get($quizPlayId);
            $quiz = Quiz::get($quizPlay->quizId);
            $questions = Question::getBy("quizId", $quiz->id);

            $correctCouter = 0;

            $questionViews = [];
            $i = 1;
            foreach ($questions as $q) {
                $qResView = null; //question result view
                $userAnswer = UserAnswer::getByFields(['questionId' => $q->id, 'quizPlayId' => $quizPlayId], UserAnswer::FETCH_ONE);

                $qres = new TemplateEngine("src/View/components/question_result.html");
                $qres->addParam("question", $q);

                switch ($q->type) {
                    case "1":
                        $correct = Choice::getByFields(['questionId' => $q->id, 'correct' => true], Choice::FETCH_ONE);

                        if ($userAnswer === null) {
                            $qResView = new QuestionResultView($q, false, $correct->text, "Empty", $i);
                            break;
                        }

                        $cua = ChoiceUserAnswer::getBy('userAnswerId', $userAnswer->id, ChoiceUserAnswer::FETCH_ONE);
                        $choice = Choice::get($cua->choiceId);

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

                        $correct = Choice::getByFields(['questionId' => $q->id, 'correct' => true]);
                        foreach ($correct as $c) {
                            $correctChoiceIDs[] = $c->id;
                            $correctText[] = $c->text;
                        }
                        if ($userAnswer === null) {
                            $qResView = new QuestionResultView($q, false, implode(",", $correctText), "Empty", $i);
                            break;
                        }

                        $cuas = ChoiceUserAnswer::getBy('userAnswerId', $userAnswer->id);
                        $userChoiceIDs = [];
                        $userText = [];

                        foreach ($cuas as $cua) {
                            $userChoiceIDs[] = $cua->choiceId;
                            $userText[] = Choice::get($cua->choiceId)->text;
                        }

                        if ($correctChoiceIDs == $userChoiceIDs) {
                            $correctCouter++;
                            $qResView = new QuestionResultView($q, true, implode(",", $correctText),
                                implode(",", $userText), $i);


                        } else {
                            $qResView = new QuestionResultView($q, false, implode(",", $correctText),
                                implode(",", $userText), $i);
                        }

                        break;

                    case "3":
                        if ($userAnswer === null) {
                            $qResView = new QuestionResultView($q, false, $q->correctTextAnswer, "Empty", $i);
                            break;
                        }
                        if ($q->correctTextAnswer === trim($userAnswer->textAnswer)) {
                            $correctCouter++;
                            $qResView = new QuestionResultView($q, true, $q->correctTextAnswer, $userAnswer->textAnswer, $i);


                        } else {
                            $qResView = new QuestionResultView($q, false, $q->correctTextAnswer, $userAnswer->textAnswer, $i);
                        }
                }
                $questionViews[] = $qResView;
                $i++;
            }

            $result = $correctCouter / count($questions) * 100;

            $quizPlay->score = $result;
            $quizPlay->save();

            $navbar = new NavbarView();

            $quizResultView = new TemplateEngine("src/View/pages/quiz_result.html");
            $quizResultView->addParam("navbar", $navbar->getHtml());
            $quizResultView->addParam("questions", $questionViews);

            if ($quiz->canBeCommented && isLoggedIn()) {
                $quizId = $quiz->getPrimaryKey();
                $commentViews = array_map(fn($comment) => new CommentView($comment), Comment::getBy("quizId", $quizId));
                $quizResultView->addParam("comments", $commentViews);

                $addCommentForm = new CommentAddFormView($quizId);
                $quizResultView->addParam("commentsForm", $addCommentForm);
            }

            $quizResultView->addParam("result", $result);
            $quizResultView->addParam("loggedIn", isLoggedIn());
            echo $quizResultView->getHtml();


        }
    }
}