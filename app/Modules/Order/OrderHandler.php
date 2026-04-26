<?php
require_once __DIR__ . '/../../Core/Database.php';

class OrderHandler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createOrder($userId, $serviceName) {
        $orderUid = '#AQ-' . rand(1000, 9999);
        
        $stmt = $this->db->prepare("INSERT INTO orders (order_uid, user_id, service_name, status) VALUES (:order_uid, :user_id, :service_name, 'Pending')");
        if ($stmt->execute([
            ':order_uid' => $orderUid,
            ':user_id' => $userId,
            ':service_name' => $serviceName
        ])) {
            return $orderUid;
        }
        return false;
    }

    public function getWhatsAppLink($companyName, $serviceName, $orderId) {
        $message = "Hi, I am from {$companyName}. I want to order {$serviceName}. Order ID: {$orderId}.";
        // URL Encode the message
        $encodedMessage = rawurlencode($message);
        return "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . $encodedMessage;
    }

    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
