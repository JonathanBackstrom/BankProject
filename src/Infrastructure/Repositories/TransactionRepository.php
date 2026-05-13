<?php

declare(strict_types=1);

class TransactionRepository implements TransactRepositoryInterface
{
private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function deposit(int $account_id, float $amount) : void
    {
        $this->pdo->beginTransaction();
        try
        {
            $stmt = $this->pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $account_id]);
            $stmt = $this->pdo->prepare("INSERT INTO transactions (to_account_id, type, amount) VALUES (?, ?, ?)");
            $stmt->execute([$account_id, 'deposit', $amount]);
            
            $this->pdo->commit();
            }
            catch (Exception $ex)
            {
                $this->pdo->rollBack();
                throw $ex;
            }
    }

    public function withdraw(int $account_id, float $amount) : void
    {
        $this->pdo->beginTransaction();
        try
        {
            $stmt = $this->pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
            $stmt->execute([$amount, $account_id]);
            $stmt = $this->pdo->prepare("INSERT INTO transactions (from_account_id, type, amount) VALUES (?, ?, ?)");
            $stmt->execute([$account_id, 'withdraw', $amount]);
            $this->pdo->commit();
        }
        catch (Exception $ex)
        {
            $this->pdo->rollBack();
            throw $ex;
        }
    }

    public function transfer(int $from_account_id,int $to_account_id, float $amount) : void
    {
        $this->pdo->beginTransaction();
        try
        {
        $stmt = $this->pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $from_account_id]);
        $stmt = $this->pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $to_account_id]);
        $stmt = $this->pdo->prepare("INSERT INTO transactions (from_account_id, to_account_id , type, amount) VALUES (?, ?, ?, ?)");
        $stmt->execute([$from_account_id, $to_account_id, 'transfer', $amount]);
        $this->pdo->commit();
        }
        catch (Exception $ex)
        {
            $this->pdo->rollBack();
            throw $ex;
        }
    }

    public function findAll(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transactions");
        $stmt->execute();

        $transactList = $stmt->fetchAll();
        if (!$transactList) return null;

        $transactions = [];
        foreach ($transactList as $list)
            {
                $transactions[] = new Transaction(
                    $list['id'],
                    (int)$list['from_account_id'],
                    (int)$list['to_account_id'],
                    $list['type'],
                    (float)$list['amount'],
                    new DateTime($list['created_at'])
                );
            }

            return $transactions;
    }
}

?>