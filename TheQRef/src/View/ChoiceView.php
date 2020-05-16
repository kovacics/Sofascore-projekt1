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
    private ?string $textPlaceholder;

    public function __construct(Choice $choice, Question $question, bool $checked = false, ?string $textPlaceholder = null)
    {
        $this->choice = $choice;
        $this->question = $question;
        $this->checked = $checked;
        $this->textPlaceholder = $textPlaceholder;
    }


    function getHtml():string
    {
        if($this->checked){
            $ct = new TemplateEngine("src/View/components/choice_correct.html");
            if($this->textPlaceholder !== null){
                $placeholder = $this->textPlaceholder;
            } else{
                $placeholder = $this->question->correctTextAnswer;
            }
            $ct->addParam("textAnswer", __($placeholder));
        } else{
            $ct = new TemplateEngine("src/View/components/choice.html");
        }
        $ct->addParam("q", $this->question);
        $ct->addParam("c", $this->choice);
        return $ct->getHtml();
    }
}