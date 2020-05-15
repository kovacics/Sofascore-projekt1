<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class QuizPlay extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "quizplay";
    }

    public static function getColumns(): array
    {
        return ["quizId", "userId", "score"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }


}