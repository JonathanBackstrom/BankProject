<?php

interface AccountRepositoryInterface
{
    public function getAccount(int $user_id): ?Account;
    
    public function getAccounts(int $user_id): ?array;
    
    public function getAccountById(int $account_id): ?Account;

    public function findAll() : ?array;
}

?>