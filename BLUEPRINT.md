# 🚀 SYSTEM ARCHITECTURE & DEVELOPMENT BLUEPRINT: NEXTPIXELZ ERP

*Target Agent:* Google Anti-gravity (Senior AI Engineer)
*Role:* Project Architect & Developer
*Objective:* Build a High-Performance, Modular Agency Management System with a World-Class UI/UX.

---

## 🏗️ 1. ARCHITECTURE PRINCIPLES (Core Logic)
* *Modularity:* Follow "One Module, One Function." All business logic must reside in `/app/Modules`.
* *Optimization:* Optimized for Shared Hosting. Minified Assets, WebP Images, and Minimal PHP overhead.
* *Security:* Zero-tolerance for SQL Injection. Use PDO with Prepared Statements.
* *UI/UX:* International Software Agency Standard. Dark/Emerald Theme, Glassmorphism, and GSAP animations.

---

## 📂 2. DIRECTORY STRUCTURE
```text
/root
├── /app
│   ├── /Core          # DB Connection (PDO), Session, Config
│   ├── /Modules
│   │   ├── /Order     # WhatsApp Redirection & Status Logic
│   │   ├── /Billing   # DomPDF Wrapper for Invoices/Quotations
│   │   ├── /Auth      # Simple Company-Name + Phone Login
│   │   └── /Loyalty   # Spinning Wheel & Wallet Credit Logic
│   └── /Utils         # Image Optimizer, String Helpers, PDF Generator
├── /public
│   ├── /assets        # Tailwind CSS, GSAP, Swiper.js, Custom JS
│   ├── /uploads       # Securely stored Payment Slips & Invoices
│   └── index.php      # Entry point
├── /views
│   ├── /layouts       # Header.php, Footer.php, Sidebar.php
│   ├── /sections      # Dynamic Hero, Service Grid, Workflow, FAQ
│   └── /pages         # Home, Dashboard, Profile, Order-Details
└── .htaccess          # SEO Friendly URLs & Security Headers
```

## 💾 3. DATABASE SCHEMA (MySQL)
*   **Table `users`:** `id, company_name, phone, password_hash, wallet_credits, role (admin/client), created_at`
*   **Table `services`:** `id, title, description, category, icon_svg, price_estimate, is_active`
*   **Table `orders`:** `id, user_id, service_id, status (Pending, Ongoing, Completed, Cancelled), total_amount, whatsapp_thread_id, created_at`
*   **Table `billing`:** `id, order_id, type (Invoice/Quotation), file_path, status (Paid/Unpaid), generated_at`

## 🖥️ 4. DETAILED PAGE & SECTION GUIDELINES
### A. Home Page (index.php)
*   **Hero Section (Dynamic Slider):**
    *   **Source:** Fetch top 5 active services from services table.
    *   **Animation:** GSAP Stagger effect on Headline and CTA button.
    *   **UX:** Smooth transition with Swiper.js. "Asian English" copy focus.
*   **Service Grid:**
    *   Display all 8 services (Ads, SEO, Design, etc.) as interactive cards.
    *   **Hover Effect:** Tailwind group-hover scale and shadow elevation.
*   **Client Process Section:**
    *   **Visual Timeline:** Choose -> Order -> WhatsApp -> Done.

### B. The WhatsApp Order Flow (No Gateway)
*   **Action:** User clicks "Order Service".
*   **Logic:** If not logged in, show "Quick Registration" (Name + Phone).
*   **Processing:** Create record in orders table with status 'Pending'. Generate unique Order ID (e.g., #AQ-1002).
*   **Redirect:** Redirect to: `https://wa.me/[YourNumber]?text=New%20Order%20from%20[CompanyName].%20ID:%20[OrderID].%20Service:%20[ServiceTitle].`

### C. Client Dashboard (Portal)
*   **Identity:** Access via Company Name + Phone Number.
*   **Order Tracking:** Status bar showing the progress of their specific task.
*   **Billing Center:** One-click download for PDF Invoices & Quotations.
*   **Loyalty Hub:** Spinning Wheel: Triggered only after an order is marked 'Completed'.
*   **Credits:** Display current wallet balance for future discounts.

## ⚙️ 5. ADMIN PANEL (Business Brain)
*   **Order Manager:** Change status, update price, and link to WhatsApp conversation.
*   **Billing Engine:**
    *   **Input:** Line items, Quantity, Price.
    *   **Output:** Professional PDF with Agency Branding using DomPDF.
*   **Financial Overview:** Simple Dashboard showing Monthly Profit/Loss and Pending Payments.

## 📝 6. CONTENT & CODING STANDARDS
*   **Language:** Use "Asian English" - Simplified, clear, and action-oriented. (e.g., Use "Boost your sales" instead of "Enhance your revenue streams").
*   **CSS:** Strictly Tailwind CSS utility classes. Avoid custom CSS files.
*   **JS:** GSAP for high-end animations; Vanilla JS for logic.
*   **Git:** Always work in branches. Commit message format: `feat: [feature name]`, `fix: [bug name]`.

## 🛑 7. FINAL INSTRUCTION FOR AGENT
"You must execute this project module-by-module. Do not skip the Core setup. Ensure that the 'WhatsApp Integration' and 'PDF Invoice Generation' are the most robust parts of the system. Maintain a Senior Architect's mindset throughout."
