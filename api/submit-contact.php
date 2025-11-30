<?php
/**
 * Contact Form Submission Handler
 * SOFTNETIX
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// CORS headers (adjust origin for production)
header('Access-Control-Allow-Origin: *'); // Change * to your domain in production
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

// Define secure access
define('SECURE_ACCESS', true);

// Include configuration
require_once 'config.php';

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Validate JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        json_response(false, 'Invalid JSON data');
    }
    
    // Extract and sanitize data
    $name = isset($data['name']) ? sanitize_input($data['name']) : '';
    $email = isset($data['email']) ? sanitize_input($data['email']) : '';
    $phone = isset($data['phone']) ? sanitize_input($data['phone']) : '';
    $message = isset($data['message']) ? sanitize_input($data['message']) : '';
    
    // Validation
    $errors = [];
    
    // Validate name
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must not exceed 100 characters';
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!validate_email($email)) {
        $errors[] = 'Invalid email address';
    }
    
    // Validate phone (optional but if provided, must be valid)
    if (!empty($phone) && !validate_phone($phone)) {
        $errors[] = 'Invalid phone number';
    }
    
    // Validate message
    if (empty($message)) {
        $errors[] = 'Message is required';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    } elseif (strlen($message) > 1000) {
        $errors[] = 'Message must not exceed 1000 characters';
    }
    
    // Return errors if any
    if (!empty($errors)) {
        json_response(false, implode(', ', $errors));
    }
    
    // Get database connection
    $db = Database::getInstance()->getConnection();
    
    // Prepare SQL statement
    $sql = "INSERT INTO contact_submissions (name, email, phone, message, ip_address, user_agent, submitted_at) 
            VALUES (:name, :email, :phone, :message, :ip_address, :user_agent, NOW())";
    
    $stmt = $db->prepare($sql);
    
    // Get client IP and user agent
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    // Execute statement
    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':message' => $message,
        ':ip_address' => $ip_address,
        ':user_agent' => $user_agent
    ]);
    
    if ($result) {
        $submission_id = $db->lastInsertId();
        
        // Optional: Send email notification
        if (SMTP_ENABLED) {
            send_email_notification($name, $email, $phone, $message);
        }
        
        // Log success
        error_log("Contact form submission successful - ID: $submission_id");
        
        json_response(true, 'Thank you! Your message has been received. We will get back to you soon.', [
            'submission_id' => $submission_id
        ]);
    } else {
        throw new Exception('Failed to save submission');
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    json_response(false, 'A database error occurred. Please try again later.');
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    json_response(false, 'An error occurred. Please try again later.');
}

/**
 * Send email notification (optional)
 */
function send_email_notification($name, $email, $phone, $message) {
    // Simple email notification
    $to = ADMIN_EMAIL;
    $subject = "New Contact Form Submission - " . SITE_NAME;
    
    $email_message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #4FD1C5; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #333; }
            .value { color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Name:</span>
                    <span class='value'>" . htmlspecialchars($name) . "</span>
                </div>
                <div class='field'>
                    <span class='label'>Email:</span>
                    <span class='value'>" . htmlspecialchars($email) . "</span>
                </div>
                <div class='field'>
                    <span class='label'>Phone:</span>
                    <span class='value'>" . htmlspecialchars($phone) . "</span>
                </div>
                <div class='field'>
                    <span class='label'>Message:</span>
                    <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
                <div class='field'>
                    <span class='label'>Submitted:</span>
                    <span class='value'>" . date('Y-m-d H:i:s') . "</span>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">" . "\r\n";
    
    @mail($to, $subject, $email_message, $headers);
}
?>
