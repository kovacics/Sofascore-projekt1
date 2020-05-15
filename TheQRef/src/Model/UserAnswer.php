<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class UserAnswer extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "useranswer";
    }

    public static function getColumns(): array
    {
        return ["questionId", "textAnswer", "quizPlayId"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }

}