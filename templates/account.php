<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if ($selectedAccount): ?>
    <h2><?= htmlspecialchars($selectedAccount->getAccountType()) ?></h2>
    <p>Balance: <?= htmlspecialchars($selectedAccount->getBalance()) ?> </p>
    <?php else: ?>
        <p>No account selected. Go back to home and selcet an account.</p>
        <?php endif; ?>
        <?php require __DIR__ . '/../templates/navbar.php' ?>

    
        
</body>
</html>

