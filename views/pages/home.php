<?php
require_once __DIR__ . '/../../app/Modules/Service/ServiceHandler.php';
$serviceHandler = new ServiceHandler();
$services = $serviceHandler->getActiveServices();
?>

<!-- ScrollSmoother / Lenis implementation can be complex without full JS setup, we will use custom CSS smooth scrolling and heavy GSAP ScrollTrigger for the "Wow" factor -->

<!-- Hero Section -->
<section class="min-h-screen flex flex-col justify-center items-center text-center px-4 relative overflow-hidden bg-[#0a091a]">
    <!-- Ambient Glowing Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-accent opacity-20 rounded-full blur-[120px] mix-blend-screen animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-blue-600 opacity-20 rounded-full blur-[150px] mix-blend-screen" style="animation: pulse 8s infinite alternate;"></div>

    <!-- Grid Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik02MCAwaC0xdjYwaDFWMEptLTQwIDBoLTF2NjBoMVYwSm0tMjAgMGgtMXY2MGgxVjBKbTYwIDIwaC0xdjYwaDFWMjBqbS00MCAwaC0xdjYwaDFWMjBqbS0yMCAwaC0xdjYwaDFWMjBqIiBzdHJva2U9IiNmZmZmZmYiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L2c+PC9zdmc+')] opacity-20"></div>

    <div class="z-10 max-w-5xl mx-auto mt-20">
        <div class="inline-block px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8 gs-reveal">
            <span class="text-accent font-semibold text-sm tracking-widest uppercase">The #1 Growth Agency</span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-extrabold text-white mb-8 leading-[1.1] tracking-tight gs-reveal" style="text-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            We Don't Just Build.<br>
            We <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-orange-400">Scale Businesses.</span>
        </h1>
        
        <p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-12 gs-reveal">
            Stop losing customers to your competitors. From high-performance **Software Development** to ROI-driven **Marketing Campaigns**, we provide the ultimate ecosystem for your digital growth.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-6 gs-reveal">
            <button onclick="openOrderModal('Premium ERP Development')" class="w-full sm:w-auto bg-accent text-white px-10 py-5 rounded-full text-lg font-bold hover:bg-orange-600 hover:scale-105 transition-all shadow-[0_0_40px_rgba(240,113,44,0.4)]">
                Start Your Project Now
            </button>
            <a href="#services" class="w-full sm:w-auto px-10 py-5 rounded-full text-lg font-bold text-white border border-white/20 hover:bg-white/10 transition-all backdrop-blur-sm">
                Explore Our Services
            </a>
        </div>
        
        <!-- Trust Indicators -->
        <div class="mt-20 pt-10 border-t border-white/10 flex flex-wrap justify-center gap-12 opacity-60 gs-reveal">
            <div class="flex flex-col items-center"><span class="text-3xl font-bold text-white">150+</span><span class="text-sm">Projects Delivered</span></div>
            <div class="flex flex-col items-center"><span class="text-3xl font-bold text-white">99%</span><span class="text-sm">Client Retention</span></div>
            <div class="flex flex-col items-center"><span class="text-3xl font-bold text-white">$10M+</span><span class="text-sm">Client Revenue</span></div>
        </div>
    </div>
</section>

