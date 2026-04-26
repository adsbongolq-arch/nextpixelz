<?php
require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../../app/Modules/Admin/AdminHandler.php';
require_once __DIR__ . '/../../app/Modules/Billing/BillingEngine.php';

if (!AuthHandler::isAdmin()) {
    header("Location: " . BASE_URL);
    exit;
}

$admin = new AdminHandler();
$stats = $admin->getFinancialStats();
$orders = $admin->getAllOrders();
$billing = new BillingEngine();
?>
<div class="min-h-[90vh] px-4 md:px-10 py-12 text-white bg-[#0f0e2b]">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
            <h1 class="text-3xl font-bold text-accent">Admin Powerhouse</h1>
            <button onclick="logout()" class="text-sm bg-gray-800 px-4 py-2 rounded hover:bg-gray-700 transition-colors">Logout</button>
        </div>

        <!-- Financial Tracking -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="glass-panel p-6 rounded-xl border border-emerald-500/30">
                <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Total Revenue (Completed)</h3>
                <p class="text-4xl font-bold text-emerald-400">$<?= number_format($stats['completed'], 2) ?></p>
            </div>
            <div class="glass-panel p-6 rounded-xl border border-orange-500/30">
                <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Pending Revenue</h3>
                <p class="text-4xl font-bold text-orange-400">$<?= number_format($stats['pending'], 2) ?></p>
            </div>
        </div>

        <!-- Order Management -->
        <div class="glass-panel rounded-xl overflow-hidden shadow-2xl">
            <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Recent Orders</h2>
            </div>
            <div class="overflow-x-auto p-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm uppercase tracking-wider border-b border-gray-700">
                            <th class="p-4">Order ID</th>
                            <th class="p-4">Client</th>
                            <th class="p-4">Service</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Price ($)</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-b border-gray-800 hover:bg-white/5 transition-colors">
                                <td class="p-4 font-mono text-accent"><?= htmlspecialchars($order['order_uid']) ?></td>
                                <td class="p-4">
                                    <div class="font-semibold"><?= htmlspecialchars($order['company_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($order['phone']) ?></div>
                                </td>
                                <td class="p-4"><?= htmlspecialchars($order['service_name']) ?></td>
                                <td class="p-4">
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <span class="bg-orange-500/20 text-orange-400 px-2 py-1 rounded text-xs">Pending</span>
                                    <?php else: ?>
                                        <span class="bg-emerald-500/20 text-emerald-400 px-2 py-1 rounded text-xs">Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <input type="number" id="price_<?= $order['id'] ?>" class="w-24 bg-gray-900 border border-gray-700 rounded px-2 py-1 text-white focus:border-accent outline-none" value="0.00" step="0.01">
                                    <?php else: ?>
                                        <span class="font-semibold">$<?= number_format($order['total_amount'], 2) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 space-x-2 flex">
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <button onclick="approveOrder(<?= $order['id'] ?>)" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1 rounded text-xs transition-colors">Approve</button>
                                    <?php else: ?>
                                        <?php $invoiceUrl = $billing->getInvoiceForOrder($order['id']); ?>
                                        <?php if ($invoiceUrl): ?>
                                            <a href="<?= BASE_URL . $invoiceUrl ?>" target="_blank" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs transition-colors">View PDF</a>
                                        <?php else: ?>
                                            <button onclick="generateInvoice(<?= $order['id'] ?>)" class="bg-accent hover:bg-orange-500 text-white px-3 py-1 rounded text-xs transition-colors shadow-accent-glow">Gen Invoice</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($orders)): ?>
                            <tr><td colspan="6" class="p-8 text-center text-gray-500">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

function approveOrder(orderId) {
    const priceInput = document.getElementById('price_' + orderId);
    const amount = priceInput ? priceInput.value : 0;

    if (amount <= 0) {
        alert("Please set a valid price before approving.");
        return;
    }

    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('total_amount', amount);

    fetch('<?= BASE_URL ?>api?action=admin_approve', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
}

function generateInvoice(orderId) {
    const formData = new FormData();
    formData.append('order_id', orderId);

    fetch('<?= BASE_URL ?>api?action=admin_invoice', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
}
</script>
