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
        $account = $this->repo->getAccount($user_id);
        if ($account === null)
            {
                return false;
            }
        
        return $account;
    }
}

?>