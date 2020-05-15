<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class Question extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "question";
    }

    public static function getColumns(): array
    {
        return ["quizId", "text", "type", "correctTextAnswer"];
    }


    public static function getPrimaryKeyColumn()
    {
        return "id";
    }


}