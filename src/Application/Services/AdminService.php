<?php

declare(strict_types=1);

class AdminService
{

    private UserRepositoryInterface $userRepo;
    private AccountRepositoryInterface $accountRepo;
    private TransactRepositoryInterface $transactRepo;

    function __construct(UserRepositoryInterface $userRepo, AccountRepositoryInterface $accountRepo, TransactRepositoryInterface $transactRepo)
    {
        $this->userRepo = $userRepo;
        $this->accountRepo = $accountRepo;
        $this->transactRepo = $transactRepo;
    }

    public function getAllUsers()
    {
        return $this->userRepo->findAll();
    }

    public function getAllAccounts()
    {
        return $this->accountRepo->findAll();
    }

    public function getAllTransactions()
    {
        return $this->transactRepo->findAll();
    }
}

?>