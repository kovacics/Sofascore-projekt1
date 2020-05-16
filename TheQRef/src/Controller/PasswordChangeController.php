<?php


namespace src\controller;


use src\model\User;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ErrorView;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();

class PasswordChangeController
{

    public function change()
    {

        if (!isLoggedIn()) {
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $route = Route::get("passChange")->generate();


            $password = post('password');
            $passwordConfirm = post('passwordConfirm');
            $newPassword = post('newPassword');

            if (!(paramExists($passwordConfirm) && paramExists($password) && paramExists($newPassword))) {
                redirect("$route?error=notset");
            }


            $user = User::get(userID());

            if ($password !== $passwordConfirm) {
                redirect("$route?error=password-mismatch");
            }

            if (!password_verify($password, rtrim($user->password))) {
                redirect("$route?error=wrongInputs");
            }

            //all good

            $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
            $user->save();

            redirect("./");

        } else {

            $navbar = new NavbarView();
            $page = new TemplateEngine("./src/View/pages/password_change.html");

            $page->addParam("navbar", $navbar->getHtml());

            $errorMsg = get("error");
            if ($errorMsg === "notset") {
                $errorView = new ErrorView("Some field are empty.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "password-mismatch") {
                $errorView = new ErrorView("Password and confirm password are not the same.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "wrongInputs") {
                $errorView = new ErrorView("Wrong password.");
                $page->addParam("error", $errorView);
            }

            echo $page->getHtml();
        }
    }

}