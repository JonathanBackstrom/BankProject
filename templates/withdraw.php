<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
</head>
<body>
    <?php if ($selectedAccount): ?>
        
    <h2><?= $selectedAccount->getAccountType() ?></h2>
    <p>Balance: <?= $selectedAccount->getBalance() ?> </p>
    
    <form  method="post">
        <?= csrf_field() ?>
        <label for="">How much do you want to withdraw</label>
        <input type="hidden" name="action" value="withdraw">
        <input type="text" name="amount">
        <button class="button" type="submit">Withdraw!</button>
    </form>
    <?php if (isset($_SESSION['flash'])): ?>
        <p><?= htmlspecialchars($_SESSION['flash']) ?></p>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
            <?php if ($error !== ''): ?>
            <p><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        <?php else: ?>
        <p>No account selected...</p>
        <?php endif; ?>
        
    <?php require __DIR__ . '/../templates/navbar.php' ?>

</body>
</html>