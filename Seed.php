<?php

 $config =  require 'config.php';
 require 'src/Infrastructure/Database/Database.php';

 $database = new Database($config);

 $connection = $database->getConnection();

 $stmt = $connection->prepare("INSERT INTO users (card_number, pin_hash, name, role) VALUES (?, ?, ?, ?)");
 $stmt->execute(['1234', password_hash('1111', PASSWORD_BCRYPT), 'Anna', 'user']);
 $idAnna = $connection->lastInsertId();

 $stmt->execute(['4321', password_hash('admin', PASSWORD_BCRYPT), 'Jonathan', 'admin']);
 $idJonathan = $connection->lastInsertId();

 $stmt = $connection->prepare("INSERT INTO accounts (user_id, balance) VALUES (?, ?)");
 $stmt->execute([$idAnna, 0]);
 $stmt->execute([$idJonathan, 0]);
 ?>