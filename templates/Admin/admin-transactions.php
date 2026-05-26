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
    <title>Admin-transactions</title>
</head>
<body>
    <div class="transaction-item">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>From account id</th>
                        <th>To account id</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $transactions = $adminService->getAllTransactions() ?? []; ?>
                        <?php foreach ($transactions as $tran): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$tran->getId()) ?></td>
                                <td><?= $tran->getFromAccountId() !== null ? htmlspecialchars((string)$tran->getFromAccountId()) : '-' ?></td>
                                <td><?= $tran->getToAccountId() !== null ? htmlspecialchars((string)$tran->getToAccountId()) : '-' ?></td>
                                <td><?= htmlspecialchars($tran->getType()) ?></td>
                                <td><?= htmlspecialchars((string)$tran->getAmount()) ?></td>
                                <td><?= htmlspecialchars($tran->getCreatedAt()->format('Y-m-d')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <?php require __DIR__ . '/../Admin/admin-navbar.php'; ?>
</body>
</html>