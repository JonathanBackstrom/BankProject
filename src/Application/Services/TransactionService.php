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
}

?>