<?php
require_once __DIR__ . '/../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../app/Modules/Order/OrderHandler.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// ─── Place Order ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'order') {
    $companyName = trim($_POST['company_name'] ?? '');
    $phone       = trim($_POST['phone']        ?? '');
    $serviceName = trim($_POST['service_name'] ?? '');
    $packageId   = intval($_POST['package_id']   ?? 0) ?: null;
    $packageName = trim($_POST['package_name'] ?? '') ?: null;
    $packageType = trim($_POST['package_type'] ?? '') ?: null;

    if (empty($serviceName)) {
        echo json_encode(['success' => false, 'message' => 'Service name is required.']);
        exit;
    }

    $auth = new AuthHandler();

    if (!AuthHandler::isLoggedIn()) {
        if (empty($companyName) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Company Name and Phone are required.']);
            exit;
        }
        $auth->loginOrRegister($companyName, $phone);
    } else {
        $companyName = $_SESSION['company_name'];
    }

    $userId      = $_SESSION['user_id'];
    $orderHandler = new OrderHandler();
    $orderUid    = $orderHandler->createOrder($userId, $serviceName, $packageId, $packageName, $packageType);

    if ($orderUid) {
        $waLink = $orderHandler->getWhatsAppLink($companyName, $serviceName, $orderUid, $packageName, $packageType);
        echo json_encode(['success' => true, 'redirect_url' => $waLink]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create order.']);
    }
    exit;
}

// ─── Fetch packages for a service (for modal dropdown) ──────────────
require_once __DIR__ . '/../app/Modules/Package/PackageHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get_packages') {
    $serviceId = intval($_GET['service_id'] ?? 0);
    if (!$serviceId) { echo json_encode([]); exit; }
    $ph = new PackageHandler();
    echo json_encode($ph->getByService($serviceId));
    exit;
}

// ─── Admin Actions ──────────────────────────────────────────────────
require_once __DIR__ . '/../app/Modules/Admin/AdminHandler.php';
require_once __DIR__ . '/../app/Modules/Billing/BillingEngine.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'admin_approve') {
    if (!AuthHandler::isAdmin()) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit; }
    $admin = new AdminHandler();
    if ($admin->approveOrder($_POST['order_id'] ?? 0, $_POST['total_amount'] ?? 0)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'admin_invoice') {
    if (!AuthHandler::isAdmin()) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit; }
    $billing = new BillingEngine();
    echo json_encode($billing->generateInvoice($_POST['order_id'] ?? 0));
    exit;
}

// Package CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'package_create') {
    if (!AuthHandler::isAdmin()) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit; }
    $ph = new PackageHandler();
    $ok = $ph->create($_POST['service_id'], $_POST['name'], $_POST['type'], $_POST['price'], $_POST['features']);
    echo json_encode(['success' => $ok]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'package_delete') {
    if (!AuthHandler::isAdmin()) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit; }
    $ph = new PackageHandler();
    echo json_encode(['success' => $ph->delete($_POST['package_id'] ?? 0)]);
    exit;
}

// ─── Loyalty ────────────────────────────────────────────────────────
require_once __DIR__ . '/../app/Modules/Loyalty/LoyaltyHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'spin_wheel') {
    if (!AuthHandler::isLoggedIn()) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit; }
    $loyalty = new LoyaltyHandler();
    echo json_encode($loyalty->spinWheel($_SESSION['user_id']));
    exit;
}

// Logout
if ($action === 'logout') {
    AuthHandler::logout();
    echo json_encode(['success' => true]);
    exit;
}

header("HTTP/1.0 404 Not Found");
echo json_encode(['error' => 'Invalid endpoint']);
