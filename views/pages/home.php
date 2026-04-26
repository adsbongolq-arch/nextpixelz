<?php
require_once __DIR__ . '/../../app/Modules/Package/PackageHandler.php';
$ph = new PackageHandler();
$servicesWithPackages = $ph->getServicesWithPackages();

// Icon map
$icons = [
    'code'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
    'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
    'ads'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
    'brush'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
    'cart'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
    'social' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>',
];
$gradients = [
    'Development' => 'from-violet-600/20 to-indigo-600/10 border-violet-500/30',
    'Marketing'   => 'from-orange-600/20 to-rose-600/10 border-orange-500/30',
    'Design'      => 'from-pink-600/20 to-fuchsia-600/10 border-pink-500/30',
];
$badgeColors = [
    'Development' => 'bg-violet-500/20 text-violet-300',
    'Marketing'   => 'bg-orange-500/20 text-orange-300',
    'Design'      => 'bg-pink-500/20 text-pink-300',
];
?>

<!-- HERO -->
<section class="relative min-h-screen flex flex-col justify-center items-center text-center px-4 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#06050f] via-[#0d0b23] to-[#06050f]"></div>
    <div class="absolute top-1/3 left-1/4 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-violet-600/15 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto pt-24">
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-8 hero-badge">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            <span class="text-sm text-gray-300 tracking-widest uppercase font-medium">Taking New Projects</span>
        </div>

        <h1 class="text-6xl md:text-8xl font-extrabold text-white mb-8 leading-[1.05] tracking-tight hero-title">
            We Build<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-orange-400 to-yellow-400">What You Imagine.</span>
        </h1>

        <p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-12 hero-sub">
            Custom Websites, ERP, CRM, POS, SEO & Performance Marketing — no limits, no templates. Pure results for serious businesses.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 hero-cta">
            <a href="#services" class="bg-accent hover:bg-orange-600 text-white px-10 py-5 rounded-full font-bold text-lg transition-all hover:scale-105 shadow-[0_0_40px_rgba(240,113,44,0.35)]">
                Explore Services
            </a>
            <button onclick="openOrderModal(null,null,null,null)" class="border border-white/20 hover:bg-white/10 text-white px-10 py-5 rounded-full font-bold text-lg transition-all backdrop-blur-sm">
                Start a Project
            </button>
        </div>

        <!-- Stats row -->
        <div class="mt-20 pt-10 border-t border-white/10 flex flex-wrap justify-center gap-12 hero-stats">
            <?php foreach ([['150+','Projects Done'],['50+','Happy Clients'],['99%','Success Rate']] as $stat): ?>
            <div class="flex flex-col items-center">
                <span class="text-4xl font-extrabold text-white"><?= $stat[0] ?></span>
                <span class="text-sm text-gray-500 mt-1"><?= $stat[1] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BENTO SERVICE GRID -->
