<?php

namespace src\controller;

use src\model\User;
use src\route\Route;
use src\template\TemplateEngine;
use src\View\ErrorView;
use src\View\NavbarView;

if (!isset($_SESSION)) {
    session_start();
}

class LoginController
{

    public function login()
    {
        if (isLoggedIn()) {
            redirect("./");

        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $route = Route::get("login")->generate();

                $email = post('email');
                $password = post('password');

                if (!(paramExists($email) && paramExists($password))) {
                    redirect("$route?error=notset");
                }

                $user = User::getBy("email", $email, User::FETCH_ONE);

                if ($user === null) {
                    redirect("$route?error=wrongInputs");
                }
                if (!password_verify($password, rtrim($user->password))) {
                    redirect("$route?error=wrongInputs");
                }

                $_SESSION['userID'] = (int)$user->id;

                redirect("./");

            } else {
                $navbar = new NavbarView();

                $page = new TemplateEngine("src/View/pages/login.html");
                $page->addParam("navbar", $navbar->getHtml());

                $errorMsg = get("error");
                if ($errorMsg === "notset") {
                    $errorView = new ErrorView("Some field are empty.");
                    $page->addParam("error", $errorView);
                } elseif ($errorMsg === "wrongInputs") {
                    $errorView = new ErrorView("Wrong email or password.");
                    $page->addParam("error", $errorView);
                }

                echo $page->getHtml();
            }
        }
    }
}