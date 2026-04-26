<?php
// home.php — Part A: Hero + Services Grid
require_once __DIR__ . '/../../app/Modules/Package/PackageHandler.php';
$ph = new PackageHandler();
$allSvcs = $ph->getServicesWithPackages();

// Group by category
$grouped = [];
foreach ($allSvcs as $s) { $grouped[$s['category']][] = $s; }

// SVG icon paths by key
$svgPaths = [
    'globe'  => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
    'server' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01',
    'users'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'school' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
    'pos'    => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    'cart'   => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
    'logo'   => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
    'poster' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
    'video'  => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
    'print'  => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z',
    'ads'    => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
    'search' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
    'social' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z',
    'content'=> 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
];
$catColors = [
    'Software'  => ['ring'=>'ring-violet-500/40','icon'=>'bg-violet-500/20 text-violet-300','badge'=>'bg-violet-500/20 text-violet-300','glow'=>'shadow-violet-500/10'],
    'Design'    => ['ring'=>'ring-pink-500/40',  'icon'=>'bg-pink-500/20 text-pink-300',    'badge'=>'bg-pink-500/20 text-pink-300',    'glow'=>'shadow-pink-500/10'],
    'Marketing' => ['ring'=>'ring-accent/40',    'icon'=>'bg-accent/20 text-orange-300',    'badge'=>'bg-accent/20 text-orange-300',    'glow'=>'shadow-accent/10'],
];
?>

<!-- ═══ HERO ═══════════════════════════════════════════════════════════ -->
<section class="relative min-h-screen flex items-center justify-center text-center px-4 overflow-hidden">
    <div class="absolute inset-0" style="background:linear-gradient(135deg,#06050f 0%,#0d0826 50%,#06050f 100%)"></div>
    <div class="absolute top-1/3 left-1/4 w-[600px] h-[600px] bg-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/5 w-[400px] h-[400px] bg-violet-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.03]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 max-w-5xl mx-auto pt-20">
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-sm mb-8 hero-badge">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs text-gray-300 font-semibold tracking-[0.2em] uppercase">Open for New Projects</span>
        </div>

        <h1 class="text-6xl md:text-8xl font-black text-white mb-6 leading-[1.05] hero-title">
            Your Business.<br>
            <span class="text-transparent bg-clip-text" style="background-image:linear-gradient(135deg,#F0712C,#f59e0b)">Our Expertise.</span>
        </h1>

        <p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-12 leading-relaxed hero-sub">
            From custom software to brand design, video ads to performance marketing —
            we handle every digital need for your business under one roof.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20 hero-cta">
            <a href="#services" class="px-10 py-5 rounded-2xl font-bold text-lg text-white transition-all hover:scale-105" style="background:linear-gradient(135deg,#F0712C,#ea580c);box-shadow:0 0 40px rgba(240,113,44,0.4)">
                See All Services ↓
            </a>
            <a href="#portfolio" class="px-10 py-5 rounded-2xl font-bold text-lg text-white border border-white/20 hover:bg-white/10 transition-all backdrop-blur-sm">
                View Our Work
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-6 border-t border-white/10 pt-10 hero-stats">
            <?php foreach ([['150+','Projects Delivered'],['50+','Happy Clients'],['5+','Years Experience']] as $s): ?>
            <div>
                <div class="text-3xl md:text-5xl font-black text-white mb-1"><?= $s[0] ?></div>
                <div class="text-sm text-gray-500"><?= $s[1] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══ SERVICES BY CATEGORY ═══════════════════════════════════════════ -->
<section id="services" class="py-28 px-4 md:px-10" style="background:#08071a">
<div class="max-w-7xl mx-auto">

    <div class="text-center mb-20 reveal">
        <span class="text-xs font-black tracking-[0.3em] uppercase text-accent mb-3 block">Complete Service Suite</span>
        <h2 class="text-4xl md:text-6xl font-black text-white mb-4">Everything Your <span class="text-transparent bg-clip-text" style="background-image:linear-gradient(135deg,#F0712C,#f59e0b)">Business Needs</span></h2>
        <p class="text-gray-500 max-w-xl mx-auto">Click any service card to view detailed packages and pricing — instantly.</p>
    </div>

    <?php foreach ($grouped as $cat => $svcs):
        $c = $catColors[$cat] ?? $catColors['Marketing'];
    ?>
    <div class="mb-20">
        <div class="flex items-center gap-4 mb-8 reveal">
            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider <?= $c['badge'] ?>"><?= $cat ?></span>
            <div class="flex-1 h-px bg-white/5"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($svcs as $i => $svc):
            $path = $svgPaths[$svc['icon_key']] ?? $svgPaths['globe'];
            $minPrice = !empty($svc['packages']) ? min(array_column($svc['packages'],'price')) : null;
        ?>
        <div class="bento-card group relative rounded-2xl border border-white/8 bg-white/[0.03] hover:bg-white/[0.06] p-7 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:<?= $c['ring'] ?> hover:ring-1 hover:shadow-xl <?= $c['glow'] ?>"
             onclick="openPricingModal(<?= $svc['id'] ?>,'<?= addslashes(htmlspecialchars($svc['title'])) ?>')">
            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl <?= $c['icon'] ?> flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-accent transition-colors"><?= htmlspecialchars($svc['title']) ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6"><?= htmlspecialchars($svc['description']) ?></p>
                <div class="flex items-center justify-between">
                    <?php if ($minPrice): ?>
                    <span class="text-sm text-gray-600">From <strong class="text-white text-base">$<?= number_format($minPrice,0) ?></strong></span>
                    <?php endif; ?>
                    <span class="text-xs font-bold text-accent flex items-center gap-1 group-hover:gap-2 transition-all">
                        View Packages <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>
</section>

<?php require_once __DIR__ . '/home_part2.php'; ?>
