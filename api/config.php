<?php
/**
 * Database Configuration
 * SOFTNETIX Contact Form Backend
 */

// Prevent direct access
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

// Database Configuration for WAMP Server
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Default WAMP username
define('DB_PASS', '');               // Default WAMP password (empty)
define('DB_NAME', 'softnetix_db');   // Database name

// Application Settings
define('SITE_NAME', 'SOFTNETIX');
define('ADMIN_EMAIL', 'admin@softnetix.com'); // Change this to your email
define('TIMEZONE', 'Asia/Kolkata');

// Security Settings
define('ADMIN_USERNAME', 'admin');    // Change this
define('ADMIN_PASSWORD', 'Admin@123'); // Change this to a strong password

// Email Settings (Optional - for email notifications)
define('SMTP_ENABLED', false);        // Set to true if you want email notifications
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_FROM_EMAIL', 'noreply@softnetix.com');
define('SMTP_FROM_NAME', 'SOFTNETIX');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Database Connection
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// Helper Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone($phone) {
    // Remove all non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    // Check if length is between 10 and 15 digits
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>