<section id="services" class="py-28 px-4 md:px-10 bg-[#06050f]">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 reveal-block">
            <span class="text-accent text-sm font-bold uppercase tracking-widest">What We Do</span>
            <h2 class="text-4xl md:text-6xl font-extrabold text-white mt-3 mb-5">Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-orange-300">Services</span></h2>
            <p class="text-gray-400 max-w-xl mx-auto">Click any service to see packages and pricing instantly.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($servicesWithPackages as $i => $svc):
                $grad = $gradients[$svc['category']] ?? 'from-gray-600/20 to-gray-800/10 border-gray-500/30';
                $badge = $badgeColors[$svc['category']] ?? 'bg-gray-500/20 text-gray-300';
                $iconPath = $icons[$svc['icon_key'] ?? 'code'] ?? $icons['code'];
                $minPrice = !empty($svc['packages']) ? min(array_column($svc['packages'],'price')) : null;
                $isLarge = $i === 0 || $i === 3;
            ?>
            <div class="bento-card group relative rounded-2xl border bg-gradient-to-br <?= $grad ?> p-7 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_60px_rgba(0,0,0,0.4)] <?= $isLarge ? 'sm:col-span-2 lg:col-span-1' : '' ?>"
                 onclick="openPricingModal(<?= $svc['id'] ?>, '<?= htmlspecialchars(addslashes($svc['title'])) ?>')">
                <!-- Glow on hover -->
                <div class="absolute inset-0 rounded-2xl bg-white/0 group-hover:bg-white/[0.03] transition-colors"></div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?= $iconPath ?>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full <?= $badge ?>"><?= htmlspecialchars($svc['category']) ?></span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-accent transition-colors"><?= htmlspecialchars($svc['title']) ?></h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-5"><?= htmlspecialchars($svc['description']) ?></p>

                    <div class="flex items-center justify-between">
                        <?php if ($minPrice): ?>
                        <span class="text-gray-300 text-sm">From <span class="text-white font-bold text-lg">$<?= number_format($minPrice,0) ?></span></span>
                        <?php endif; ?>
                        <span class="text-accent text-sm font-semibold flex items-center gap-1 group-hover:gap-3 transition-all">
                            View Packages
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PRICING MODAL (Service-specific packages) -->
<div id="pricing-modal" class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closePricingModal()"></div>
    <div class="relative w-full max-w-5xl max-h-[90vh] overflow-y-auto bg-[#0d0b23] border border-white/10 rounded-2xl shadow-2xl">
        <div class="sticky top-0 bg-[#0d0b23] border-b border-white/10 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-accent text-xs font-bold uppercase tracking-widest mb-1">Packages & Pricing</p>
                <h3 id="pricing-modal-title" class="text-2xl font-extrabold text-white"></h3>
            </div>
            <button onclick="closePricingModal()" class="text-gray-400 hover:text-white transition-colors p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="pricing-modal-body" class="p-8">
            <div class="flex justify-center"><div class="w-10 h-10 border-2 border-accent border-t-transparent rounded-full animate-spin"></div></div>
        </div>
    </div>
</div>

