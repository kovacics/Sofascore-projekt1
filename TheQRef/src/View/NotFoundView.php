<?php


namespace src\View;


use src\template\TemplateEngine;

class NotFoundView implements View
{
    function getHtml(): string
    {
        $messageView = new MessageView("Not found.");
        return $messageView->getHtml();
    }
}