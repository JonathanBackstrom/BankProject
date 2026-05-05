<?php

declare(strict_types=1);

class TransactionRepository
{
private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function deposit(int $account_id, float $amount)
    {
        try
        {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $account_id]);
            $stmt = $this->pdo->prepare("INSERT INTO transactions (to_account_id, type, amount) VALUES (?, ?, ?)");
            $stmt->execute([$account_id, 'deposit', $amount]);

            $this->pdo->commit();
        }
        catch (Exception $ex)
        {
            $this->pdo->rollback();
            throw $ex;
        }

        
            

    }
}

?>