<?php

declare(strict_types=1);

class AuthService
{
    private UserRepositoryInterface $repo;
    function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function logIn(string $card_number, string $pin)
    {
        $user = $this->repo->getUser($card_number);
        if ($user === null)
        {
            return false;
        }
        if(!password_verify($pin, $user->getPin()))
        {
            return false;
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['role'] = $user->getRole();
        return true;
    }
}

?>