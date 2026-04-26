<?php
require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php';
require_once __DIR__ . '/../../app/Modules/Order/OrderHandler.php';
require_once __DIR__ . '/../../app/Modules/Loyalty/LoyaltyHandler.php';

if (!AuthHandler::isLoggedIn()) {
    header("Location: " . BASE_URL);
    exit;
}

$orderHandler = new OrderHandler();
$orders = $orderHandler->getUserOrders($_SESSION['user_id']);

$loyaltyHandler = new LoyaltyHandler();
$walletInfo = $loyaltyHandler->getWalletInfo($_SESSION['user_id']);
$walletCredits = $walletInfo ? $walletInfo['wallet_credits'] : 0;
$spinsAvailable = $walletInfo ? $walletInfo['spins_available'] : 0;
?>

<div class="min-h-[90vh] px-4 md:px-10 py-12 text-white bg-primary">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
            <h1 class="text-3xl font-bold">Welcome, <span class="text-accent"><?= htmlspecialchars($_SESSION['company_name']) ?></span></h1>
            <button onclick="logout()" class="text-sm bg-gray-800 px-4 py-2 rounded hover:bg-gray-700 transition-colors">Logout</button>
        </div>

        <!-- Loyalty & Wallet Hub -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Wallet Card -->
            <div class="glass-panel p-6 rounded-xl border border-gray-700 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-accent opacity-10 rounded-full blur-3xl"></div>
                <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Wallet Balance</h3>
                <p class="text-5xl font-bold text-white mb-2">$<span id="wallet-balance"><?= number_format($walletCredits, 2) ?></span></p>
                <p class="text-sm text-accent">Use credits for your next order</p>
            </div>

            <!-- Spinning Wheel Card -->
            <div class="glass-panel p-6 rounded-xl border border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Loyalty Hub</h3>
                    <p class="text-lg font-semibold text-white mb-1">Available Spins: <span id="spin-count" class="text-accent font-bold"><?= $spinsAvailable ?></span></p>
                    <p class="text-xs text-gray-400 mb-4">Complete an order to get more spins!</p>
                    
                    <button id="spin-btn" onclick="spinWheel()" <?= $spinsAvailable <= 0 ? 'disabled' : '' ?> class="bg-accent text-white px-6 py-2 rounded font-bold hover:bg-orange-600 transition-colors shadow-accent-glow disabled:opacity-50 disabled:cursor-not-allowed">
                        Spin to Win!
                    </button>
                </div>
                
                <!-- Wheel Graphic -->
                <div class="relative w-32 h-32 ml-4 flex-shrink-0">
                    <!-- Pointer -->
                    <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-[16px] border-t-white z-20"></div>
                    <!-- Wheel -->
                    <div id="wheel-graphic" class="w-full h-full rounded-full border-4 border-gray-700 overflow-hidden relative shadow-lg shadow-accent/20">
                        <div class="absolute inset-0" style="background: conic-gradient(#F0712C 0deg 120deg, #1D1B4D 120deg 240deg, #333 240deg 360deg);"></div>
                        <div class="absolute inset-0 flex items-center justify-center font-bold text-white z-10 bg-black/20 rounded-full">
                            <span class="text-xs tracking-widest uppercase opacity-50">Spin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="glass-panel rounded-xl overflow-hidden shadow-2xl">
            <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Your Orders</h2>
            </div>
            
            <div class="overflow-x-auto p-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm uppercase tracking-wider border-b border-gray-700">
                            <th class="p-4">Order ID</th>
                            <th class="p-4">Service</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-b border-gray-800 hover:bg-white/5 transition-colors">
                                <td class="p-4 font-mono text-accent"><?= htmlspecialchars($order['order_uid']) ?></td>
                                <td class="p-4"><?= htmlspecialchars($order['service_name']) ?></td>
                                <td class="p-4"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
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
                        
                        <?php if(empty($orders)): ?>
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">
                                    No orders found. Let's create your first order!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Spin Result Modal -->
<div id="spin-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('spin-modal').classList.add('hidden')"></div>
    <div class="relative bg-primary border-2 border-accent p-10 rounded-2xl text-center shadow-2xl shadow-accent/50 max-w-sm w-[90%] transform scale-95 opacity-0" id="spin-modal-content">
        <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 shadow-accent-glow">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h2 class="text-3xl font-bold text-white mb-2">Congratulations!</h2>
        <p class="text-gray-300 mb-6">You won <span id="win-amount" class="text-accent font-bold text-2xl">$0</span> in wallet credits!</p>
        <button onclick="document.getElementById('spin-modal').classList.add('hidden')" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-3 rounded transition-colors">
            Awesome!
        </button>
    </div>
</div>

<script>
let isSpinning = false;

function spinWheel() {
    if (isSpinning) return;
    isSpinning = true;
    const btn = document.getElementById('spin-btn');
    btn.disabled = true;

    fetch('<?= BASE_URL ?>api?action=spin_wheel', { method: 'POST' })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            // Animate wheel
            const wheel = document.getElementById('wheel-graphic');
            // Calculate rotation based on index (just visually)
            const extraRot = (data.prize_index * 120) + 60; 
            const totalRot = 360 * 5 + extraRot; // 5 full spins + extra
            
            gsap.to(wheel, {
                rotation: totalRot,
                duration: 4,
                ease: "power4.out",
                onComplete: () => {
                    // Update UI
                    document.getElementById('spin-count').innerText = data.new_balance < 0 ? 0 : document.getElementById('spin-count').innerText - 1; // Wait, data has no new spins count, we just decrement manually
                    let currentSpins = parseInt(document.getElementById('spin-count').innerText);
                    
                    document.getElementById('wallet-balance').innerText = parseFloat(data.new_balance).toFixed(2);
                    
                    // Show Modal
                    document.getElementById('win-amount').innerText = "$" + data.won_amount;
                    const modal = document.getElementById('spin-modal');
                    const modalContent = document.getElementById('spin-modal-content');
                    modal.classList.remove('hidden');
                    
                    gsap.fromTo(modalContent, 
                        { scale: 0.8, opacity: 0, y: 50 }, 
                        { scale: 1, opacity: 1, y: 0, duration: 0.5, ease: "back.out(1.5)" }
                    );

                    // Re-enable if spins > 0
                    isSpinning = false;
                    if (currentSpins > 0) btn.disabled = false;
                }
            });
        } else {
            alert(data.message);
            isSpinning = false;
        }
    })
    .catch(err => {
        console.error(err);
        isSpinning = false;
        btn.disabled = false;
    });
}

function logout() {
    fetch('<?= BASE_URL ?>api?action=logout', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if(data.success) window.location.href = '<?= BASE_URL ?>';
        });
}
</script>