<!-- Deep Dive Services Section -->
<section id="services" class="py-32 px-4 md:px-10 bg-[#060513] relative">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-24 gs-reveal">
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">World-Class <span class="text-accent">Expertise</span></h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">We master the tools and strategies that turn ordinary ideas into market-dominating brands.</p>
        </div>

        <!-- Service 1: Software -->
        <div class="flex flex-col md:flex-row items-center gap-16 mb-32 gs-section">
            <div class="w-full md:w-1/2 order-2 md:order-1">
                <div class="inline-block px-3 py-1 rounded text-accent bg-accent/10 font-bold mb-4">Engineering</div>
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Custom Software & <br>ERP Systems</h3>
                <p class="text-lg text-gray-400 mb-8 leading-relaxed">
                    Off-the-shelf software limits your potential. We architect custom, highly scalable Web Applications and ERP systems tailored exactly to your operational needs. Secure, lightning-fast, and designed to automate your workflow so you can focus on growth.
                </p>
                <ul class="space-y-4 mb-8 text-gray-300">
                    <li class="flex items-center"><svg class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Complete Business Automation</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Ultra-Secure Cloud Infrastructure</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Modular & Scalable Architecture</li>
                </ul>
                <button onclick="openOrderModal('Software Development')" class="text-accent font-bold text-lg hover:text-white flex items-center group transition-colors">
                    Request a Quote <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
            <div class="w-full md:w-1/2 order-1 md:order-2">
                <div class="relative rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060513] to-transparent opacity-60 z-10"></div>
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Software Development" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>
        </div>

        <!-- Service 2: Marketing -->
        <div class="flex flex-col md:flex-row items-center gap-16 mb-32 gs-section">
            <div class="w-full md:w-1/2">
                <div class="relative rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060513] to-transparent opacity-60 z-10"></div>
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Data Driven Marketing" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <div class="inline-block px-3 py-1 rounded text-orange-400 bg-orange-400/10 font-bold mb-4">Growth</div>
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Data-Driven <br>Performance Marketing</h3>
                <p class="text-lg text-gray-400 mb-8 leading-relaxed">
                    Stop wasting money on blind advertising. We run highly targeted Facebook, Instagram, and Google ad campaigns based on raw data and consumer psychology. We track every click, optimize every conversion, and maximize your Return on Ad Spend (ROAS).
                </p>
                <ul class="space-y-4 mb-8 text-gray-300">
                    <li class="flex items-center"><svg class="w-6 h-6 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Meta & Google Ads Mastery</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Pixel Tracking & Retargeting</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Guaranteed Lead Generation</li>
                </ul>
                <button onclick="openOrderModal('Performance Marketing')" class="text-accent font-bold text-lg hover:text-white flex items-center group transition-colors">
                    Scale Your Ads <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>

        <!-- Service 3: SEO -->
        <div class="flex flex-col md:flex-row items-center gap-16 gs-section">
            <div class="w-full md:w-1/2 order-2 md:order-1">
                <div class="inline-block px-3 py-1 rounded text-blue-400 bg-blue-400/10 font-bold mb-4">Visibility</div>
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">High-End SEO & <br>Organic Dominance</h3>
                <p class="text-lg text-gray-400 mb-8 leading-relaxed">
                    If you are not on Page 1, you do not exist. Our elite SEO strategies ensure your website ranks for the most profitable keywords in your industry. We don't just bring traffic; we bring buyers.
                </p>
                <ul class="space-y-4 mb-8 text-gray-300">
                    <li class="flex items-center"><svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Technical & On-Page SEO</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> High-Authority Link Building</li>
                    <li class="flex items-center"><svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Local SEO & Maps Ranking</li>
                </ul>
                <button onclick="openOrderModal('SEO Services')" class="text-accent font-bold text-lg hover:text-white flex items-center group transition-colors">
                    Rank Higher <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
            <div class="w-full md:w-1/2 order-1 md:order-2">
                <div class="relative rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060513] to-transparent opacity-60 z-10"></div>
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="SEO Dashboard" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All Services Grid (Dynamic) -->
<section class="py-24 px-4 md:px-10 bg-[#0a091a] border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 gs-reveal">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Complete <span class="text-accent">Solutions</span></h2>
            <p class="text-gray-400">Everything you need to run a successful digital empire.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($services as $index => $service): ?>
                <div class="service-card glass-panel p-8 rounded-xl border border-gray-800 hover:border-accent hover:bg-white/5 group transition-all duration-300 cursor-pointer relative overflow-hidden" onclick="openOrderModal('<?= htmlspecialchars($service['title']) ?>')">
                    <div class="absolute inset-0 bg-gradient-to-br from-accent/0 to-accent/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center mb-6 group-hover:bg-accent transition-colors shadow-lg">
                            <span class="text-white font-bold text-xl"><?= $index + 1 ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2"><?= htmlspecialchars($service['title']) ?></h3>
                        <p class="text-gray-500 text-sm mb-6"><?= htmlspecialchars($service['category']) ?></p>
                        <div class="mt-auto">
                            <span class="text-accent font-semibold flex items-center text-sm group-hover:text-white transition-colors">
                                Book Now 
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action Footer -->
<section class="py-32 px-4 text-center relative overflow-hidden bg-[#060513]">
    <div class="absolute inset-0 bg-accent/5"></div>
    <div class="max-w-4xl mx-auto relative z-10 gs-reveal">
        <h2 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">Ready to Dominate <br>Your Market?</h2>
        <p class="text-xl text-gray-400 mb-12">Don't settle for average. Partner with the agency that delivers extraordinary results.</p>
        <button onclick="openOrderModal('General Inquiry')" class="bg-accent text-white px-12 py-6 rounded-full text-2xl font-bold hover:bg-orange-600 hover:scale-105 transition-all shadow-[0_0_50px_rgba(240,113,44,0.5)]">
            Let's Talk Business
        </button>
    </div>
</section>

