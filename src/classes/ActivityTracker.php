<?php

namespace App;

class ActivityTracker {
    private $pdo;
    private $userId;
    private $ipAddress;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->userId = $_SESSION['user_id'] ?? null;
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    public function logPageView($pageName) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO UserActivity (user_id, activity_type, activity_details, ip_address, is_anonymous)
                VALUES (:user_id, 'page_view', :page_name, :ip_address, :is_anonymous)
            ");
            
            return $stmt->execute([
                'user_id' => $this->userId,
                'page_name' => $pageName,
                'ip_address' => $this->ipAddress,
                'is_anonymous' => $this->userId === null
            ]);
        } catch (\PDOException $e) {
            error_log("Tracking error: " . $e->getMessage());
            return false;
        }
    }

    public function logProductView($productId, $productName) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO UserActivity (user_id, activity_type, activity_details, ip_address, is_anonymous)
                VALUES (:user_id, 'product_view', :details, :ip_address, :is_anonymous)
            ");
            
            $details = json_encode([
                'product_id' => $productId,
                'product_name' => $productName
            ]);
            
            return $stmt->execute([
                'user_id' => $this->userId,
                'details' => $details,
                'ip_address' => $this->ipAddress,
                'is_anonymous' => $this->userId === null
            ]);
        } catch (\PDOException $e) {
            error_log("Tracking error: " . $e->getMessage());
            return false;
        }
    }

    public function logCartAction($action, $productId, $quantity = 1) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO UserActivity (user_id, activity_type, activity_details, ip_address, is_anonymous)
                VALUES (:user_id, :action, :details, :ip_address, :is_anonymous)
            ");
            
            $details = json_encode([
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
            
            return $stmt->execute([
                'user_id' => $this->userId,
                'action' => $action,
                'details' => $details,
                'ip_address' => $this->ipAddress,
                'is_anonymous' => $this->userId === null
            ]);
        } catch (\PDOException $e) {
            error_log("Tracking error: " . $e->getMessage());
            return false;
        }
    }

    public function logPurchase($orderId, $totalAmount) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO UserActivity (user_id, activity_type, activity_details, ip_address, is_anonymous)
                VALUES (:user_id, 'purchase', :details, :ip_address, :is_anonymous)
            ");
            
            $details = json_encode([
                'order_id' => $orderId,
                'total_amount' => $totalAmount
            ]);
            
            return $stmt->execute([
                'user_id' => $this->userId,
                'details' => $details,
                'ip_address' => $this->ipAddress,
                'is_anonymous' => false // Purchases are always logged for registered users
            ]);
        } catch (\PDOException $e) {
            error_log("Tracking error: " . $e->getMessage());
            return false;
        }
    }
} 