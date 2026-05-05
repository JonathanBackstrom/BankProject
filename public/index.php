<?php
declare(strict_types=1);
define('BASE_URL', '/BankProject/public');

//Infra
require __DIR__ . '/../src/Infrastructure/Database/Database.php';
//Repo
require __DIR__ . '/../src/Infrastructure/Repositories/UserRepository.php';
require __DIR__ . '/../src/Infrastructure/Repositories/AccountRepository.php';
require __DIR__ . '/../src/Infrastructure/Repositories/TransactionRepository.php';
//Service
require __DIR__ . '/../src/Application/Services/AuthService.php';
require __DIR__ . '/../src/Application/Services/AccountService.php';
require __DIR__ . '/../src/Application/Services/TransactionService.php';
//helpers
require __DIR__ . '/../src/Helpers/helpers.php';
//config
$config = require __DIR__ . '/../config.php';;

//infra
$database = new Database($config);
$pdo = $database->getConnection();

//repo
$userRepo = new UserRepository($pdo);
$accountRepo = new AccountRepository($pdo);
$transactRepo = new TransactionRepository($pdo);

//service
$accountService = new AccountService($accountRepo);
$transactService = new TransactionService($transactRepo);
$authService = new AuthService($userRepo);

session_start();

$error = "";

$page = $_GET['page'] ?? 'login';
if ($page !== 'login' && $page !== 'logout') {
    require_login();
}
if (isset($_SESSION['user_id']))
    {
        $user_id = $_SESSION['user_id'];
        $loggedInUser = $accountService->getInfo($user_id);
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $card = $_POST["card_number"] ?? '';
    $pin = $_POST["pin"] ?? '';
    $action = $_POST["action"] ?? '';
    
    if ($action === 'login') {
        // login logik
        if ($card === '' || $pin === '')
        {
            $error = "Please enter correct number/pin";
        }
       
        $result = $authService->logIn($card, $pin);
        if($result)
        {
            if($_SESSION['role'] === 'admin')
                {
                    header('Location: ?page=admin');
                    exit;
                }
                else
                    {
                        header('Location: ?page=dashboard');
                        exit;
                    }

        }
    }
    
    if ($action === 'deposit' && isset($loggedInUser)) {
        // deposit logik
        $amount = (float) $_POST['amount'] ?? 0;
        $account_id = $loggedInUser['id'];
        $transactService->deposit($account_id, $amount);
        header('Location: ?page=dashboard');
        exit;
    }
}

match($page) {
    'login' => require __DIR__ . '/../templates/login.php',
    'dashboard' => require __DIR__ . '/../templates/dashboard.php',
    'admin' => require __DIR__ . '/../templates/Admin/admin.php',
    'logout' => require __DIR__ . '/../templates/logout.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>