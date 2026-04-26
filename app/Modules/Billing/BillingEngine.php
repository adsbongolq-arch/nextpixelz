<?php
require_once __DIR__ . '/../../Core/Database.php';

// Try to include Dompdf (assuming it will be extracted to app/Utils/dompdf-3.1.5 or dompdf)
$autoloadPath = __DIR__ . '/../../Utils/dompdf/autoload.inc.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} elseif (file_exists(__DIR__ . '/../../Utils/dompdf-3.1.5/autoload.inc.php')) {
    require_once __DIR__ . '/../../Utils/dompdf-3.1.5/autoload.inc.php';
}

use Dompdf\Dompdf;
use Dompdf\Options;

class BillingEngine {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function generateInvoice($orderId) {
        if (!class_exists('Dompdf\Dompdf')) {
            return ['success' => false, 'message' => 'DomPDF is not installed yet. Please wait for setup to finish or run composer.'];
        }

        // Fetch Order Details
        $stmt = $this->db->prepare("
            SELECT o.*, u.company_name, u.phone 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = :id
        ");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch();

        if (!$order || $order['status'] !== 'Completed') {
            return ['success' => false, 'message' => 'Invalid or unapproved order. Must be Approved first.'];
        }

        // QR Code URL (User requested: "Hi NextPixelz, I have paid the invoice for Order #[Order_ID]. Please check.")
        $qrMessage = "Hi NextPixelz, I have paid the invoice for Order {$order['order_uid']}. Please check.";
        $qrWaLink = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . rawurlencode($qrMessage);
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrWaLink);

        // Generate HTML
        $html = $this->getInvoiceHtml($order, $qrImageUrl);

        // Setup DomPDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save PDF
        $fileName = 'INV-' . str_replace('#', '', $order['order_uid']) . '.pdf';
        $saveDir = __DIR__ . '/../../../public/uploads/invoices/';
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }
        $filePath = $saveDir . $fileName;
        file_put_contents($filePath, $dompdf->output());

        // Update billing table
        $dbPath = 'uploads/invoices/' . $fileName;
        $stmt = $this->db->prepare("INSERT INTO billing (order_id, type, file_path) VALUES (:order_id, 'Invoice', :file_path)");
        $stmt->execute([':order_id' => $orderId, ':file_path' => $dbPath]);

        return ['success' => true, 'file_url' => BASE_URL . $dbPath];
    }

    private function getInvoiceHtml($order, $qrImageUrl) {
        $date = date('F d, Y');
        $company = htmlspecialchars($order['company_name']);
        $service = htmlspecialchars($order['service_name']);
        $amount = number_format($order['total_amount'], 2);
        
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
                .header { border-bottom: 3px solid #1D1B4D; padding-bottom: 20px; margin-bottom: 30px; }
                .header h1 { color: #1D1B4D; margin: 0; font-size: 32px; }
                .header span { color: #F0712C; }
                .invoice-details { margin-bottom: 40px; }
                .invoice-details table { width: 100%; }
                .invoice-details td { vertical-align: top; }
                .text-right { text-align: right; }
                .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
                .items-table th { background-color: #1D1B4D; color: #fff; padding: 10px; text-align: left; }
                .items-table td { border-bottom: 1px solid #ddd; padding: 10px; }
                .total-row { font-weight: bold; font-size: 18px; color: #1D1B4D; }
                .footer { text-align: center; margin-top: 50px; font-size: 14px; color: #666; }
                .qr-code { text-align: center; margin-top: 30px; }
                .qr-code img { width: 100px; height: 100px; }
                .qr-text { font-size: 12px; color: #F0712C; font-weight: bold; margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <table style='width:100%'>
                    <tr>
                        <td>
                            <h1>Next<span>Pixelz</span></h1>
                            <p>info@nextpixelz.net | " . WHATSAPP_NUMBER . "</p>
                        </td>
                        <td class='text-right'>
                            <h2 style='color: #F0712C;'>INVOICE</h2>
                            <p><strong>Order ID:</strong> {$order['order_uid']}<br>
                            <strong>Date:</strong> {$date}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class='invoice-details'>
                <table>
                    <tr>
                        <td>
                            <strong>Bill To:</strong><br>
                            {$company}<br>
                            Phone: {$order['phone']}
                        </td>
                    </tr>
                </table>
            </div>

            <table class='items-table'>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class='text-right'>Amount (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$service}</td>
                        <td class='text-right'>\${$amount}</td>
                    </tr>
                    <tr class='total-row'>
                        <td class='text-right' style='padding-top: 20px;'>Total Due:</td>
                        <td class='text-right' style='padding-top: 20px;'>\${$amount}</td>
                    </tr>
                </tbody>
            </table>

            <div class='qr-code'>
                <p>Scan to notify us of your payment via WhatsApp:</p>
                <img src='{$qrImageUrl}' alt='WhatsApp QR'>
                <p class='qr-text'>Pay & Notify Instantly</p>
            </div>

            <div class='footer'>
                <p>Thank you for doing business with NextPixelz!</p>
            </div>
        </body>
        </html>
        ";
    }

    public function getInvoiceForOrder($orderId) {
        $stmt = $this->db->prepare("SELECT file_path FROM billing WHERE order_id = :id AND type = 'Invoice' ORDER BY id DESC LIMIT 1");
        $stmt->execute([':id' => $orderId]);
        $res = $stmt->fetch();
        return $res ? $res['file_path'] : null;
    }
}
