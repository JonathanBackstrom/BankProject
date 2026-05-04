<?php
declare(strict_types=1);
define('BASE_URL', '/BankProject/public');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $card = $_POST["card_number"] ?? '';
    $pin = $_POST["pin"] ?? '';
}

$error = "";
if ($card === '' || $pin === '')
    {
        $error = "Please enter correct number/pin";
    }

$page = $_GET['page'] ?? 'login';

match($page) {
    'login' => require __DIR__ . '/../templates/login.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>