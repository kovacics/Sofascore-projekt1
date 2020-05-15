<?php


namespace src\View;


use src\model\Choice;
use src\model\Question;
use src\template\TemplateEngine;

class QuestionView implements View
{
    private Question $question;
    private ?array $choiceViews;

    public function __construct(Question $question, ?array $choiceViews = null)
    {
        $this->question = $question;
        $this->choiceViews = $choiceViews;
    }

    function getHtml(): string
    {
        $qid = $this->question->id;
        $choices = Choice::getBy('questionId', $qid);

        if ($this->choiceViews === null) {
            $this->choiceViews = [];
            foreach ($choices as $choice) {
                $choiceView = new ChoiceView($choice, $this->question);
                $this->choiceViews[] = $choiceView;
            }
        }

        $fullQuestion = new TemplateEngine("src/View/components/question.html");
        $fullQuestion->addParam("question", __($this->question->text));
        $fullQuestion->addParam("choices", $this->choiceViews);

        return $fullQuestion->getHtml();
    }
}