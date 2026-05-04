<?php

declare(strict_types=1);

class AuthService
{
    private UserRepository $repo;
    function __construct(UserRepository $repo)
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
        if(!password_verify($pin, $user['pin_hash']))
        {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
}

?>