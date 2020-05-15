<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;

class Quiz extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "quiz";
    }

    public static function getColumns(): array
    {
        return ["name", "description", "private", "canBeCommented", "uniqId", "creatorId"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }


}