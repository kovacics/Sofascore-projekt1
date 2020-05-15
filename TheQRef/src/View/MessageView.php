<?php


namespace src\View;


use src\template\TemplateEngine;

class MessageView implements View
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    function getHtml(): string
    {
        $page = new TemplateEngine("src/View/pages/message.html");
        $navbar = new NavbarView();

        $page->addParam("navbar", $navbar->getHtml());
        $page->addParam("message", $this->message);
        return $page->getHtml();
    }
}