<?php

namespace src\db;

use PDO;

class DBPool
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function getPDO(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO("mysql:dbname=qrefdb", "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]);
            } catch (\PDOException $e) {
                var_dump($e);
                die();
            }
        }
        return self::$pdo;
    }

}