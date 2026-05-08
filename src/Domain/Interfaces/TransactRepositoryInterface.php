<?php

interface TransactRepositoryInterface
{
    public function deposit(int $account_id, float $amount) : void;
    public function withdraw(int $account_id, float $amount) : void;
    public function transfer(int $from_account_id,int $to_account_id, float $amount) : void;

}

?>