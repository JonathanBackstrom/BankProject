<?php
declare(strict_types=1);
define('BASE_URL', '/BankProject/public');

//Interfaces
require __DIR__ . '/../src/Domain/Interfaces/UserRepositoryInterface.php';
require __DIR__ . '/../src/Domain/Interfaces/AccountRepositoryInterface.php';
require __DIR__ . '/../src/Domain/Interfaces/TransactRepositoryInterface.php';


//Entities
require __DIR__ . '/../src/Domain/Entities/User.php';
require __DIR__ . '/../src/Domain/Entities/Account.php';
require __DIR__ . '/../src/Domain/Entities/Transaction.php';
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
require __DIR__ . '/../src/Application/Services/AdminService.php';
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
$adminService = new AdminService($userRepo);

//sätter lokalt, hade varit i php.ini för produktion
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

session_start();

$error = "";
$success = "";
$success = $_GET['success'] ?? '';

$page = $_GET['page'] ?? 'login';
if ($page !== 'login' && $page !== 'logout')
    {
        require_login();
    }

if (isset($_SESSION['user_id']))
    {
        $user_id = $_SESSION['user_id'];
        $userAccounts = $accountService->getAccounts($user_id) ?? [];
        
        $selectedAccount = null;
        
    }

if (isset($_GET['account_id']))
    {
        $_SESSION['account_id'] = (int) $_GET['account_id'];
    }
if (isset($_SESSION['account_id']))
    {   
        $selectedAccount = $accountService->getAccountById($_SESSION['account_id']);
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $card = $_POST["card_number"] ?? '';
        $pin = $_POST["pin"] ?? '';
        $action = $_POST["action"] ?? '';
    
        // login logik
        if ($action === 'login') {
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

    // deposit logik
    if ($action === 'deposit' && isset($selectedAccount))
        {
            csrf_verify();
            $amount = (float) $_POST['amount'] ?? 0;
            $account_id = $selectedAccount->getId();
            $transactService->deposit($account_id, $amount);
            $_SESSION['flash'] = 'Deposit was successful!';
            header('Location: ?page=deposit');
            exit;
            }
            

    // withdraw logik
    if ($action === 'withdraw' && isset($selectedAccount))
        {
            csrf_verify();
            $amount = (float) $_POST['amount'] ?? 0;
            $account_id = $selectedAccount->getId();
            $balance = (float) $selectedAccount->getBalance();
            if($transactService->withdraw($account_id, $amount, $balance))
                {
                    $_SESSION['flash'] = 'Withdraw was successful!';
                    header('Location: ?page=withdraw');
                    exit;
                }
            else
                {
                    $error = "Insufficient funds";
                }
        }
    
    //transfer logik
    if ($action === 'transfer' && isset($selectedAccount))
        {
            csrf_verify();
            $amount = (float) $_POST['amount'] ?? 0;
            $to_account_id = (int) $_POST['to_account_id'] ?? 0;
            $from_account_id = $selectedAccount->getId();
            $balance = (float) $selectedAccount->getBalance();
            if ($transactService->transfer($from_account_id, $to_account_id, $amount, $balance))
                {
                    $_SESSION['flash'] = 'Transfer was successful!';
                    header('Location: ?page=transfer');
                    exit;
                }
            else
                {
                    $error = "Insufficient funds";
                }
            
        }
}

match($page) {
    'login' => require __DIR__ . '/../templates/login.php',
    'dashboard' => require __DIR__ . '/../templates/dashboard.php',
    'admin' => require __DIR__ . '/../templates/Admin/admin.php',
    'logout' => require __DIR__ . '/../templates/logout.php',
    'account' => require __DIR__ . '/../templates/account.php',
    'deposit' => require __DIR__ . '/../templates/deposit.php',
    'withdraw' => require __DIR__ . '/../templates/withdraw.php',
    'transfer' => require __DIR__ . '/../templates/transfer.php',
    'users' => require __DIR__ . '/../templates/Admin/admin-users.php',
    'transaction' => require __DIR__ . '/../templates/Admin/admin-transactions.php',
    'accounts' => require __DIR__ . '/../templates/Admin/admin-account.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>