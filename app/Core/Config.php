<?php
define('ENV', 'local'); // Change to 'production' when uploading to nextpixelz.net

if (ENV === 'local') {
    define('BASE_URL', 'http://localhost/nextpixelz.net/');
} else {
    define('BASE_URL', 'https://nextpixelz.net/');
}

// Database Credentials
if (ENV === 'local') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'nextpixelz_db'); // Local db name
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'seotopra_nextpixelz');
    define('DB_USER', 'seotopra_user'); 
    define('DB_PASS', 'placeholder_pass'); 
}