<!-- Simple Auth Modal -->
<div id="order-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeOrderModal()"></div>
    
    <!-- Modal Content -->
    <div id="modal-content" class="relative bg-[#0f0e2b] border border-gray-700 p-8 rounded-2xl w-[90%] max-w-md shadow-[0_0_50px_rgba(0,0,0,0.8)]">
        
        <!-- Form State -->
        <div id="form-state">
            <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-white mb-2">Initialize Project</h3>
            <p class="text-gray-400 mb-8 text-sm">Enter your details. You will be instantly connected to our executive team via WhatsApp.</p>
            
            <form id="order-form" onsubmit="submitOrder(event)">
                <input type="hidden" id="service_name" name="service_name" value="">
                
                <?php require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php'; ?>
                <?php if (!AuthHandler::isLoggedIn()): ?>
                    <div class="mb-5">
                        <label class="block text-gray-400 mb-2 text-sm font-semibold uppercase tracking-wider">Company Name</label>
                        <input type="text" name="company_name" required class="w-full bg-[#060513] border border-gray-700 rounded-lg px-4 py-4 text-white focus:outline-none focus:border-accent transition-colors shadow-inner">
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-400 mb-2 text-sm font-semibold uppercase tracking-wider">Phone Number</label>
                        <input type="tel" name="phone" required class="w-full bg-[#060513] border border-gray-700 rounded-lg px-4 py-4 text-white focus:outline-none focus:border-accent transition-colors shadow-inner">
                    </div>
                <?php else: ?>
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4 mb-8">
                        <p class="text-sm text-gray-400 mb-1">Continuing as</p>
                        <p class="text-xl text-white font-bold"><?= htmlspecialchars($_SESSION['company_name']) ?></p>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="w-full bg-accent text-white font-bold py-4 rounded-lg hover:bg-orange-600 transition-colors shadow-[0_0_20px_rgba(240,113,44,0.4)] text-lg">
                    Connect & Procure
                </button>
            </form>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="hidden flex-col items-center justify-center py-12">
            <div class="relative w-20 h-20 mb-6">
                <div class="absolute inset-0 border-4 border-gray-700 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
            </div>
            <h3 class="text-2xl font-bold text-white">Establishing Secure Link</h3>
            <p class="text-accent text-sm mt-2 animate-pulse">Redirecting to Executive WhatsApp...</p>
        </div>
    </div>
</div>

<!-- Load GSAP ScrollTrigger -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<!-- Page Specific JS -->
<script>
    gsap.registerPlugin(ScrollTrigger);

    function openOrderModal(serviceName) {
        document.getElementById('service_name').value = serviceName;
        const modal = document.getElementById('order-modal');
        const modalContent = document.getElementById('modal-content');
        
        modal.classList.remove('hidden');
        
        gsap.fromTo(modalContent, 
            { y: 50, opacity: 0, scale: 0.95 }, 
            { y: 0, opacity: 1, scale: 1, duration: 0.5, ease: "power4.out" }
        );
    }

    function closeOrderModal() {
        const modal = document.getElementById('order-modal');
        const modalContent = document.getElementById('modal-content');
        
        gsap.to(modalContent, { 
            y: 30, opacity: 0, scale: 0.95, duration: 0.3, ease: "power2.in",
            onComplete: () => {
                modal.classList.add('hidden');
                document.getElementById('form-state').classList.remove('hidden');
                document.getElementById('loading-state').classList.add('hidden');
            }
        });
    }

    function submitOrder(e) {
        e.preventDefault();
        
        const form = document.getElementById('order-form');
        const formData = new FormData(form);
        
        gsap.to("#form-state", {
            opacity: 0, duration: 0.3, onComplete: () => {
                document.getElementById('form-state').classList.add('hidden');
                const loadingState = document.getElementById('loading-state');
                loadingState.classList.remove('hidden');
                gsap.fromTo(loadingState, {opacity: 0, scale: 0.9}, {opacity: 1, scale: 1, duration: 0.3});
            }
        });

        fetch('<?= BASE_URL ?>api.php?action=order', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
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
        // Hero Intro Animations
        gsap.from(".gs-reveal", {
            y: 50,
            opacity: 0,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out",
            delay: 0.2
        });

        // Deep Dive Services Scroll Animations
        const sections = document.querySelectorAll('.gs-section');
        sections.forEach(section => {
            gsap.from(section, {
                scrollTrigger: {
                    trigger: section,
                    start: "top 80%",
                },
                y: 100,
                opacity: 0,
                duration: 1.2,
                ease: "power3.out"
            });
        });

        // Dynamic Grid Animation
        gsap.from(".service-card", {
            scrollTrigger: {
                trigger: ".service-card",
                start: "top 85%"
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: "back.out(1.2)"
        });
    });
</script>
