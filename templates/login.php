<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <H1>Bank</H1>
        <form method="post">
        <?= csrf_field() ?> //Egentligen inget skydd för användaren inte har en session än.
        <input type="text" name="card_number" placeholder="Cardnumber">
        <input type="password" name="pin" placeholder="PIN">
        <input type="hidden" name="action" value="login">
        <button type="submit">Log in</button>
        <?php 
        if ($error !== '') 
        {
            echo htmlspecialchars($error);
        }
         ?>
        </form>
        </div>
    </div>
</body>
</html>