<!-- ORDER MODAL -->
<div id="order-modal" class="fixed inset-0 z-[300] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeOrderModal()"></div>
    <div id="modal-content" class="relative w-full max-w-md bg-[#0d0b23] border border-white/10 rounded-2xl p-8 shadow-2xl">
        <div id="form-state">
            <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-white mb-1">Start Your Project</h3>
            <p class="text-gray-500 text-sm mb-6">We'll connect with you on WhatsApp within minutes.</p>

            <form id="order-form" onsubmit="submitOrder(event)">
                <input type="hidden" id="f_service_name" name="service_name" value="">
                <input type="hidden" id="f_package_id"   name="package_id"   value="">
                <input type="hidden" id="f_package_name" name="package_name" value="">
                <input type="hidden" id="f_package_type" name="package_type" value="">

                <?php require_once __DIR__ . '/../../app/Modules/Auth/AuthHandler.php'; ?>
                <?php if (!AuthHandler::isLoggedIn()): ?>
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Company Name</label>
                    <input type="text" name="company_name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-accent transition-colors">
                </div>
                <div class="mb-5">
                    <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="tel" name="phone" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-accent transition-colors">
                </div>
                <?php else: ?>
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 mb-5">
                    <p class="text-xs text-gray-400 mb-1">Ordering as</p>
                    <p class="text-white font-bold"><?= htmlspecialchars($_SESSION['company_name']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Selected package summary -->
                <div id="package-summary" class="hidden bg-accent/10 border border-accent/20 rounded-xl p-4 mb-6">
                    <p class="text-xs text-gray-400 mb-1">Selected Package</p>
                    <p id="pkg-summary-text" class="text-white font-bold text-sm"></p>
                </div>

                <button type="submit" class="w-full bg-accent hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors shadow-[0_0_20px_rgba(240,113,44,0.3)] text-lg">
                    Connect via WhatsApp →
                </button>
            </form>
        </div>
        <div id="loading-state" class="hidden flex-col items-center py-12">
            <div class="w-16 h-16 border-4 border-gray-700 rounded-full relative mb-6">
                <div class="absolute inset-0 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
            </div>
            <h3 class="text-xl font-bold text-white">Processing Order...</h3>
            <p class="text-accent text-sm mt-2 animate-pulse">Redirecting to WhatsApp...</p>
        </div>
    </div>
</div>

<!-- BOTTOM CTA -->
<section class="py-28 px-4 text-center relative overflow-hidden bg-gradient-to-b from-[#06050f] to-[#0d0b23]">
    <div class="absolute inset-0 bg-accent/5"></div>
    <div class="relative z-10 max-w-4xl mx-auto reveal-block">
        <h2 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">Ready to Build <br><span class="text-accent">Something Great?</span></h2>
        <p class="text-xl text-gray-400 mb-10">No fluff. No delays. Just results that move your business forward.</p>
        <button onclick="openOrderModal(null,null,null,null)" class="bg-accent hover:bg-orange-600 text-white px-14 py-6 rounded-full text-2xl font-bold hover:scale-105 transition-all shadow-[0_0_50px_rgba(240,113,44,0.4)]">
            Let's Talk Business
        </button>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
gsap.registerPlugin(ScrollTrigger);
const BASE = '<?= BASE_URL ?>';

/* ── Hero Animations ── */
gsap.timeline()
    .from('.hero-badge',  { y:20, opacity:0, duration:.6 })
    .from('.hero-title',  { y:50, opacity:0, duration:.8, ease:'power3.out' }, '-=.3')
    .from('.hero-sub',    { y:30, opacity:0, duration:.7 }, '-=.4')
    .from('.hero-cta',    { y:20, opacity:0, duration:.6 }, '-=.4')
    .from('.hero-stats',  { y:20, opacity:0, duration:.6 }, '-=.3');

/* ── Scroll Reveals ── */
gsap.utils.toArray('.reveal-block').forEach(el => {
    gsap.from(el, { scrollTrigger:{ trigger:el, start:'top 85%' }, y:50, opacity:0, duration:1, ease:'power3.out' });
});
gsap.utils.toArray('.bento-card').forEach((el,i) => {
    gsap.from(el, {
        scrollTrigger:{ trigger:el, start:'top 88%' },
        y:60, opacity:0, duration:.8, delay: i * .07, ease:'power3.out'
    });
});

/* ── Hover Tilt Effect ── */
document.querySelectorAll('.bento-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width  - .5;
        const y = (e.clientY - r.top)  / r.height - .5;
        gsap.to(card, { rotateX: -y*8, rotateY: x*8, duration:.3, ease:'power2.out', transformPerspective:800 });
    });
    card.addEventListener('mouseleave', () => {
        gsap.to(card, { rotateX:0, rotateY:0, duration:.5, ease:'power2.out' });
    });
});

