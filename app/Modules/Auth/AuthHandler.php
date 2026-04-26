<?php
require_once __DIR__ . '/../../Core/Database.php';

class AuthHandler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function loginOrRegister($companyName, $phone) {
        // Sanitize
        $companyName = htmlspecialchars(strip_tags($companyName));
        $phone = htmlspecialchars(strip_tags($phone));

        // Check if user exists
        $stmt = $this->db->prepare("SELECT id, company_name FROM users WHERE phone = :phone LIMIT 1");
        $stmt->execute([':phone' => $phone]);
        $user = $stmt->fetch();

        if ($user) {
            // Existing user, log them in
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['company_name'] = $user['company_name'];
            return true;
        } else {
            // New user, register and log in
            $stmt = $this->db->prepare("INSERT INTO users (company_name, phone) VALUES (:company_name, :phone)");
            if ($stmt->execute([':company_name' => $companyName, ':phone' => $phone])) {
                $_SESSION['user_id'] = $this->db->lastInsertId();
                $_SESSION['company_name'] = $companyName;
                return true;
            }
        }
        return false;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function logout() {
        session_destroy();
    }
}
