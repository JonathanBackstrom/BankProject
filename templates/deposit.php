<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit</title>
</head>
<body>
    <?php if ($selectedAccount): ?>
    <h2><?= $selectedAccount->getAccountType() ?></h2>
    <p>Balance: <?= $selectedAccount->getBalance() ?> </p>
    <form method="post">
        <?= csrf_field() ?>
        <label for="">How much do you want to deposit</label>
        <input type="hidden" name="action" value="deposit">
        <input type="text" name="amount">
        <button class="button" type="submit">Deposit!</button>
    </form>
        <?php if (isset($_SESSION['flash'])): ?>
        <p><?= htmlspecialchars($_SESSION['flash']) ?></p>
        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
     <?php else: ?>
        <p>No account selected. Go back to home and selcet an account.</p>
        <?php endif; ?>
    <?php require __DIR__ . '/../templates/navbar.php' ?>

</body>
</html>