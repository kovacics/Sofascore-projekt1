<?php


namespace src\View;


use src\template\TemplateEngine;

class ForbiddenView implements View
{
    function getHtml(): string
    {
        $messageView = new MessageView("Forbidden!");
        return $messageView->getHtml();
    }
}