<?php
require_once __DIR__ . '/../../Core/Database.php';

class OrderHandler {
    private $db;
    private $waNumber;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->waNumber = defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '8801700000000';
    }

    public function createOrder($userId, $serviceName, $packageId = null, $packageName = null, $packageType = null) {
        $orderUid = 'NP-' . strtoupper(substr(md5(uniqid()), 0, 6));

        $stmt = $this->db->prepare("
            INSERT INTO orders (order_uid, user_id, service_name, package_id, package_name, package_type)
            VALUES (:uid, :user_id, :service_name, :package_id, :package_name, :package_type)
        ");

        $stmt->execute([
            ':uid'          => $orderUid,
            ':user_id'      => $userId,
            ':service_name' => $serviceName,
            ':package_id'   => $packageId,
            ':package_name' => $packageName,
            ':package_type' => $packageType,
        ]);

        return $orderUid;
    }

    public function getWhatsAppLink($companyName, $serviceName, $orderUid, $packageName = null, $packageType = null) {
        $pkg = $packageName ? " | Package: {$packageName} ({$packageType})" : '';
        $text = urlencode("Hi NextPixelz! New Order from {$companyName}. Order ID: #{$orderUid}. Service: {$serviceName}{$pkg}. Please confirm.");
        return "https://wa.me/{$this->waNumber}?text={$text}";
    }

    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("
            SELECT o.*, b.file_path as invoice_path
            FROM orders o
            LEFT JOIN billing b ON b.order_id = o.id AND b.type = 'Invoice'
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
