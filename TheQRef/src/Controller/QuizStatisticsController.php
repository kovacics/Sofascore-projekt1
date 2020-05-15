<?php


namespace src\controller;


use src\model\Quiz;
use src\model\QuizPlay;
use src\template\TemplateEngine;
use src\View\ForbiddenView;
use src\View\NavbarView;

session_start();

class QuizStatisticsController
{

    public function show()
    {

        if (!isLoggedIn()) {
            $forbidden = new ForbiddenView();
            echo $forbidden->getHtml();
            return;
        }

        $navbar = new NavbarView();

        $page = new TemplateEngine("src/View/pages/quiz_stats.html");
        $page->addParam("navbar", $navbar->getHtml());

        $quizzes = Quiz::getAll();

        foreach ($quizzes as $quiz) {
            $quizPlays = QuizPlay::getBy('quizId', $quiz->id);
            $quizPlaysScores = array_map(fn($qp) => $qp->score, $quizPlays);
            if (count($quizPlaysScores) > 0) {
                $quiz->scoreMin = min($quizPlaysScores);
                $quiz->scoreMax = max($quizPlaysScores);
                $quiz->scoreAvg = array_sum($quizPlaysScores) / count($quizPlaysScores);
                $quiz->scoreStddev = $this->stddev($quizPlaysScores);
                $quiz->scoreMedian = $this->median($quizPlaysScores);

            } else {
                $quiz->scoreMin = "-";
                $quiz->scoreMax = "-";
                $quiz->scoreAvg = "-";
                $quiz->scoreStddev = "-";
                $quiz->scoreMedian = "-";
            }
        }

        $page->addParam("quizzes", $quizzes);
        echo $page->getHtml();
    }

    private function median($array)
    {
        $n = count($array);
        if ($n == 0) return null;

        sort($array);
        $middle = floor($n / 2);
        if ($n % 2 === 0) {
            $median = (($array[$middle] + $array[$middle - 1]) / 2);
        } else {
            $median = $array[$middle];
        }
        return $median;
    }

    private function stddev($array)
    {
        $N = count($array);
        if ($N < 2) return 0;

        $mean = array_sum($array) / $N;
        $numerator = 0;
        foreach ($array as $el) {
            $numerator += pow($el - $mean, 2);
        }

        return sqrt($numerator / $N);
    }

}

