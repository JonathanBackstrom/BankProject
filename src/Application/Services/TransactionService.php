<?php

declare(strict_types=1);

class TransactionService
{
    private TransactionRepository $repo;
    function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function deposit(int $account_id, float $amount)
    {
        if ($amount <= 0)
            {
                return false;
            }
        $this->repo->deposit($account_id, $amount);
        return true;
    }

    public function withdraw(int $account_id, float $amount, float $balance)
    {
        if ($balance < $amount)
        {
            return false;
        }
        $this->repo->withdraw($account_id, $amount);
        return true;
    }

    public function transfer(int $from_account_id,int $to_account_id, float $amount, float $balance)
    {
        if ($balance < $amount)
        {
            return false;
        }
        $this->repo->transfer($from_account_id, $to_account_id, $amount);
        return true;
    }
}

?>