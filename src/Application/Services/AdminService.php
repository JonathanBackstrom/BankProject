<?php

declare(strict_types=1);

class AdminService
{

    private UserRepositoryInterface $repo;

    function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAllUsers()
    {
        return $this->repo->findAll();
    }
}

?>