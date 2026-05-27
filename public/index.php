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
$adminService = new AdminService($userRepo, $accountRepo, $transactRepo);

//sätter lokalt, hade varit i php.ini för produktion, satt lifetime till en timme, men är man idle i 10 minuter kastas man ut.
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);

// Säkrar upp så att sessionens id inte läcker genom url.
ini_set('session.use_only_cookies', '1');
// Skyddat mot attacker och tillåter bara sessions id:n som den själv skapat.
ini_set('session.use_strict_mode', '1');

session_start();

$error = "";
$success = "";
$success = $_GET['success'] ?? '';

//skyddar alla sidor från obehörig åtkomst
$page = $_GET['page'] ?? 'login';

//Undantar login för att inte hamna i login-loop, undantar logout för att kunna komma åt lougout logiken
if ($page !== 'login' && $page !== 'logout')
    {
        require_login();
    }

// kontrollerar så det finns ett user_id i sessionen, hämtar sedan accounts bundet till user_id.
// Sätter även selectedAccount till null som standard för att man än inte valt konto.
if (isset($_SESSION['user_id']))
    {
        $user_id = $_SESSION['user_id'];
        $userAccounts = $accountService->getAccounts($user_id) ?? [];
        
        $selectedAccount = null;
    }

// När användaren klickar på ett konto så sparas id på kontot i session
if (isset($_GET['account_id']))
    {
        $_SESSION['account_id'] = (int) $_GET['account_id'];
    }

// Hämtar kontot från databasen och sätter det till selectedAccount
if (isset($_SESSION['account_id']))
    {   
        $selectedAccount = $accountService->getAccountById($_SESSION['account_id']);
        
        // kontrollerar så att kontot man hämtar verkligen tillhör användaren.
        if ($selectedAccount?->getUserId() !== $user_id)
            {
                $selectedAccount = null;
                unset($_SESSION['account_id']);
            }
    }

// Kontrollerar att POST skickas och läser då in datan som skickats
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $card = $_POST["card_number"] ?? '';
        $pin = $_POST["pin"] ?? '';
        $action = $_POST["action"] ?? '';
    
        // login logik, använder 'action' för att visa vilket formulär som skickats
        if ($action === 'login')
            {
                //validering för log in
            if ($card === '' || $pin === '')
            {
                $error = "Please enter correct number/pin";
            }
            //loggas in med metod.
        $result = $authService->logIn($card, $pin);
       
            //beroende på roll vart man hamnar
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

    // Deposit logik, använder 'action' för att visa vilket formulär som skickats
    // Med isset validering mot selectAccount för att det inte ska krascha när man hämtar id
    // Använder mig av flash för att få ett 'pop up' meddelande som försvinner när man går vidare.
    if ($action === 'deposit' && isset($selectedAccount))
        {
            csrf_verify();
            $amount = (float) $_POST['amount'] ?? 0;
            $account_id = $selectedAccount->getId();
            if ($transactService->deposit($account_id, $amount))
                {
                    $_SESSION['flash'] = 'Deposit was successful!';
                    header('Location: ?page=deposit');
                    exit;
                }
                else
                    {
                        $error = "Invalid amount";
                    }
            }
            

    // Withdraw logik, använder 'action' för att visa vilket formulär som skickats
    // Med isset validering mot selectAccount för att det inte ska krascha när man hämtar id
    // Kontrollerar med if sats mot withdraw() ifall det lyckades eller inte.
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
    
    //transfer logik, använder 'action' för att visa vilket formulär som skickats
    // Med isset validering mot selectAccount för att det inte ska krascha när man hämtar id
    // Kontrollerar med if sats mot tranfser() ifall det lyckades eller inte.
    if ($action === 'transfer' && isset($selectedAccount))
        {
            csrf_verify();
            $amount = (float) $_POST['amount'] ?? 0;
            $to_account_id = (int) $_POST['to_account_id'] ?? 0;
            $from_account_id = $selectedAccount->getId();
            $balance = (float) $selectedAccount->getBalance();

            //validerar så att kontot stämmer överrens med userId, alltså att man bara kan överföra till sina egnan konton
            $validAccount = false;
            foreach ( $userAccounts as $account)
                {
                    if ($account->getId() === $to_account_id)
                        {
                            $validAccount = true;
                            break;
                        }
                }
            if (!$validAccount)
                {
                    $error = "Invalid account";
                }
                else
                {
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
}

//Routing
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
    'accounts' => require __DIR__ . '/../templates/Admin/admin-accounts.php',
    default => require __DIR__ . '/../templates/login.php',
};
?>