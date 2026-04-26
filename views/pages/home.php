<section class="min-h-[85vh] flex flex-col justify-center items-center text-center px-4 relative overflow-hidden">
    <!-- Animated Background Triangle -->
    <div id="bg-triangle" class="absolute opacity-5 -z-10 w-0 h-0 border-l-[300px] border-l-transparent border-r-[300px] border-r-transparent border-b-[500px] border-b-accent top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>

    <h1 id="hero-headline" class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
        Scale Your Agency with <br> <span class="text-accent">Next-Gen Solutions</span>
    </h1>
    <p id="hero-subline" class="text-lg md:text-xl text-gray-300 max-w-2xl mb-10">
        We provide world-class software development, high-end SEO, and ROI-driven marketing to elevate your business. No complex jargon, just results.
    </p>
    <div id="hero-cta">
        <button onclick="openOrderModal('Premium ERP Development')" class="bg-accent text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-orange-600 transition-all shadow-accent-glow inline-block">
            Boost Your Sales Today
        </button>
    </div>
</section>

<!-- Simple Auth Modal -->
<div id="order-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeOrderModal()"></div>
    
    <!-- Modal Content -->
    <div id="modal-content" class="relative bg-primary border border-gray-700 p-8 rounded-2xl w-[90%] max-w-md shadow-2xl glass-panel">
        
        <!-- Form State -->
        <div id="form-state">
            <h3 class="text-2xl font-bold text-white mb-2">Quick Order</h3>
            <p class="text-gray-400 mb-6 text-sm">Enter your details to generate your order and connect via WhatsApp.</p>
            
            <form id="order-form" onsubmit="submitOrder(event)">
                <input type="hidden" id="service_name" name="service_name" value="">
                
                <?php require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php'; ?>
                <?php if (!AuthHandler::isLoggedIn()): ?>
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2 text-sm">Company Name</label>
                        <input type="text" name="company_name" required class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-300 mb-2 text-sm">Phone Number</label>
                        <input type="tel" name="phone" required class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent">
                    </div>
                <?php else: ?>
                    <p class="text-accent mb-6">Logged in as <?= htmlspecialchars($_SESSION['company_name']) ?></p>
                <?php endif; ?>
                
                <button type="submit" class="w-full bg-accent text-white font-bold py-3 rounded-lg hover:bg-orange-600 transition-colors shadow-accent-glow">
                    Continue to WhatsApp
                </button>
            </form>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="hidden flex-col items-center justify-center py-10">
            <div class="w-12 h-12 border-4 border-accent border-t-transparent rounded-full animate-spin mb-4"></div>
            <h3 class="text-xl font-bold text-white">Processing your order...</h3>
            <p class="text-gray-400 text-sm mt-2">Connecting to WhatsApp</p>
        </div>
    </div>
</div>

<!-- Page Specific JS -->
<script>
    function openOrderModal(serviceName) {
        document.getElementById('service_name').value = serviceName;
        const modal = document.getElementById('order-modal');
        const modalContent = document.getElementById('modal-content');
        
        modal.classList.remove('hidden');
        
        // GSAP Modal Entry
        gsap.fromTo(modalContent, 
            { y: 50, opacity: 0, scale: 0.9 }, 
            { y: 0, opacity: 1, scale: 1, duration: 0.4, ease: "back.out(1.5)" }
        );
    }

    function closeOrderModal() {
        const modal = document.getElementById('order-modal');
        const modalContent = document.getElementById('modal-content');
        
        // GSAP Modal Exit
        gsap.to(modalContent, { 
            y: 30, opacity: 0, scale: 0.95, duration: 0.3, ease: "power2.in",
            onComplete: () => {
                modal.classList.add('hidden');
                // reset states
                document.getElementById('form-state').classList.remove('hidden');
                document.getElementById('loading-state').classList.add('hidden');
            }
        });
    }

    function submitOrder(e) {
        e.preventDefault();
        
        const form = document.getElementById('order-form');
        const formData = new FormData(form);
        
        // GSAP transition to Loading State
        gsap.to("#form-state", {
            opacity: 0, duration: 0.3, onComplete: () => {
                document.getElementById('form-state').classList.add('hidden');
                const loadingState = document.getElementById('loading-state');
                loadingState.classList.remove('hidden');
                gsap.fromTo(loadingState, {opacity: 0, scale: 0.9}, {opacity: 1, scale: 1, duration: 0.3});
            }
        });

        fetch('<?= BASE_URL ?>api?action=order', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // 1.5 second delay before redirect
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                alert("Error: " + data.message);
                closeOrderModal();
            }
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong!");
            closeOrderModal();
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Hero Stagger Animation
        gsap.from("#hero-headline", { y: 50, opacity: 0, duration: 1, delay: 0.2 });
        gsap.from("#hero-subline", { y: 30, opacity: 0, duration: 1, delay: 0.5 });
        gsap.from("#hero-cta", { scale: 0.8, opacity: 0, duration: 0.8, delay: 0.8, ease: "back.out(1.7)" });
        
        // Triangle Pulse Animation
        gsap.to("#bg-triangle", { 
            scale: 1.15, 
            opacity: 0.1, 
            duration: 4, 
            repeat: -1, 
            yoyo: true, 
            ease: "sine.inOut" 
        });
    });
</script>
