<?php

declare(strict_types=1);

class UserRepository
{
    private PDO $pdo;
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUser (string $card_number)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE card_number = ?");
        $stmt->execute([$card_number]);
        return $stmt->fetch(); 
    }

}

?>