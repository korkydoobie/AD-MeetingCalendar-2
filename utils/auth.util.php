<?php
declare(strict_types=1);

// Include envSetter (if needed for DB access)
require_once UTILS_PATH . '/envSetter.util.php';

class Auth
{
    // ✅ Make sure session is always started
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ✅ Login logic
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        self::init(); // Start session if not yet

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login — store user info in session
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'firstName' => $user['first_name'],
                'lastName'  => $user['last_name'],
                'role'      => $user['role'],
            ];
            return true;
        }

        // Failed login
        return false;
    }

    // ✅ Get current logged-in user
    public static function user(): ?array
    {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    // ✅ Check if someone is logged in
    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user']);
    }

    // ✅ Logout function
    public static function logout(): void
    {
        self::init();
        session_unset();
        session_destroy();

        // Optional: clear session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }
}
