<?php
require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../../app/Modules/Order/OrderHandler.php';

if (!AuthHandler::isLoggedIn()) {
    header("Location: " . BASE_URL);
    exit;
}

$userId = $_SESSION['user_id'];
$companyName = htmlspecialchars($_SESSION['company_name']);

$orderHandler = new OrderHandler();
$orders = $orderHandler->getUserOrders($userId);
?>
<div class="min-h-[80vh] px-4 md:px-10 py-12 text-white">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold">Welcome, <span class="text-accent"><?= $companyName ?></span></h1>
            <button onclick="logout()" class="text-sm border border-gray-500 px-4 py-2 rounded hover:bg-gray-800 transition-colors">Logout</button>
        </div>

        <div class="glass-panel rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-6">Your Orders</h2>
            
            <?php if (empty($orders)): ?>
                <div class="text-center py-10 text-gray-400">
                    <p>You have no active orders yet.</p>
                    <a href="<?= BASE_URL ?>" class="text-accent mt-4 inline-block hover:underline">Browse Services</a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700 text-gray-400">
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Service</th>
                                <th class="p-4">Date</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr class="border-b border-gray-800 hover:bg-white/5 transition-colors">
                                    <td class="p-4 font-mono"><?= htmlspecialchars($order['order_uid']) ?></td>
                                    <td class="p-4"><?= htmlspecialchars($order['service_name']) ?></td>
                                    <td class="p-4 text-gray-400"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                    <td class="p-4">
                                        <?php if ($order['status'] === 'Pending'): ?>
                                            <span class="bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full text-xs font-semibold border border-orange-500/30">Pending</span>
                                        <?php elseif ($order['status'] === 'Completed'): ?>
                                            <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/30">Completed</span>
                                            
                                            <?php 
                                            require_once __DIR__ . '/../../app/Modules/Billing/BillingEngine.php';
                                            $billing = new BillingEngine();
                                            $invoiceUrl = $billing->getInvoiceForOrder($order['id']);
                                            if($invoiceUrl): 
                                            ?>
                                                <a href="<?= BASE_URL . $invoiceUrl ?>" target="_blank" class="ml-2 inline-flex items-center text-xs text-accent hover:text-white transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Invoice
                                                </a>
                                            <?php endif; ?>
                                            
                                        <?php else: ?>
                                            <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-xs font-semibold border border-gray-500/30"><?= htmlspecialchars($order['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function logout() {
    fetch('<?= BASE_URL ?>api?action=logout', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if(data.success) window.location.href = '<?= BASE_URL ?>';
        });
}
</script>
