<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextPixelz ERP</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1D1B4D',
                        accent: '#F0712C',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1D1B4D;
            color: #E0E0E0;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .shadow-accent-glow {
            box-shadow: 0 0 15px rgba(240, 113, 44, 0.5);
            transition: all 0.3s ease;
        }
        .shadow-accent-glow:hover {
            box-shadow: 0 0 25px rgba(240, 113, 44, 0.8);
        }
    </style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
</head>
<body class="antialiased">
    <!-- Navbar -->
    <nav class="glass-panel sticky top-0 z-50 px-6 py-4 flex justify-between items-center">
        <a href="<?= BASE_URL ?>" class="text-2xl font-bold text-white flex items-center gap-2">
            <!-- Triangle logo conceptual -->
            <div class="w-0 h-0 border-l-[12px] border-l-transparent border-r-[12px] border-r-transparent border-b-[20px] border-b-accent"></div>
            NextPixelz
        </a>
        <div class="hidden md:flex space-x-6 items-center">
            <a href="<?= BASE_URL ?>index.php" class="hover:text-accent transition-colors">Home</a>
            <a href="<?= BASE_URL ?>dashboard.php" class="hover:text-accent transition-colors">Client Portal</a>
            <button onclick="if(typeof openOrderModal === 'function') { openOrderModal('General Service'); } else { window.location.href='<?= BASE_URL ?>index.php'; }" class="bg-accent text-white px-5 py-2 rounded-md font-semibold hover:bg-orange-600 shadow-accent-glow">Start Order</button>
        </div>
    </nav>
    <main>
