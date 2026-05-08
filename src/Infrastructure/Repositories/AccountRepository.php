<?php

declare(strict_types=1);

class AccountRepository implements AccountRepositoryInterface
{
private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAccount(int $user_id) : ?Account
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $row = $stmt->fetch();
        if(!$row) return null;

        return new Account(
            $row['id'],
            $row['user_id'],
            $row['account_type'],
            (float)$row['balance'],
            new DateTime($row['created_at'])
        );
    }

    public function getAccounts(int $user_id) : ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $accountList = $stmt->fetchAll();
        if (!$accountList) return null;
        $accounts = [];
        foreach ($accountList as $list)
            {
                $accounts[] = new Account(
                    $list['id'],
                    $list['user_id'],
                    $list['account_type'],
                    (float)$list['balance'],
                    new DateTime($list['created_at'])
                );
            }
        return $accounts;
    }

    public function getAccountById(int $account_id) : ?Account
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$account_id]);

        $account = $stmt->fetch();
        if (!$account) return null;
        return new Account(
            $account['id'],
            $account['user_id'],
            $account['account_type'],
            (float)$account['balance'],
            new DateTime($account['created_at'])
        );
    }
}

?>