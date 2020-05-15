<?php

namespace src\model;

use src\model\abstractModel\AbstractDBModel;


class User extends AbstractDBModel
{

    public static function getTable(): string
    {
        return "user";
    }


    public static function getColumns(): array
    {
        return ["firstName", "lastName", "email", "dob", "password"];
    }

    public static function getPrimaryKeyColumn()
    {
        return "id";
    }


}