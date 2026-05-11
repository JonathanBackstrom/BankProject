<?php

interface UserRepositoryInterface
{
    public function getUser(string $card_number): ?User;

    public function findAll() : ?array;
}



?>