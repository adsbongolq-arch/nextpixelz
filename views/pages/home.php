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
        <a href="<?= BASE_URL ?>order" class="bg-accent text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-orange-600 transition-all shadow-accent-glow inline-block">
            Boost Your Sales Today
        </a>
    </div>
</section>

<!-- Page Specific JS -->
<script>
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
