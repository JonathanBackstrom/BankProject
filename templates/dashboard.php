<?php
declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboardPage</title>
</head>
<body>
    <div class="wrapper">
    <h1>Welcome <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
    <a href="?page=logout">Logga ut</a>
    <?php require __DIR__ . '/../templates/navbar.php'; ?>
    
    <?php foreach ($userAccounts as $account): ?>
        <div class="account-item">
        <a href="?page=account&account_id=<?= htmlspecialchars((string)$account->getId()) ?>">
        <?= htmlspecialchars((string)$account->getAccountType()) ?>
        </a>
        </div>
    <?php endforeach; ?>
    
    </div>
</body>
</html>