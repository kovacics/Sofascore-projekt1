<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class Comment extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "comment";
    }

    public static function getColumns(): array
    {
        return ["quizId", "userId", "text"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }
}