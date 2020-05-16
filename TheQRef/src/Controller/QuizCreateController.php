<?php


namespace src\controller;


use src\model\Choice;
use src\model\Question;
use src\model\Quiz;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ErrorView;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();

class QuizCreateController
{
    public function create()
    {
        if (!isLoggedIn()) {
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $route = Route::get("newQuiz")->generate();


            $name = post("name");
            $description = post("description");
            $qref = post("qref");
            $public = post("public");
            $private = true;

            if (!(paramExists($name) && paramExists($description) &&
                (paramExists($qref) || $this->isFileUploaded()))) {
                redirect("$route?error=notset");
            }

            if (paramExists($public) && $public === "true") {
                $private = false;
            }

            $canBeCommented = post("canBeCommented");

            if (paramExists($canBeCommented) && $canBeCommented === "true") {
                $canBeCommented = true;
            } else {
                $canBeCommented = false;
            }


            $quiz = new Quiz();
            $quiz->save();

            if ($this->isFileUploaded()) {
                $file = $_FILES['quizFile'];
                $fileName = $file['name'];
                $array = explode('.', $fileName);
                $ext = end($array);
                $tempFileName = $file['tmp_name'];

                if ($ext !== 'qref') {
                    redirect("./quiz-new?error=wrong-file-ext");
                }

                $lines = file($tempFileName);
            } else {
                $lines = explode("\n", $qref);
            }

            foreach ($lines as $line) {

                if (substr($line, 0, 1) === '#') continue; //komentar

                $question = new Question();
                $question->quizId = $quiz->getPrimaryKey();
                $question->save();

                $parts = preg_split('/([:=])/', $line);
                $questionParts = explode('{', $parts[0]);
                $questionPart = trim($questionParts[0]);
                $questionType = substr(trim($questionParts[1]), 0, 1);
                $allAnswers = array_map('trim', explode(',', $parts[1]));
                $correntAnswers = array_map('trim', explode(',', $parts[2]));

                foreach ($allAnswers as $answer) {
                    $choice = new Choice();
                    $choice->questionId = $question->getPrimaryKey();
                    $choice->text = $answer;
                    if (in_array($answer, $correntAnswers)) {
                        $choice->correct = true;
                    }
                    $choice->save();
                }

                $question->text = $questionPart;
                $question->type = $questionType;
                $question->type = $questionType;
                if ($questionType == "3") {
                    $question->correctTextAnswer = $correntAnswers[0];
                }
                $question->save();
            }
            $quiz->name = $name;
            $quiz->description = $description;
            $quiz->creatorId = userID();
            $quiz->canBeCommented = $canBeCommented;
            $quiz->private = $private;
            $quiz->uniqId = uniqid();
            $quiz->save();

            redirect("./");

        } else {

            $navbar = new NavbarView();

            $page = new TemplateEngine("src/View/pages/quiz_create.html");
            $page->addParam("navbar", $navbar->getHtml());

            $errorMsg = get("error");
            if ($errorMsg === "notset") {
                $errorView = new ErrorView("Some field are empty.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "wrong-file-ext") {
                $errorView = new ErrorView("Wrong file extension! Only .qref allowed.");
                $page->addParam("error", $errorView);
            }

            echo $page->getHtml();
        }
    }

    private function isFileUploaded(): bool
    {
        return file_exists($_FILES['quizFile']['tmp_name']) && is_uploaded_file($_FILES['quizFile']['tmp_name']);
    }

}