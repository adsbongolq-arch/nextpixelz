<?php
// home_part2.php — Portfolio + Modals + JS
$db2 = Database::getInstance()->getConnection();
$portfolio = $db2->query("SELECT * FROM portfolio WHERE is_active=1 ORDER BY sort_order ASC")->fetchAll();
$portCats = array_unique(array_column($portfolio,'category'));

// Auth check for modal
$loggedIn = AuthHandler::isLoggedIn();
?>

<!-- ═══ PORTFOLIO ══════════════════════════════════════════════════════ -->
<section id="portfolio" class="py-28 px-4 md:px-10 border-t border-white/5" style="background:#06050f">
<div class="max-w-7xl mx-auto">
    <div class="text-center mb-16 reveal">
        <span class="text-xs font-black tracking-[0.3em] uppercase text-accent mb-3 block">Our Work</span>
        <h2 class="text-4xl md:text-6xl font-black text-white mb-4">Sample <span class="text-transparent bg-clip-text" style="background-image:linear-gradient(135deg,#F0712C,#f59e0b)">Projects</span></h2>
        <p class="text-gray-500 max-w-xl mx-auto">Real results for real businesses. Click to explore each project.</p>
    </div>

    <!-- Filter tabs -->
    <div class="flex flex-wrap justify-center gap-3 mb-12 reveal">
        <button class="port-filter active px-5 py-2 rounded-full text-sm font-bold border border-accent bg-accent/20 text-accent" data-cat="all">All Work</button>
        <?php foreach ($portCats as $pc): ?>
        <button class="port-filter px-5 py-2 rounded-full text-sm font-bold border border-white/10 text-gray-400 hover:border-accent hover:text-accent transition-colors" data-cat="<?= $pc ?>"><?= $pc ?></button>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($portfolio as $item): ?>
    <div class="port-item group rounded-2xl overflow-hidden border border-white/8 bg-white/[0.02] hover:border-accent/40 transition-all hover:-translate-y-1 duration-300 reveal" data-cat="<?= $item['category'] ?>">
        <div class="relative overflow-hidden h-52">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute top-4 right-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-black/60 text-accent backdrop-blur-sm border border-accent/30"><?= $item['category'] ?></span>
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-bold text-white mb-2"><?= htmlspecialchars($item['title']) ?></h3>
            <p class="text-gray-500 text-sm mb-4 leading-relaxed"><?= htmlspecialchars($item['description']) ?></p>
            <div class="flex flex-wrap gap-2 mb-5">
                <?php foreach (explode(',',$item['tech_tags']) as $tag): ?>
                <span class="px-2 py-1 rounded bg-white/5 text-xs text-gray-400 border border-white/8"><?= trim($tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php if ($item['demo_url'] && $item['demo_url'] !== '#'): ?>
            <a href="<?= htmlspecialchars($item['demo_url']) ?>" target="_blank" class="text-accent text-sm font-bold hover:text-white transition-colors flex items-center gap-1">
                View Live <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
</section>

<!-- ═══ CTA BANNER ═════════════════════════════════════════════════════ -->
<section class="py-24 px-4 text-center relative overflow-hidden" style="background:linear-gradient(135deg,#0d0826,#06050f)">
    <div class="absolute inset-0 opacity-5" style="background-image:radial-gradient(circle,#F0712C 1px,transparent 1px);background-size:50px 50px"></div>
    <div class="absolute inset-0 bg-accent/5"></div>
    <div class="relative z-10 max-w-3xl mx-auto reveal">
        <h2 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">Ready to Build <span class="text-accent">Something Great?</span></h2>
        <p class="text-xl text-gray-400 mb-10">No templates. No limits. Just custom solutions that deliver real results.</p>
        <button onclick="openOrderModal(null,null,null,null)" class="px-14 py-6 rounded-2xl text-2xl font-black text-white transition-all hover:scale-105" style="background:linear-gradient(135deg,#F0712C,#ea580c);box-shadow:0 0 50px rgba(240,113,44,0.4)">
            Start a Project Today →
        </button>
    </div>
</section>

<!-- ═══ PRICING MODAL ══════════════════════════════════════════════════ -->
<div id="pricing-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closePricingModal()"></div>
    <div class="relative w-full max-w-5xl max-h-[88vh] overflow-y-auto rounded-2xl border border-white/10" style="background:#0d0b23">
        <div class="sticky top-0 border-b border-white/10 px-8 py-5 flex justify-between items-center z-10" style="background:#0d0b23">
            <div>
                <span class="text-accent text-xs font-black uppercase tracking-widest">Packages & Pricing</span>
                <h3 id="pm-title" class="text-2xl font-black text-white mt-1"></h3>
            </div>
            <button onclick="closePricingModal()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="pm-body" class="p-8">
            <div class="flex justify-center py-8"><div class="w-10 h-10 border-2 border-accent border-t-transparent rounded-full animate-spin"></div></div>
        </div>
    </div>
</div>

<!-- ═══ ORDER MODAL ════════════════════════════════════════════════════ -->
<div id="order-modal" class="fixed inset-0 z-[300] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeOrderModal()"></div>
    <div id="modal-content" class="relative w-full max-w-md rounded-2xl border border-white/10 p-8" style="background:#0d0b23">
        <div id="form-state">
            <div class="w-14 h-14 rounded-2xl bg-accent/20 flex items-center justify-center mb-5">
                <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-1">Start Your Project</h3>
            <p class="text-gray-500 text-sm mb-6">We'll contact you on WhatsApp within minutes.</p>
            <form id="order-form" onsubmit="submitOrder(event)">
                <input type="hidden" id="f_service" name="service_name">
                <input type="hidden" id="f_pkg_id"   name="package_id">
                <input type="hidden" id="f_pkg_name" name="package_name">
                <input type="hidden" id="f_pkg_type" name="package_type">
                <?php if (!$loggedIn): ?>
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Company / Your Name</label>
                    <input type="text" name="company_name" required placeholder="NextPixelz Agency" class="w-full rounded-xl px-4 py-4 text-white text-sm outline-none transition-colors border border-white/10 bg-white/5 focus:border-accent">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">WhatsApp Number</label>
                    <input type="tel" name="phone" required placeholder="01XXXXXXXXX" class="w-full rounded-xl px-4 py-4 text-white text-sm outline-none transition-colors border border-white/10 bg-white/5 focus:border-accent">
                </div>
                <?php else: ?>
                <div class="rounded-xl p-4 mb-6 border border-emerald-500/20 bg-emerald-500/5">
                    <p class="text-xs text-gray-500 mb-1">Ordering as</p>
                    <p class="text-white font-bold"><?= htmlspecialchars($_SESSION['company_name']) ?></p>
                </div>
                <?php endif; ?>
                <div id="pkg-summary" class="hidden rounded-xl p-4 mb-5 border border-accent/20 bg-accent/5">
                    <p class="text-xs text-gray-500 mb-1">Selected Package</p>
                    <p id="pkg-summary-text" class="text-white font-bold text-sm"></p>
                </div>
                <button type="submit" class="w-full py-4 rounded-xl font-black text-lg text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#F0712C,#ea580c);box-shadow:0 0 20px rgba(240,113,44,0.3)">
                    Connect via WhatsApp →
                </button>
            </form>
        </div>
        <div id="loading-state" class="hidden flex-col items-center py-12">
            <div class="relative w-16 h-16 mb-5">
                <div class="absolute inset-0 border-4 border-white/10 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
            </div>
            <h3 class="text-xl font-black text-white">Processing...</h3>
            <p class="text-accent text-sm mt-2 animate-pulse">Redirecting to WhatsApp</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
gsap.registerPlugin(ScrollTrigger);
const BASE = '<?= BASE_URL ?>';

// Hero
gsap.timeline()
    .from('.hero-badge',  {y:20,opacity:0,duration:.6})
    .from('.hero-title',  {y:60,opacity:0,duration:1,ease:'power3.out'},'-=.3')
    .from('.hero-sub',    {y:30,opacity:0,duration:.8},'-=.4')
    .from('.hero-cta',    {y:20,opacity:0,duration:.6},'-=.4')
    .from('.hero-stats',  {y:20,opacity:0,duration:.6},'-=.3');

// Scroll reveals
gsap.utils.toArray('.reveal').forEach(el => {
    gsap.from(el,{scrollTrigger:{trigger:el,start:'top 88%'},y:50,opacity:0,duration:.9,ease:'power3.out'});
});
gsap.utils.toArray('.bento-card').forEach((el,i) => {
    gsap.from(el,{scrollTrigger:{trigger:el,start:'top 90%'},y:50,opacity:0,duration:.7,delay:i*.06,ease:'power3.out'});
});
gsap.utils.toArray('.port-item').forEach((el,i) => {
    gsap.from(el,{scrollTrigger:{trigger:el,start:'top 90%'},y:40,opacity:0,duration:.7,delay:i*.08,ease:'power3.out'});
});

// Tilt effect
document.querySelectorAll('.bento-card').forEach(c => {
    c.addEventListener('mousemove',e=>{
        const r=c.getBoundingClientRect();
        gsap.to(c,{rotateX:-(((e.clientY-r.top)/r.height)-.5)*10,rotateY:(((e.clientX-r.left)/r.width)-.5)*10,duration:.3,transformPerspective:900});
    });
    c.addEventListener('mouseleave',()=>gsap.to(c,{rotateX:0,rotateY:0,duration:.5}));
});

// Portfolio filter
document.querySelectorAll('.port-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.port-filter').forEach(b => b.classList.remove('active','bg-accent/20','text-accent','border-accent'));
        this.classList.add('active','bg-accent/20','text-accent','border-accent');
        const cat = this.dataset.cat;
        document.querySelectorAll('.port-item').forEach(item => {
            const show = cat === 'all' || item.dataset.cat === cat;
            gsap.to(item, {opacity: show?1:0.2, scale: show?1:0.95, duration:.3});
        });
    });
});

