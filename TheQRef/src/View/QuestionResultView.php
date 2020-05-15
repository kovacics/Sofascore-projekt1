<?php


namespace src\View;


use src\model\Question;
use src\template\TemplateEngine;

class QuestionResultView implements View
{
    private Question $q;
    private string $correctText;
    private bool $isCorrect;
    private string $yourText;
    private int $number;


    public function __construct(Question $q, bool $isCorrect, string  $correctText, string $yourText, int $number)
    {
        $this->q = $q;
        $this->correctText = $correctText;
        $this->isCorrect = $isCorrect;
        $this->yourText = $yourText;
        $this->number = $number;
    }


    function getHtml(): string
    {
        $questionResult = new TemplateEngine("src/View/components/question_result.html");
        $questionView = new QuestionView($this->q);

        $questionResult->addParam("question", $questionView);
        $questionResult->addParam("number", $this->number);
        $questionResult->addParam("result", $this->isCorrect ? "correct" : "wrong");
        $questionResult->addParam("correct", __($this->correctText));
        $questionResult->addParam("your", __($this->yourText));

        return $questionResult->getHtml();
    }
}