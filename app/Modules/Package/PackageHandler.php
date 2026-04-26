<?php
require_once __DIR__ . '/../../Core/Database.php';

class PackageHandler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllWithServices() {
        return $this->db->query("
            SELECT p.*, s.title as service_title, s.category
            FROM packages p
            JOIN services s ON p.service_id = s.id
            ORDER BY s.id ASC, p.price ASC
        ")->fetchAll();
    }

    public function getByService($serviceId) {
        $stmt = $this->db->prepare("SELECT * FROM packages WHERE service_id = :sid AND is_active = 1 ORDER BY price ASC");
        $stmt->execute([':sid' => $serviceId]);
        return $stmt->fetchAll();
    }

    public function getServicesWithPackages() {
        $services = $this->db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC")->fetchAll();
        foreach ($services as &$s) {
            $stmt = $this->db->prepare("SELECT * FROM packages WHERE service_id = :sid AND is_active = 1 ORDER BY price ASC");
            $stmt->execute([':sid' => $s['id']]);
            $s['packages'] = $stmt->fetchAll();
        }
        return $services;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, s.title as service_title FROM packages p JOIN services s ON p.service_id = s.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($serviceId, $name, $type, $price, $features) {
        $featuresJson = json_encode(array_values(array_filter(array_map('trim', explode("\n", $features)))));
        $stmt = $this->db->prepare("INSERT INTO packages (service_id, name, type, price, features) VALUES (:sid, :name, :type, :price, :features)");
        return $stmt->execute([':sid' => $serviceId, ':name' => $name, ':type' => $type, ':price' => $price, ':features' => $featuresJson]);
    }

    public function update($id, $serviceId, $name, $type, $price, $features) {
        $featuresJson = json_encode(array_values(array_filter(array_map('trim', explode("\n", $features)))));
        $stmt = $this->db->prepare("UPDATE packages SET service_id=:sid, name=:name, type=:type, price=:price, features=:features WHERE id=:id");
        return $stmt->execute([':sid' => $serviceId, ':name' => $name, ':type' => $type, ':price' => $price, ':features' => $featuresJson, ':id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM packages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getAllServices() {
        return $this->db->query("SELECT id, title, category FROM services WHERE is_active = 1 ORDER BY id ASC")->fetchAll();
    }
}