// ── Pricing Modal ──
function openPricingModal(svcId, svcTitle) {
    document.getElementById('pm-title').textContent = svcTitle;
    document.getElementById('pm-body').innerHTML = '<div class="flex justify-center py-8"><div class="w-10 h-10 border-2 border-accent border-t-transparent rounded-full animate-spin"></div></div>';
    const modal = document.getElementById('pricing-modal');
    modal.classList.remove('hidden');
    gsap.fromTo(modal.querySelector('.relative'), {y:60,opacity:0,scale:.97},{y:0,opacity:1,scale:1,duration:.45,ease:'power4.out'});

    fetch(BASE+'api.php?action=get_packages&service_id='+svcId)
    .then(r=>r.json()).then(pkgs=>{
        if (!pkgs.length) {
            document.getElementById('pm-body').innerHTML = '<div class="text-center py-12 text-gray-500"><p class="text-lg font-bold text-white mb-2">Custom Pricing Available</p><p>Contact us for a tailored quote for this service.</p><button onclick="selectPackage(null,\''+svcTitle.replace(/'/g,"\\'")+'\')" class="mt-6 px-8 py-3 rounded-xl bg-accent text-white font-bold hover:bg-orange-600 transition-colors">Request Custom Quote →</button></div>';
            return;
        }
        let h = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">';
        pkgs.forEach((p,i)=>{
            const feats = JSON.parse(p.features||'[]');
            const pop = i===1 && pkgs.length>1;
            h+=`<div class="relative rounded-2xl border p-6 flex flex-col transition-all hover:-translate-y-1 ${pop?'border-accent bg-accent/10 shadow-[0_0_30px_rgba(240,113,44,0.2)]':'border-white/10 bg-white/5'}">
                ${pop?'<div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-accent text-white text-xs font-black px-4 py-1 rounded-full tracking-widest">POPULAR</div>':''}
                <p class="text-xs font-black uppercase tracking-widest text-gray-500 mb-1">${p.type}</p>
                <h4 class="text-xl font-black text-white mb-2">${p.name}</h4>
                <div class="flex items-end gap-1 mb-5"><span class="text-4xl font-black text-white">$${parseFloat(p.price).toFixed(0)}</span><span class="text-gray-500 text-sm mb-1">/${p.type==='One-Time'?'one-time':p.type.toLowerCase()}</span></div>
                <ul class="space-y-2.5 mb-7 flex-1">${feats.map(f=>`<li class="flex items-start gap-2 text-sm text-gray-300"><svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>${f}</li>`).join('')}</ul>
                <button onclick="selectPackage(${p.id},'${svcTitle.replace(/'/g,"\\'")}','${p.name.replace(/'/g,"\\'")}','${p.type}')" class="w-full py-3 rounded-xl font-black text-sm transition-all ${pop?'text-white hover:opacity-90':'bg-white/10 hover:bg-white/20 text-white'}" ${pop?'style="background:linear-gradient(135deg,#F0712C,#ea580c)"':''}>Select Package</button>
            </div>`;
        });
        h+='</div>';
        document.getElementById('pm-body').innerHTML = h;
    });
}
function closePricingModal(){ document.getElementById('pricing-modal').classList.add('hidden'); }
function selectPackage(id,svc,pkg,type){ closePricingModal(); openOrderModal(svc,id,pkg,type); }

// ── Order Modal ──
function openOrderModal(svc,pkgId,pkgName,pkgType){
    document.getElementById('f_service').value  = svc||'';
    document.getElementById('f_pkg_id').value   = pkgId||'';
    document.getElementById('f_pkg_name').value = pkgName||'';
    document.getElementById('f_pkg_type').value = pkgType||'';
    const sum=document.getElementById('pkg-summary');
    if(pkgName){ document.getElementById('pkg-summary-text').textContent=pkgName+(pkgType?' · '+pkgType:''); sum.classList.remove('hidden'); }
    else sum.classList.add('hidden');
    document.getElementById('order-modal').classList.remove('hidden');
    gsap.fromTo('#modal-content',{y:50,opacity:0,scale:.96},{y:0,opacity:1,scale:1,duration:.45,ease:'power4.out'});
}
function closeOrderModal(){
    const mc=document.getElementById('modal-content');
    gsap.to(mc,{y:30,opacity:0,scale:.96,duration:.3,ease:'power2.in',onComplete:()=>{
        document.getElementById('order-modal').classList.add('hidden');
        document.getElementById('form-state').classList.remove('hidden');
        document.getElementById('loading-state').classList.add('hidden');
        mc.style.cssText='';
    }});
}
function submitOrder(e){
    e.preventDefault();
    gsap.to('#form-state',{opacity:0,duration:.25,onComplete:()=>{
        document.getElementById('form-state').classList.add('hidden');
        const ls=document.getElementById('loading-state');
        ls.classList.remove('hidden');
        gsap.from(ls,{opacity:0,scale:.9,duration:.3});
    }});
    fetch(BASE+'api.php?action=order',{method:'POST',body:new FormData(document.getElementById('order-form'))})
    .then(r=>r.json()).then(d=>{
        if(d.success) setTimeout(()=>window.location.href=d.redirect_url,1500);
        else{ alert('Error: '+d.message); closeOrderModal(); }
    }).catch(()=>{ alert('Something went wrong!'); closeOrderModal(); });
}
</script>
