<?php
require_once __DIR__ . '/../../Core/Database.php';

class LoyaltyHandler {
    private $db;
    
    // Prizes in credits
    private $prizes = [10, 20, 50];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function spinWheel($userId) {
        $stmt = $this->db->prepare("SELECT spins_available, wallet_credits FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();

        if (!$user || $user['spins_available'] <= 0) {
            return ['success' => false, 'message' => 'No spins available!'];
        }

        // Randomly pick a prize
        $prizeIndex = array_rand($this->prizes);
        $wonAmount = $this->prizes[$prizeIndex];

        // Update User Wallet & Spins
        $newCredits = $user['wallet_credits'] + $wonAmount;
        $newSpins = $user['spins_available'] - 1;

        $updateStmt = $this->db->prepare("UPDATE users SET wallet_credits = :credits, spins_available = :spins WHERE id = :id");
        if ($updateStmt->execute([':credits' => $newCredits, ':spins' => $newSpins, ':id' => $userId])) {
            return [
                'success' => true,
                'won_amount' => $wonAmount,
                'new_balance' => $newCredits,
                'prize_index' => $prizeIndex // useful for frontend rotation calculation
            ];
        }

        return ['success' => false, 'message' => 'Something went wrong!'];
    }

    public function getWalletInfo($userId) {
        $stmt = $this->db->prepare("SELECT wallet_credits, spins_available FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }
}
