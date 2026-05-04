<?php
declare(strict_types=1);
define('BASE_URL', '/BankProject/public');

require 'src/Infrastructure/Database/Database.php';
require 'src/Infrastructure/Repositories/UserRepository.php';
require 'src/Application/Services/AuthService.php';
$config = require 'config.php';

$database = new Database($config);
$pdo = $database->getConnection();
$userRepo = new UserRepository($pdo);
$authService = new AuthService($userRepo);

session_start();

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $card = $_POST["card_number"] ?? '';
    $pin = $_POST["pin"] ?? '';
    
    if ($card === '' || $pin === '')
        {
            $error = "Please enter correct number/pin";
        }
        
    $authService->logIn($card, $pin);
}
$page = $_GET['page'] ?? 'login';

match($page) {
    'login' => require __DIR__ . '/../templates/login.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>