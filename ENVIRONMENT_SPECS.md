# ⚙️ PATH & ENVIRONMENT SPECIFICATION (For the Agent)

## Current Context
*   **Domain:** nextpixelz.net
*   **Root Directory (Server):** `/home/seotopra/nextpixelz.net/`
*   **Localhost (XAMPP):** `C:/xampp/htdocs/nextpixelz.net/`

## Agent Instructions

### 1. Strict Path Isolation
এজেন্টকে নির্দেশ দিন যেন সে সমস্ত Relative Paths ব্যবহার করে। `/home/seotopra/` এই হার্ডকোডেড পাথ ব্যবহার না করে পিএইচপি-র `__DIR__` অথবা `$_SERVER['DOCUMENT_ROOT']` ব্যবহার করতে হবে। এতে করে লোকাল থেকে সার্ভারে ফাইল ট্রান্সফার করলে কোনো পাথ এরর (Path Error) হবে না।

### 2. Configuration
`app/Core/Config.php` ফাইলে একটি সুইচ (Switch) থাকতে হবে:

```php
define('ENV', 'local'); // Change to 'production' when uploading to nextpixelz.net

if (ENV === 'local') {
    define('BASE_URL', 'http://localhost/nextpixelz.net/');
} else {
    define('BASE_URL', 'https://nextpixelz.net/');
}
```

### 3. Permissions
সার্ভারে আপলোড করার সময় `uploads/` এবং `logs/` ফোল্ডারের পারমিশন অবশ্যই 0755 বা প্রয়োজনীয় ক্ষেত্রে 0775 রাখতে হবে (যেমনটি স্ক্রিনশটে nextpixelz.net ফোল্ডারের জন্য 0750 দেখা যাচ্ছে)।
