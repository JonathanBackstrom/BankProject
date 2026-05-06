<?php
declare(strict_types=1);
class Account
{
    private int $id;
    private int $user_id;
    private string $account_type;
    private float $balance;
    private DateTime $created_at;

    function __construct(int $id, int $user_id, string $account_type, float $balance, DateTime $created_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->account_type = $account_type;
        $this->balance = $balance;
        $this->created_at = $created_at;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getUserId() : int
    {
        return $this->user_id;
    }

    public function getAccountType() : string
    {
        return $this->account_type;
    }

    public function getBalance() : float
    {
        return $this->balance;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->created_at;
    }
}

?>