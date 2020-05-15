<?php

namespace src\model;


use src\model\abstractModel\AbstractDBModel;

class Choice extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "choice";
    }


    public static function getColumns(): array
    {
        return ["questionId", "text", "correct"];
    }


    public static function getPrimaryKeyColumn()
    {
        return "id";
    }
}