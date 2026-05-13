<?php

declare(strict_types=1);

class Database
{
    private PDO $pdo;
    public function __construct($config)
    {
        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4'; //connection string

        $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass']); //Lägger upp variabel som har connection för databasen
        
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Kastar execpetion vid databas fel
    }

    //metod som expnorerar anslutningen
    public function getConnection() : PDO {
        return $this->pdo;
    }
}

?>