<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class ChoiceUserAnswer extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "choiceuseranswer";
    }


    public static function getColumns(): array
    {
        return ["userAnswerId", "choiceId"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }
}