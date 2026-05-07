<?php

declare(strict_types=1);

class AccountService
{
    private AccountRepository $repo;
    function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getInfo(int $user_id)
    {
        return $this->repo->getAccount($user_id);
    }

    public function getAccounts(int $user_id)
    {
        return $this->repo->getAccounts($user_id);
    }

    public function getAccountById(int $account_id)
    {
        return $this->repo->getAccountById((int)$account_id);
    }
}

?>