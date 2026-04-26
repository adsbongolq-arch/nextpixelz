<?php
require_once __DIR__ . '/Config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            // Auto-create database if on local environment
            if (ENV === 'local') {
                $tempConn = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
                $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`");
            }

            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (ENV === 'local') {
                die("Database Connection failed: " . $e->getMessage() . " <br>Please make sure you have created the 'nextpixelz_db' database in phpMyAdmin.");
            } else {
                die("System Error: Unable to connect to database.");
            }
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
