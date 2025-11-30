# 🚀 SOFTNETIX Contact Form Backend Setup Guide

## Complete PHP Backend with Admin Dashboard

This guide will help you set up the contact form backend with PHP and MySQL on your WAMP server.

---

## 📋 Prerequisites

✅ WAMP Server installed and running  
✅ Apache and MySQL services started  
✅ PHP 7.4 or higher  
✅ Modern web browser  

---

## 🔧 Installation Steps

### Step 1: Start WAMP Server

1. Launch WAMP Server
2. Wait for the icon to turn **GREEN** (fully running)
3. If it's **ORANGE** or **RED**, click the icon and start all services

### Step 2: Copy Files to WAMP Directory

1. Copy your entire project folder to:
   ```
   C:\wamp64\www\softnetix\
   ```
   (Or wherever your WAMP www directory is located)

2. Your folder structure should look like:
   ```
   C:\wamp64\www\softnetix\
   ├── api/
   │   ├── config.php
   │   ├── submit-contact.php
   │   └── install.php
   ├── admin/
   │   ├── index.php
   │   ├── dashboard.php
   │   └── logout.php
   ├── css/
   ├── js/
   ├── index.html
   └── ...
   ```

### Step 3: Run Database Installation

1. Open your web browser
2. Navigate to:
   ```
   http://localhost/softnetix/api/install.php
   ```

3. Click the **"Install Database"** button

4. You should see success messages:
   - ✓ Database 'softnetix_db' created successfully!
   - ✓ Table 'contact_submissions' created successfully!
   - ✓ Table 'admin_users' created successfully!
   - ✓ Default admin user created!

5. **Important:** Note down the admin credentials shown:
   - Username: `admin`
   - Password: `Admin@123`

### Step 4: Access Your Website

1. Open your website:
   ```
   http://localhost/softnetix/
   ```

2. Test the contact form by filling it out and submitting

### Step 5: Access Admin Dashboard

1. Navigate to:
   ```
   http://localhost/softnetix/admin/
   ```

2. Login with credentials:
   - **Username:** `admin`
   - **Password:** `Admin@123`

3. You should see the admin dashboard with all submissions!

---

## 🔐 Security Configuration

### Change Default Credentials

**IMPORTANT:** Change the default admin credentials for security!

1. Open `api/config.php`
2. Find these lines:
   ```php
   define('ADMIN_USERNAME', 'admin');
   define('ADMIN_PASSWORD', 'Admin@123');
   ```

3. Change to your own secure credentials:
   ```php
   define('ADMIN_USERNAME', 'your_username');
   define('ADMIN_PASSWORD', 'YourStrongPassword123!');
   ```

4. Run the installation again to update:
   ```
   http://localhost/softnetix/api/install.php
   ```

### Change Database Password (Optional)

If you've set a MySQL root password:

1. Open `api/config.php`
2. Update:
   ```php
   define('DB_PASS', 'your_mysql_password');
   ```

---

## 📧 Email Notifications (Optional)

To receive email notifications when someone submits the form:

1. Open `api/config.php`

2. Enable SMTP:
   ```php
   define('SMTP_ENABLED', true);
   ```

3. Configure your email settings:
   ```php
   define('ADMIN_EMAIL', 'your-email@gmail.com');
   define('SMTP_HOST', 'smtp.gmail.com');
   define('SMTP_PORT', 587);
   define('SMTP_USERNAME', 'your-email@gmail.com');
   define('SMTP_PASSWORD', 'your-app-password');
   ```

4. For Gmail, you need to:
   - Enable 2-factor authentication
   - Generate an "App Password"
   - Use that app password in the config

---

## 🧪 Testing

### Test Contact Form

1. Go to: `http://localhost/softnetix/`
2. Scroll to "Get In Touch" section
3. Fill out the form:
   - Name: Test User
   - Email: test@example.com
   - Phone: 1234567890
   - Message: This is a test message
4. Click "Send Message"
5. You should see: "Thank you! Your message has been received..."

### Test Admin Dashboard

1. Go to: `http://localhost/softnetix/admin/`
2. Login with your credentials
3. You should see your test submission
4. Try these actions:
   - Mark as Read
   - Mark as Replied
   - Archive
   - Delete

---

## 📊 Admin Dashboard Features

### Statistics
- Total Submissions
- New Messages
- Read Messages
- Replied Messages

### Filters
- View All
- View New only
- View Read only
- View Replied only
- View Archived only

### Search
- Search by name
- Search by email
- Search by message content

### Actions
- **Mark as Read** - Mark message as read
- **Mark as Replied** - Mark that you've replied
- **Archive** - Move to archive
- **Delete** - Permanently delete

---

## 🗄️ Database Structure

