<?php
declare(strict_types=1);
    
    function require_login() : void
    {
        check_idle_timeout(10);

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

    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token']))
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    function csrf_field(): string
    {
        return
         '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')
        . '">';
    }

    function csrf_verify(): void
    {
        $token = $_POST['csrf_token'] ?? '';

        if (!hash_equals(csrf_token(), $token))
             {
                http_response_code(403);
                echo "<h1>403 – Ogiltig CSRF-token<h1>";
                exit;
            }

        unset($_SESSION['csrf_token']);
    }

    // Timear ut ifall man inte är aktiv på sidan
    function check_idle_timeout(int $minutes): void
    {
        // kollar så man är inloggad
        if (!isset($_SESSION['user_id'])) return;

        // skapar variabel som senast aktiv
        $lastActive = $_SESSION['last_active'] ?? 0;

        // förstör sessionen och kastar ut användaren ifall man inte varit aktiv efter viss tid.
        if (time() - $lastActive > $minutes * 60)
            {
                session_unset();
                session_destroy();
                setcookie(session_name(), '', time() - 3600, '/');
                header("location: ?page=login&reason=timeout");
                exit;
            }
            // uppdaterar till ny tid
            $_SESSION['last_active'] = time();
    }
?>