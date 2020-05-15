<?php


namespace src\View;


use src\template\TemplateEngine;

class ErrorView implements View
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }


    function getHtml():string
    {
        $error = new TemplateEngine("src/View/components/error.html");
        $error->addParam("message", $this->message);
        return $error->getHtml();
    }
}