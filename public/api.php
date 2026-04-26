<?php
require_once __DIR__ . '/../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../app/Modules/Order/OrderHandler.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'order') {
    $companyName = $_POST['company_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $serviceName = $_POST['service_name'] ?? '';

    if (empty($serviceName)) {
        echo json_encode(['success' => false, 'message' => 'Service name is missing.']);
        exit;
    }

    $auth = new AuthHandler();
    
    // If not logged in, require company name and phone
    if (!AuthHandler::isLoggedIn()) {
        if (empty($companyName) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Company Name and Phone are required.']);
            exit;
        }
        $auth->loginOrRegister($companyName, $phone);
    } else {
        $companyName = $_SESSION['company_name'];
    }

    $userId = $_SESSION['user_id'];
    $orderHandler = new OrderHandler();
    
    $orderId = $orderHandler->createOrder($userId, $serviceName);
    
    if ($orderId) {
        $waLink = $orderHandler->getWhatsAppLink($companyName, $serviceName, $orderId);
        echo json_encode(['success' => true, 'redirect_url' => $waLink]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create order.']);
    }
    exit;
}

// Logout logic
if ($action === 'logout') {
    AuthHandler::logout();
    echo json_encode(['success' => true]);
    exit;
}

// 404 for invalid API
header("HTTP/1.0 404 Not Found");
echo json_encode(['error' => 'Invalid endpoint']);
