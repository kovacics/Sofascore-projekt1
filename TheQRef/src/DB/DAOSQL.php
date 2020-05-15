<?php

namespace src\DB;

use PDO;
use src\model\Quiz;

class DAOSQL
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function getAllByUser($userId)
    {
        $sql = "SELECT quiz.id, quiz.name, COUNT(quiz.id) as number, AVG(quizplay.score) as score FROM quiz JOIN quizplay WHERE quizplay.quizId=quiz.id and userId = $userId GROUP BY quiz.id,quiz.name;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllCreatedByUser($userId)
    {
        return Quiz::getBy("creatorId", $userId);
    }

    function getAllCreatedByOtherUsers($userId)
    {
        $sql = "SELECT *  FROM quiz WHERE creatorId !=  $userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}