<?php
require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../../app/Modules/Admin/AdminHandler.php';
require_once __DIR__ . '/../../app/Modules/Billing/BillingEngine.php';
require_once __DIR__ . '/../../app/Modules/Package/PackageHandler.php';

if (!AuthHandler::isAdmin()) {
    header("Location: " . BASE_URL);
    exit;
}

$admin   = new AdminHandler();
$stats   = $admin->getFinancialStats();
$orders  = $admin->getAllOrders();
$billing = new BillingEngine();
$ph      = new PackageHandler();
$packages     = $ph->getAllWithServices();
$allServices  = $ph->getAllServices();
?>
<div class="min-h-screen px-4 md:px-8 py-10 text-white" style="background:#06050f">
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-10 border-b border-white/10 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Admin <span class="text-accent">Powerhouse</span></h1>
            <p class="text-gray-500 text-sm mt-1">Manage orders, packages &amp; billing</p>
        </div>
        <div class="flex gap-3">
            <button onclick="showTab('orders')" id="tab-orders" class="tab-btn px-5 py-2 rounded-xl text-sm font-bold bg-accent text-white transition-all">Orders</button>
            <button onclick="showTab('packages')" id="tab-packages" class="tab-btn px-5 py-2 rounded-xl text-sm font-bold bg-white/10 text-gray-300 hover:bg-white/20 transition-all">Manage Packages</button>
            <button onclick="logout()" class="px-4 py-2 rounded-xl text-sm bg-red-900/40 text-red-400 hover:bg-red-900/70 transition-all">Logout</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6">
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-2">Total Revenue</p>
            <p class="text-4xl font-extrabold text-white">$<?= number_format($stats['completed'], 2) ?></p>
        </div>
        <div class="rounded-2xl border border-orange-500/20 bg-orange-500/5 p-6">
            <p class="text-xs font-bold uppercase tracking-wider text-orange-400 mb-2">Pending Revenue</p>
            <p class="text-4xl font-extrabold text-white">$<?= number_format($stats['pending'], 2) ?></p>
        </div>
        <div class="rounded-2xl border border-violet-500/20 bg-violet-500/5 p-6">
            <p class="text-xs font-bold uppercase tracking-wider text-violet-400 mb-2">Total Orders</p>
            <p class="text-4xl font-extrabold text-white"><?= count($orders) ?></p>
        </div>
    </div>

    <!-- ORDERS TAB -->
    <div id="panel-orders">
        <div class="rounded-2xl border border-white/10 overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10">
                <h2 class="text-lg font-bold">All Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-white/10">
                            <th class="p-4">Order ID</th>
                            <th class="p-4">Client</th>
                            <th class="p-4">Service / Package</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="p-4 font-mono text-accent text-xs"><?= htmlspecialchars($order['order_uid']) ?></td>
                            <td class="p-4">
                                <div class="font-semibold"><?= htmlspecialchars($order['company_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($order['phone']) ?></div>
                            </td>
                            <td class="p-4">
                                <div><?= htmlspecialchars($order['service_name']) ?></div>
                                <?php if (!empty($order['package_name'])): ?>
                                <div class="text-xs text-accent"><?= htmlspecialchars($order['package_name']) ?> · <?= htmlspecialchars($order['package_type'] ?? '') ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <span class="bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                                <?php elseif ($order['status'] === 'Completed'): ?>
                                    <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-bold">Completed</span>
                                <?php else: ?>
                                    <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-xs font-bold"><?= $order['status'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <input type="number" id="price_<?= $order['id'] ?>" value="<?= $order['total_amount'] ?: '' ?>" placeholder="0.00" step="0.01" class="w-24 bg-black/40 border border-white/10 rounded-lg px-2 py-1.5 text-white focus:border-accent outline-none text-sm">
                                <?php else: ?>
                                    <span class="font-bold text-white">$<?= number_format($order['total_amount'], 2) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <button onclick="approveOrder(<?= $order['id'] ?>)" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Approve</button>
                                <?php else: ?>
                                    <?php $invoiceUrl = $billing->getInvoiceForOrder($order['id']); ?>
                                    <?php if ($invoiceUrl): ?>
                                        <a href="<?= BASE_URL . $invoiceUrl ?>" target="_blank" class="bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">View PDF</a>
                                    <?php else: ?>
                                        <button onclick="generateInvoice(<?= $order['id'] ?>)" class="bg-accent hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Gen Invoice</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="p-10 text-center text-gray-500">No orders yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PACKAGES TAB -->
    <div id="panel-packages" class="hidden">
        <!-- Add Package Form -->
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 mb-8">
            <h2 class="text-lg font-bold mb-6">Add New Package</h2>
            <form onsubmit="createPackage(event)" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Service</label>
                    <select name="service_id" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none">
                        <?php foreach ($allServices as $svc): ?>
                        <option value="<?= $svc['id'] ?>"><?= htmlspecialchars($svc['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Package Name</label>
                    <input type="text" name="name" placeholder="e.g. SEO Growth Plan" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Type</label>
                    <select name="type" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none">
                        <option>Monthly</option>
                        <option>Weekly</option>
                        <option>One-Time</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Price ($)</label>
                    <input type="number" name="price" step="0.01" placeholder="99.00" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Features <span class="text-gray-600">(one per line)</span></label>
                    <textarea name="features" rows="4" placeholder="10 Target Keywords&#10;Monthly Report&#10;On-Page Optimization" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none resize-none"></textarea>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-accent hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-all shadow-accent-glow">Add Package</button>
                </div>
            </form>
        </div>

        <!-- Existing Packages List -->
        <div class="rounded-2xl border border-white/10 overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10">
                <h2 class="text-lg font-bold">All Packages</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-white/10">
                            <th class="p-4">Service</th>
                            <th class="p-4">Package</th>
                            <th class="p-4">Type</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($packages as $pkg): ?>
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="p-4 text-gray-400"><?= htmlspecialchars($pkg['service_title']) ?></td>
                            <td class="p-4 font-semibold text-white"><?= htmlspecialchars($pkg['name']) ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-violet-500/20 text-violet-300"><?= $pkg['type'] ?></span>
                            </td>
                            <td class="p-4 font-bold text-accent">$<?= number_format($pkg['price'], 2) ?></td>
                            <td class="p-4">
                                <button onclick="deletePackage(<?= $pkg['id'] ?>)" class="text-red-500 hover:text-red-400 text-xs font-bold transition-colors">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($packages)): ?>
                        <tr><td colspan="5" class="p-10 text-center text-gray-500">No packages yet. Add one above.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<script>
const BASE = '<?= BASE_URL ?>';

function showTab(tab) {
    document.getElementById('panel-orders').classList.toggle('hidden', tab !== 'orders');
    document.getElementById('panel-packages').classList.toggle('hidden', tab !== 'packages');
    document.getElementById('tab-orders').className   = 'tab-btn px-5 py-2 rounded-xl text-sm font-bold transition-all ' + (tab === 'orders'   ? 'bg-accent text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20');
    document.getElementById('tab-packages').className = 'tab-btn px-5 py-2 rounded-xl text-sm font-bold transition-all ' + (tab === 'packages' ? 'bg-accent text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20');
}

function logout() {
    fetch(BASE + 'api.php?action=logout', { method:'POST' }).then(r => r.json()).then(d => { if(d.success) window.location.href = BASE; });
}

function approveOrder(id) {
    const amount = document.getElementById('price_' + id)?.value;
    if (!amount || parseFloat(amount) <= 0) { alert('Please set a valid price before approving.'); return; }
    const fd = new FormData(); fd.append('order_id', id); fd.append('total_amount', amount);
    fetch(BASE + 'api.php?action=admin_approve', { method:'POST', body:fd })
        .then(r => r.json()).then(d => { if(d.success) location.reload(); else alert(d.message); });
}

function generateInvoice(id) {
    const fd = new FormData(); fd.append('order_id', id);
    fetch(BASE + 'api.php?action=admin_invoice', { method:'POST', body:fd })
        .then(r => r.json()).then(d => { if(d.success) location.reload(); else alert(d.message); });
}

function createPackage(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    fetch(BASE + 'api.php?action=package_create', { method:'POST', body:fd })
        .then(r => r.json()).then(d => { if(d.success) location.reload(); else alert('Failed to create package.'); });
}

function deletePackage(id) {
    if (!confirm('Delete this package?')) return;
    const fd = new FormData(); fd.append('package_id', id);
    fetch(BASE + 'api.php?action=package_delete', { method:'POST', body:fd })
        .then(r => r.json()).then(d => { if(d.success) location.reload(); else alert('Failed to delete.'); });
}
</script>
