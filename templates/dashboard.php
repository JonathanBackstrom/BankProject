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
    <h1>Welcome <?= $_SESSION['user_name'] ?>!</h1>
    <a href="?page=logout">Logga ut</a>
    <?= $loggedInUser['balance'] ?>
    <?php require __DIR__ . '/../templates/navbar.php'; ?>
    <form method="post">
        <label for="">How much do you want to deposit</label>
        <input type="hidden" name="action" value="deposit">
        <input type="text" name="amount">
        <button class="button" type="submit">Deposit!</button>
    </form>
    <form method="post">
        <label for="">How much do you want to withdraw</label>
        <input type="hidden" name="action" value="withdraw">
        <input type="text" name="amount">
        <button class="button" type="submit">Withdraw!</button>
    </form>
    <form method="post">
        <label for="">How much do you want to transfer</label>
        <input type="hidden" name="action" value="transfer">
        <input type="text" name="amount">
        <button class="button" type="submit">Transfer!</button>
    </form>
    <?php if ($error !== ''): ?>
    <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success === 'deposit'): ?>
    <p>Deposit was successful!</p>
    <?php elseif ($success === 'withdraw'): ?>
    <p>Withdrawal was successful!</p>
    <?php endif; ?>
</body>
</html>