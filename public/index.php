<?php
session_start();

require_once __DIR__ . '/../app/Core/Config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/InitDB.php';

// Initialize DB schema automatically
InitDB::setup();

// Simple routing mechanism
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$page = empty($urlParts[0]) ? 'home' : $urlParts[0];

// Handle API requests
if ($page === 'api') {
    require_once __DIR__ . '/api.php';
    exit;
}

// Basic Page Routing
$pageFile = __DIR__ . '/../views/pages/' . $page . '.php';

if (file_exists($pageFile)) {
    require_once __DIR__ . '/../views/layouts/header.php';
    require_once $pageFile;
    require_once __DIR__ . '/../views/layouts/footer.php';
} else {
    // 404 Not Found
    header("HTTP/1.0 404 Not Found");
    require_once __DIR__ . '/../views/layouts/header.php';
    echo "<div class='min-h-[70vh] flex flex-col items-center justify-center text-white'>
            <h1 class='text-6xl font-bold text-accent mb-4'>404</h1>
            <h2 class='text-2xl'>Page Not Found</h2>
            <a href='" . BASE_URL . "' class='mt-6 px-6 py-2 bg-white text-primary font-semibold rounded hover:bg-gray-200 transition-colors'>Go Home</a>
          </div>";
    require_once __DIR__ . '/../views/layouts/footer.php';
}