/* ── Pricing Modal ── */
function openPricingModal(serviceId, serviceTitle) {
    document.getElementById('pricing-modal-title').textContent = serviceTitle;
    document.getElementById('pricing-modal').classList.remove('hidden');
    document.getElementById('pricing-modal-body').innerHTML = '<div class="flex justify-center py-8"><div class="w-10 h-10 border-2 border-accent border-t-transparent rounded-full animate-spin"></div></div>';

    gsap.fromTo('#pricing-modal > div:last-child',
        { y:60, opacity:0, scale:.97 },
        { y:0, opacity:1, scale:1, duration:.5, ease:'power4.out' }
    );

    fetch(BASE + 'api.php?action=get_packages&service_id=' + serviceId)
        .then(r => r.json())
        .then(packages => {
            if (!packages.length) {
                document.getElementById('pricing-modal-body').innerHTML = '<p class="text-center text-gray-400 py-8">No packages available yet. Contact us for a custom quote.</p>';
                return;
            }
            let html = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">';
            packages.forEach((pkg, i) => {
                const features = JSON.parse(pkg.features || '[]');
                const isPopular = i === 1 && packages.length > 1;
                html += `
                <div class="relative rounded-xl border p-6 flex flex-col transition-all hover:-translate-y-1 ${isPopular ? 'border-accent bg-accent/10 shadow-[0_0_30px_rgba(240,113,44,0.2)]' : 'border-white/10 bg-white/5'}">
                    ${isPopular ? '<div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-accent text-white text-xs font-bold px-4 py-1 rounded-full">POPULAR</div>' : ''}
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">${pkg.type}</p>
                    <h4 class="text-xl font-extrabold text-white mb-2">${pkg.name}</h4>
                    <p class="text-4xl font-extrabold text-white mb-1">$${parseFloat(pkg.price).toFixed(0)}</p>
                    <p class="text-gray-500 text-xs mb-6">/${pkg.type === 'One-Time' ? 'one-time' : pkg.type.toLowerCase()}</p>
                    <ul class="space-y-3 mb-8 flex-1">
                        ${features.map(f => `<li class="flex items-start gap-2 text-sm text-gray-300"><svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>${f}</li>`).join('')}
                    </ul>
                    <button onclick="selectPackage(${pkg.id},'${serviceTitle.replace(/'/g,"\\'")}','${pkg.name.replace(/'/g,"\\'")}','${pkg.type}')" 
                        class="w-full py-3 rounded-xl font-bold text-sm transition-all ${isPopular ? 'bg-accent hover:bg-orange-600 text-white shadow-accent-glow' : 'bg-white/10 hover:bg-white/20 text-white'}">
                        Select This Package
                    </button>
                </div>`;
            });
            html += '</div><p class="text-center text-gray-500 text-sm mt-6">Need something custom? <button onclick="selectPackage(null,\'' + serviceTitle.replace(/'/g,"\\'") + '\',null,null)" class="text-accent underline">Request a Custom Quote →</button></p>';
            document.getElementById('pricing-modal-body').innerHTML = html;
        });
}

function closePricingModal() { document.getElementById('pricing-modal').classList.add('hidden'); }

function selectPackage(pkgId, serviceName, pkgName, pkgType) {
    closePricingModal();
    openOrderModal(serviceName, pkgId, pkgName, pkgType);
}

/* ── Order Modal ── */
function openOrderModal(serviceName, pkgId, pkgName, pkgType) {
    document.getElementById('f_service_name').value = serviceName || '';
    document.getElementById('f_package_id').value   = pkgId || '';
    document.getElementById('f_package_name').value = pkgName || '';
    document.getElementById('f_package_type').value = pkgType || '';

    const summary = document.getElementById('package-summary');
    const summaryText = document.getElementById('pkg-summary-text');
    if (pkgName) {
        summaryText.textContent = pkgName + (pkgType ? ' · ' + pkgType : '');
        summary.classList.remove('hidden');
    } else {
        summary.classList.add('hidden');
    }

    document.getElementById('order-modal').classList.remove('hidden');
    gsap.fromTo('#modal-content', { y:50, opacity:0, scale:.96 }, { y:0, opacity:1, scale:1, duration:.45, ease:'power4.out' });
}

function closeOrderModal() {
    const mc = document.getElementById('modal-content');
    gsap.to(mc, { y:30, opacity:0, scale:.96, duration:.3, ease:'power2.in', onComplete: () => {
        document.getElementById('order-modal').classList.add('hidden');
        document.getElementById('form-state').classList.remove('hidden');
        document.getElementById('loading-state').classList.add('hidden');
        mc.style.cssText = '';
    }});
}

function submitOrder(e) {
    e.preventDefault();
    gsap.to('#form-state', { opacity:0, duration:.3, onComplete: () => {
        document.getElementById('form-state').classList.add('hidden');
        const ls = document.getElementById('loading-state');
        ls.classList.remove('hidden');
        gsap.from(ls, { opacity:0, scale:.9, duration:.3 });
    }});

    const fd = new FormData(document.getElementById('order-form'));
    fetch(BASE + 'api.php?action=order', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => { window.location.href = data.redirect_url; }, 1500);
            } else {
                alert('Error: ' + data.message);
                closeOrderModal();
            }
        })
        .catch(() => { alert('Something went wrong!'); closeOrderModal(); });
}
</script>
