<?php

use src\route\DefaultRoute;
use src\route\Route;

Route::register("indexEmpty", new DefaultRoute("", ['controller' => 'IndexController', 'method' => 'index']));
Route::register("index", new DefaultRoute("index", ['controller' => 'IndexController', 'method' => 'index']));
Route::register("register", new DefaultRoute("register", ['controller' => 'RegistrationController', 'method' => 'register']));
Route::register("login", new DefaultRoute("login", ['controller' => 'LoginController', 'method' => 'login']));
Route::register("logout", new DefaultRoute("logout", ['controller' => 'LogoutController', 'method' => 'logout']));
Route::register("profile", new DefaultRoute("profile", ['controller' => 'ProfileController', 'method' => 'profile']));
Route::register("newQuiz", new DefaultRoute("quiz-new", ['controller' => 'QuizCreateController', 'method' => 'create']));
Route::register("quizList", new DefaultRoute("quiz-all", ['controller' => 'QuizListController', 'method' => 'list']));
Route::register("quizPlay", new DefaultRoute("quiz-play", ['controller' => 'QuizPlayController', 'method' => 'play']));
Route::register("passChange", new DefaultRoute("password-change", ['controller' => 'PasswordChangeController', 'method' => 'change']));
Route::register("show-res", new DefaultRoute("show-result", ['controller' => 'QuizEvaluateController', 'method' => 'show']));
Route::register("show-quiz", new DefaultRoute("showQuiz", ['controller' => 'ShowQuizController', 'method' => 'show']));
Route::register("edit-quiz", new DefaultRoute("editQuiz", ['controller' => 'QuizEditController', 'method' => 'edit']));
Route::register("delete-quiz", new DefaultRoute("deleteQuiz", ['controller' => 'QuizDeleteController', 'method' => 'delete']));
Route::register("stats-quiz", new DefaultRoute("quiz-stats", ['controller' => 'QuizStatisticsController', 'method' => 'show']));
Route::register("challenge-quiz", new DefaultRoute("challenge", ['controller' => 'ChallengeController', 'method' => 'difficulty']));
Route::register("challenge-play", new DefaultRoute("challenge-play", ['controller' => 'ChallengeController', 'method' => 'play']));
Route::register("Not found", new DefaultRoute("not-found", ['controller' => 'ErrorController', 'method' => 'show']));
