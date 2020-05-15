<?php

namespace src\controller;

use src\model\User;
use src\template\TemplateEngine;
use src\View\ErrorView;
use src\View\NavbarView;

session_start();

class RegistrationController
{

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $firstName = post('firstName');
            $lastName = post('lastName');
            $dob = post('dob');
            $email = post('email');
            $password = post('password');
            $passwordConfrim = post('passwordConfirm');

            if (!(paramExists($firstName) && paramExists($lastName) && paramExists($dob) &&
                paramExists($email) && paramExists($password) && paramExists($passwordConfrim))) {
                redirect("./register?error=notset");
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                redirect("./register?error=email");
            }

            if ($password !== $passwordConfrim) {
                redirect("./register?error=password-mismatch");
            }

            if (strlen($password) < 5) {
                redirect("./register?error=password-length");
            }

            $user = User::getBy("email", $email, User::FETCH_ONE);;

            if ($user !== null) {
                redirect("./register?error=exist");
            }

            $user = new User();
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->dob = $dob;
            $user->password = password_hash($password, PASSWORD_BCRYPT);
            $user->save();
            redirect('./');

        } else {
            $navbar = new NavbarView();

            $page = new TemplateEngine("src/View/pages/register.html");
            $page->addParam("navbar", $navbar->getHtml());


            $errorMsg = get("error");
            if ($errorMsg === "notset") {
                $errorView = new ErrorView("Some field are empty.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "exist") {
                $errorView = new ErrorView("Email already used, try other or login.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "password-mismatch") {
                $errorView = new ErrorView("Password and confirm password are not the same.");
                $page->addParam("error", $errorView);
            } elseif ($errorMsg === "password-length") {
                $errorView = new ErrorView("Password must have at least 5 characters.");
                $page->addParam("error", $errorView);
            }elseif ($errorMsg === "email") {
                $errorView = new ErrorView("Please enter valid email address.");
                $page->addParam("error", $errorView);
            }

            echo $page->getHtml();
        }
    }
}