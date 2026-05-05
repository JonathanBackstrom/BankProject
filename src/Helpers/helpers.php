<?php
declare(strict_types=1);
    
    function require_login() : void
    {
        if (!isset($_SESSION['user_id']))
            {
                header('Location: ?page=login');
                exit;
            }
    }

    function require_role(string $role)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role)
            {
                http_response_code(403);
                echo "<h1>403 - Acces denied</h1>";
                exit;
            }
    }
?>