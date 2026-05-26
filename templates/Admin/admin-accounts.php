<?php
declare(strict_types=1);
require_login();
require_role('admin');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-account</title>
</head>
<body>
    <div class="account-item">
         <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>User id</th>
                        <th>Account type</th>
                        <th>Balance</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $accounts = $adminService->getAllAccounts() ?? []; ?>
                        <?php foreach ($accounts as $acc): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$acc->getId()) ?></td>
                                <td><?= htmlspecialchars((string)$acc->getUserId()) ?></td>
                                <td><?= htmlspecialchars($acc->getAccountType()) ?></td>
                                <td><?= htmlspecialchars((string)$acc->getBalance()) ?></td>
                                <td><?= htmlspecialchars($acc->getCreatedAt()->format('Y-m-d')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <?php require __DIR__ . '/../Admin/admin-navbar.php'; ?>
</body>
</html>