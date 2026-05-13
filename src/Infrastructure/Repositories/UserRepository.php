<?php

declare(strict_types=1);

class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUser (string $card_number): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE card_number = ?");
        $stmt->execute([$card_number]);
        
        $row = $stmt->fetch();
        if (!$row) return null;

        return new User(
        $row['id'],
        $row['card_number'],
        $row['pin_hash'],
        $row['name'],
        $row['role'],
        new DateTime($row['created_at'])
        );
    }

    public function findAll(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM  users");
        $stmt->execute();

        $usersList = $stmt->fetchAll();
        if (!$usersList) return null;
        $users = [];
        foreach ($usersList as $list)
            {
                $users[] = new User(
                    $list['id'],
                    $list['card_number'],
                    $list['pin_hash'],
                    $list['name'],
                    $list['role'],
                    new DateTime($list['created_at'])
                    );
            }
        return $users;                    
    }
}

?>