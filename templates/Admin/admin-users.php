<?php
require_login();
require_role('admin');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-users</title>
</head>
<body>
    <div class="users-item">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Card number</th>
                        <th>Role</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $users = $adminService->getAllUsers() ?? []; ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user->getId()) ?></td>
                                <td><?= htmlspecialchars($user->getName()) ?></td>
                                <td><?= htmlspecialchars($user->getCardNumber()) ?></td>
                                <td><?= htmlspecialchars($user->getRole()) ?></td>
                                <td><?= htmlspecialchars($user->getCreatedAt()->format('Y-m-d')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
                
                
    </div>
</body>
</html>