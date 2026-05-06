<?php

 $config =  require 'config.php';
 require 'src/Infrastructure/Database/Database.php';

 $database = new Database($config);

 $connection = $database->getConnection();

 $connection->exec("SET FOREIGN_KEY_CHECKS = 0");
 $connection->exec("DELETE FROM transactions");
 $connection->exec("DELETE FROM accounts");
 $connection->exec("DELETE FROM users");
 $connection->exec("SET FOREIGN_KEY_CHECKS = 1");

 $stmt = $connection->prepare("INSERT INTO users (card_number, pin_hash, name, role) VALUES (?, ?, ?, ?)");
 $stmt->execute(['1234', password_hash('1111', PASSWORD_BCRYPT), 'Anna', 'user']);
 $idAnna = $connection->lastInsertId();

 $stmt->execute(['4321', password_hash('admin', PASSWORD_BCRYPT), 'Jonathan', 'admin']);
 $idJonathan = $connection->lastInsertId();

 $stmt = $connection->prepare("INSERT INTO accounts (user_id, account_type, balance) VALUES (?, ?, ?)");
 $stmt->execute([$idAnna, 'ISK', 1000]);
 $stmt->execute([$idAnna, 'Bankaccount', 2000]);
 $stmt2 = $connection->prepare("INSERT INTO accounts (user_id, balance) VALUES (?, ?)");
 $stmt2->execute([$idAnna, 0]);
 $stmt2->execute([$idJonathan, 0]);
 ?>