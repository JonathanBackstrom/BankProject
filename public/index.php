<?php

declare(strict_types=1);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
}

$page = $_GET['page'] ?? 'login';

match($page) {
    'login' => require __DIR__ . '/../templates/login.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>