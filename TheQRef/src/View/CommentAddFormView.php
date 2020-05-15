<?php


namespace src\View;


use src\template\TemplateEngine;

class CommentAddFormView implements View
{
    private int $quizId;

    public function __construct(int $quizId)
    {
        $this->quizId = $quizId;
    }

    function getHtml():string
    {
        $form = new TemplateEngine("src/View/components/add_comment_form.html");
        $form->addParam("quizId", $this->quizId);

        return $form->getHtml();
    }
}