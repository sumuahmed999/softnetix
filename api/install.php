<?php
/**
 * Database Installation Script
 * Run this file once to create the database and tables
 */

define('SECURE_ACCESS', true);
require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFTNETIX - Database Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        
        .step {
            background: #f8f9fa;
            border-left: 4px solid #4FD1C5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .step h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .step p {
            color: #666;
            line-height: 1.6;
        }
        
        .success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .btn {
            background: linear-gradient(135deg, #4FD1C5, #63B3ED);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 SOFTNETIX Database Installation</h1>
        <p class="subtitle">Set up your contact form backend</p>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
            try {
                // Create database connection without database name
                $conn = new PDO(
                    "mysql:host=" . DB_HOST . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                // Create database
                $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo "<div class='success'>✓ Database '" . DB_NAME . "' created successfully!</div>";
                
                // Select database
                $conn->exec("USE " . DB_NAME);
                
                // Create contact_submissions table
                $sql = "CREATE TABLE IF NOT EXISTS contact_submissions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    phone VARCHAR(20),
                    message TEXT NOT NULL,
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
                    submitted_at DATETIME NOT NULL,
                    read_at DATETIME NULL,
                    INDEX idx_status (status),
                    INDEX idx_submitted_at (submitted_at),
                    INDEX idx_email (email)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                $conn->exec($sql);
                echo "<div class='success'>✓ Table 'contact_submissions' created successfully!</div>";
                
                // Create admin_users table
                $sql = "CREATE TABLE IF NOT EXISTS admin_users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(255),
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    last_login DATETIME NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                $conn->exec($sql);
                echo "<div class='success'>✓ Table 'admin_users' created successfully!</div>";
                
                // Insert default admin user
                $hashed_password = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT IGNORE INTO admin_users (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([ADMIN_USERNAME, $hashed_password, ADMIN_EMAIL]);
                
                if ($stmt->rowCount() > 0) {
                    echo "<div class='success'>✓ Default admin user created!</div>";
                }
                
                echo "<div class='success'><strong>🎉 Installation completed successfully!</strong></div>";
                echo "<div class='info-box'>";
                echo "<strong>Admin Credentials:</strong><br>";
                echo "Username: <code>" . ADMIN_USERNAME . "</code><br>";
                echo "Password: <code>" . ADMIN_PASSWORD . "</code><br>";
                echo "<br><strong>⚠️ Important:</strong> Please change these credentials in <code>api/config.php</code> for security!";
                echo "</div>";
                echo "<a href='../admin/' class='btn'>Go to Admin Dashboard</a>";
                
            } catch(PDOException $e) {
                echo "<div class='error'><strong>Error:</strong> " . $e->getMessage() . "</div>";
                echo "<div class='info-box'>";
                echo "<strong>Troubleshooting:</strong><br>";
                echo "1. Make sure WAMP server is running<br>";
                echo "2. Check database credentials in <code>api/config.php</code><br>";
                echo "3. Ensure MySQL service is started<br>";
                echo "</div>";
            }
        } else {
        ?>
        
        <div class="step">
            <h3>Step 1: Prerequisites</h3>
            <p>Make sure your WAMP server is running and MySQL service is started.</p>
        </div>
        
        <div class="step">
            <h3>Step 2: Database Configuration</h3>
            <p>The following database will be created:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Database: <code><?php echo DB_NAME; ?></code></li>
                <li>Host: <code><?php echo DB_HOST; ?></code></li>
                <li>User: <code><?php echo DB_USER; ?></code></li>
            </ul>
        </div>
        
        <div class="step">
            <h3>Step 3: Tables</h3>
            <p>The following tables will be created:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><code>contact_submissions</code> - Stores contact form submissions</li>
                <li><code>admin_users</code> - Stores admin login credentials</li>
            </ul>
        </div>
        
        <div class="info-box">
            <strong>Note:</strong> This installation is safe to run multiple times. Existing data will not be affected.
        </div>
        
        <form method="POST">
            <button type="submit" name="install" class="btn">Install Database</button>
        </form>
        
        <?php } ?>
    </div>
</body>
</html>
