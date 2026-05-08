<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if ($selectedAccount): ?>
        
        <h2><?= $selectedAccount->getAccountType() ?></h2>
        <p>Balance: <?= $selectedAccount->getBalance() ?> </p>

    <form method="post">
            <label for="">How much do you want to transfer</label>
            <input type="hidden" name="action" value="transfer">
            <input type="text" name="amount">
            <select name="to_account_id" id="">
                <?php foreach ($userAccounts as $account):  ?>
                    <?php if ($account->getId() !== $selectedAccount->getId()): ?>
                <option value="<?= $account->getId() ?>"><?= $account->getAccountType() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <button class="button" type="submit">Transfer!</button>
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
