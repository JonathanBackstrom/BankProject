<?php
declare(strict_types=1);
class User
{
    private int $id;
    private string $card_number;
    private string $pin_hash;
    private string $name;
    private string $role;
    private DateTime $created_at;

    function __construct(int $id, string $card_number, string $pin_hash, string $name, string $role, DateTime $created_at)
    {
        $this->id = $id;
        $this->card_number = $card_number;
        $this->pin_hash = $pin_hash;
        $this->name = $name;
        $this->role = $role;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function getPin(): string
    {
        return $this->pin_hash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }
}

?>