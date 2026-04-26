<?php
require_once __DIR__ . '/../../Core/Database.php';

class AdminHandler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllOrders() {
        $stmt = $this->db->query("
            SELECT o.*, u.company_name, u.phone 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getFinancialStats() {
        $stats = ['completed' => 0.00, 'pending' => 0.00];
        $stmt = $this->db->query("SELECT status, SUM(total_amount) as total FROM orders GROUP BY status");
        while ($row = $stmt->fetch()) {
            if ($row['status'] === 'Completed') {
                $stats['completed'] += $row['total'];
            } else {
                $stats['pending'] += $row['total'];
            }
        }
        return $stats;
    }

    public function approveOrder($orderId, $totalAmount) {
        $stmt = $this->db->prepare("UPDATE orders SET status = 'Completed', total_amount = :amount WHERE id = :id");
        return $stmt->execute([':amount' => $totalAmount, ':id' => $orderId]);
    }
    
    public function getOrderDetails($orderId) {
        $stmt = $this->db->prepare("
            SELECT o.*, u.company_name, u.phone 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = :id
        ");
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetch();
    }
}
