<?php
declare(strict_types=1);
class Transaction
{
    private int $id;
    private ?int $from_account_id;
    private ?int $to_account_id;
    private string $type;
    private float $amount;
    private Datetime $created_at;

    function __construct(int $id, ?int $from_account_id, ?int $to_account_id, string $type, float $amount, DateTime $created_at)
    {
        $this->id = $id;
        $this->from_account_id = $from_account_id;
        $this->to_account_id = $to_account_id;
        $this->type = $type;
        $this->amount = $amount;
        $this->created_at = $created_at;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getFromAccountId() : ?int
    {
        return $this->from_account_id;
    }

    public function getToAccountId() : ?int
    {
        return $this->to_account_id;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->created_at;
    }
}

?>