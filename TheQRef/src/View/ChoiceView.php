<?php


namespace src\View;


use src\model\Choice;
use src\model\Question;
use src\template\TemplateEngine;

class ChoiceView implements View
{
    private Choice $choice;
    private Question $question;
    private bool $checked;

    public function __construct(Choice $choice, Question $question, bool $checked = false)
    {
        $this->choice = $choice;
        $this->question = $question;
        $this->checked = $checked;
    }


    function getHtml():string
    {
        if($this->checked){
            $ct = new TemplateEngine("src/View/components/choice_correct.html");
            $ct->addParam("textAnswer", __($this->question->correctTextAnswer));
        } else{
            $ct = new TemplateEngine("src/View/components/choice.html");
        }
        $ct->addParam("q", $this->question);
        $ct->addParam("c", $this->choice);
        return $ct->getHtml();
    }
}