<?php


namespace src\View;


use src\model\Choice;
use src\model\Question;
use src\template\TemplateEngine;

class QuestionResultView implements View
{
    private Question $q;
    private string $correctText;
    private bool $isCorrect;
    private int $number;
    private $userAnswer;


    public function __construct(Question $q, bool $isCorrect, string $correctText, int $number, $userAnswer)
    {
        $this->q = $q;
        $this->correctText = $correctText;
        $this->isCorrect = $isCorrect;
        $this->number = $number;
        $this->userAnswer = $userAnswer;
    }


    function getHtml(): string
    {
        $questionResult = new TemplateEngine("src/View/components/question_result.html");
        $choices = Choice::getBy("questionId", $this->q->id);

        $choiceViews = [];
        foreach ($choices as $c) {
            if($this->q->type === '3'){
                $choiceViews[] = new ChoiceView($c, $this->q, true, $this->userAnswer);
            }else{
                $choiceIds = (array) $this->userAnswer;

                if(in_array($c->id, $choiceIds)){
                    $choiceViews[] = new ChoiceView($c, $this->q, true);
                }
                else{
                    $choiceViews[] = new ChoiceView($c, $this->q, false);
                }
            }
        }

        $questionView = new QuestionView($this->q, $choiceViews);

        $questionResult->addParam("question", $questionView);
        $questionResult->addParam("number", $this->number);
        $questionResult->addParam("result", $this->isCorrect ? "correct" : "wrong");
        $questionResult->addParam("correct", __($this->correctText));

        return $questionResult->getHtml();
    }
}