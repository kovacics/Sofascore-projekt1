<?php


namespace src\controller;


use src\View\NotFoundView;

class ErrorController
{
    public function show()
    {
        $notfound = new NotFoundView();
        echo $notfound->getHtml();
    }

}