### Table: contact_submissions

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Auto-increment ID |
| name | VARCHAR(100) | Sender's name |
| email | VARCHAR(255) | Sender's email |
| phone | VARCHAR(20) | Sender's phone (optional) |
| message | TEXT | Message content |
| ip_address | VARCHAR(45) | Sender's IP |
| user_agent | TEXT | Browser info |
| status | ENUM | new/read/replied/archived |
| submitted_at | DATETIME | Submission time |
| read_at | DATETIME | When marked as read |

### Table: admin_users

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Auto-increment ID |
| username | VARCHAR(50) | Admin username |
| password | VARCHAR(255) | Hashed password |
| email | VARCHAR(255) | Admin email |
| created_at | DATETIME | Account creation |
| last_login | DATETIME | Last login time |

---

## 🔍 Troubleshooting

### Problem: "Database connection failed"

**Solution:**
1. Make sure WAMP is running (green icon)
2. Check MySQL service is started
3. Verify credentials in `api/config.php`

### Problem: "404 Not Found"

**Solution:**
1. Check file path is correct
2. Make sure files are in `C:\wamp64\www\softnetix\`
3. Access via `http://localhost/softnetix/`

### Problem: "Access denied for user 'root'"

**Solution:**
1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Check if you can login
3. If you have a password, update `api/config.php`

### Problem: Form submission shows error

**Solution:**
1. Check browser console (F12) for errors
2. Verify `api/submit-contact.php` exists
3. Check file permissions
4. Look at PHP error logs in WAMP

### Problem: Can't login to admin

**Solution:**
1. Run installation again: `http://localhost/softnetix/api/install.php`
2. Use default credentials: admin / Admin@123
3. Check if `admin_users` table exists in database

---

## 📱 Production Deployment

When moving to a live server:

### 1. Update Configuration

Edit `api/config.php`:

```php
// Update database credentials
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');

// Update admin credentials
define('ADMIN_USERNAME', 'your_secure_username');
define('ADMIN_PASSWORD', 'YourVeryStrongPassword123!@#');

// Update email
define('ADMIN_EMAIL', 'your-real-email@domain.com');
```

### 2. Update CORS Settings

Edit `api/submit-contact.php`:

```php
// Change from:
header('Access-Control-Allow-Origin: *');

// To your domain:
header('Access-Control-Allow-Origin: https://yourdomain.com');
```

### 3. Disable Error Display

Edit `api/submit-contact.php`:

```php
// Change from:
ini_set('display_errors', 1);

// To:
ini_set('display_errors', 0);
```

### 4. Enable HTTPS

- Get SSL certificate (Let's Encrypt is free)
- Force HTTPS in .htaccess
- Update all URLs to use https://

### 5. Secure Admin Directory

Add `.htaccess` in `admin/` folder:

```apache
# Restrict access by IP (optional)
Order Deny,Allow
Deny from all
Allow from YOUR_IP_ADDRESS

# Or use HTTP authentication
AuthType Basic
AuthName "Admin Area"
AuthUserFile /path/to/.htpasswd
Require valid-user
```

---

## 🎯 Quick Reference

### URLs

- **Website:** `http://localhost/softnetix/`
- **Install:** `http://localhost/softnetix/api/install.php`
- **Admin:** `http://localhost/softnetix/admin/`
- **phpMyAdmin:** `http://localhost/phpmyadmin/`

### Default Credentials

- **Username:** `admin`
- **Password:** `Admin@123`
- **Database:** `softnetix_db`
- **MySQL User:** `root`
- **MySQL Pass:** (empty by default)

### Important Files

- **Config:** `api/config.php`
- **Form Handler:** `api/submit-contact.php`
- **Admin Login:** `admin/index.php`
- **Dashboard:** `admin/dashboard.php`

---

## ✅ Checklist

Before going live, make sure:

- [ ] Changed default admin credentials
- [ ] Updated database credentials
- [ ] Configured email notifications
- [ ] Tested form submission
- [ ] Tested admin dashboard
- [ ] Updated CORS settings
- [ ] Disabled error display
- [ ] Enabled HTTPS
- [ ] Secured admin directory
- [ ] Backed up database
- [ ] Tested on production server

---

## 📞 Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Review PHP error logs in WAMP
3. Check browser console for JavaScript errors
4. Verify all files are uploaded correctly
5. Ensure database tables are created

---

## 🎉 Success!

Your contact form backend is now fully functional with:

✅ Secure PHP backend  
✅ MySQL database storage  
✅ Professional admin dashboard  
✅ Email notifications (optional)  
✅ Search and filter capabilities  
✅ Status management  
✅ Production-ready code  

**Enjoy your new contact form system!** 🚀

