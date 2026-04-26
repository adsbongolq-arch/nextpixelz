<?php
require_once __DIR__ . '/../../Core/Database.php';

class ServiceHandler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActiveServices() {
        $stmt = $this->db->query("SELECT * FROM services WHERE is_active = TRUE ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getFeaturedServices($limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE is_active = TRUE ORDER BY id ASC LIMIT :limit");
        // BindValue instead of execute array for LIMIT clause
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
