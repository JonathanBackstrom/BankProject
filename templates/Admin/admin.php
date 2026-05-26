<?php 
require_login();
require_role('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPage</title>
</head>
<body>
    <h1>Admin panel - Welcome <?= htmlspecialchars((string)$_SESSION['user_name']) ?>!</h1>
    <a href="?page=logout">Logga ut</a>
    <?php require __DIR__ . '/../Admin/admin-navbar.php'; ?>
</body>
</html>