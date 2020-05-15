<?php


namespace src\View;


use src\model\Choice;
use src\model\Question;

class EditQuestionView implements View
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    function getHtml():string
    {
        $qid = $this->question->id;
        $choices = Choice::getBy('questionId', $qid);

        $choiceViews = [];
        foreach ($choices as $choice) {

            if ($choice->correct || $this->question->type === '3') {
                $choiceView = new ChoiceView($choice, $this->question, true);
            } else {
                $choiceView = new ChoiceView($choice, $this->question, false);
            }
            $choiceViews[] = $choiceView;
        }
        $q = new QuestionView($this->question, $choiceViews);
        return $q->getHtml();

    }
}