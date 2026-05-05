<?php

declare(strict_types=1);

class AccountRepository
{
private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAccount(int $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }   
}

?>