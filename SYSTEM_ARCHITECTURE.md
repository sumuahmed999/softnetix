# 🏗️ System Architecture

## SOFTNETIX Contact Form Backend

---

## 📊 System Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     USER INTERACTION                         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND (index.html)                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Contact Form                                         │  │
│  │  - Name Input                                         │  │
│  │  - Email Input                                        │  │
│  │  - Phone Input (optional)                            │  │
│  │  - Message Textarea                                   │  │
│  │  - Submit Button                                      │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  JAVASCRIPT (js/form.js)                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  1. Validate Input                                    │  │
│  │  2. Show Loading State                                │  │
│  │  3. Send POST Request                                 │  │
│  │  4. Handle Response                                   │  │
│  │  5. Show Success/Error Message                        │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│              PHP BACKEND (api/submit-contact.php)            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  1. Receive JSON Data                                 │  │
│  │  2. Validate & Sanitize                               │  │
│  │  3. Check Required Fields                             │  │
│  │  4. Prepare SQL Statement                             │  │
│  │  5. Execute Database Insert                           │  │
│  │  6. Send Email (optional)                             │  │
│  │  7. Return JSON Response                              │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  DATABASE (MySQL)                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Table: contact_submissions                           │  │
│  │  ┌────────────────────────────────────────────────┐  │  │
│  │  │ id, name, email, phone, message                │  │  │
│  │  │ ip_address, user_agent, status                 │  │  │
│  │  │ submitted_at, read_at                          │  │  │
│  │  └────────────────────────────────────────────────┘  │  │
│  │                                                       │  │
│  │  Table: admin_users                                   │  │
│  │  ┌────────────────────────────────────────────────┐  │  │
│  │  │ id, username, password (hashed)                │  │  │
│  │  │ email, created_at, last_login                  │  │  │
│  │  └────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│              ADMIN DASHBOARD (admin/dashboard.php)           │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Features:                                            │  │
│  │  - View all submissions                               │  │
│  │  - Filter by status (new/read/replied/archived)      │  │
│  │  - Search by name/email/message                       │  │
│  │  - Mark as read/replied                               │  │
│  │  - Archive or delete submissions                      │  │
│  │  - View statistics                                    │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔄 Data Flow

### 1. Form Submission Flow

```
User fills form
    ↓
JavaScript validates input
    ↓
AJAX POST to api/submit-contact.php
    ↓
PHP validates & sanitizes data
    ↓
Data saved to MySQL database
    ↓
Email notification sent (optional)
    ↓
Success response returned
    ↓
Success message shown to user
```

### 2. Admin Dashboard Flow

```
Admin visits /admin/
    ↓
Login page shown
    ↓
Admin enters credentials
    ↓
PHP verifies against database
    ↓
Session created
    ↓
Dashboard loads submissions
    ↓
Admin can filter/search/manage
```

---

## 📁 File Structure

```
softnetix/
│
├── api/                          # Backend API
│   ├── config.php               # Database & app configuration
│   ├── submit-contact.php       # Form submission handler
│   └── install.php              # Database installation script
│
├── admin/                        # Admin Dashboard
│   ├── index.php                # Login page
│   ├── dashboard.php            # Main dashboard
│   └── logout.php               # Logout handler
│
├── css/                          # Stylesheets
│   ├── variables.css
│   ├── base.css
│   ├── components.css
│   ├── sections.css
│   └── animations.css
│
├── js/                           # JavaScript
│   ├── main.js
│   ├── form.js                  # Form handling (connects to PHP)
│   ├── navigation.js
│   ├── animations.js
│   └── scroll-animations.js
│
├── assets/                       # Images, fonts, etc.
│   └── images/
│
├── index.html                    # Main website
│
└── Documentation/
    ├── SETUP_INSTRUCTIONS.md    # Full setup guide
    ├── QUICK_START.md           # 5-minute quick start
    └── SYSTEM_ARCHITECTURE.md   # This file
```

---

## 🔐 Security Features

### 1. Input Validation
- Client-side validation (JavaScript)
- Server-side validation (PHP)
- SQL injection prevention (PDO prepared statements)
- XSS prevention (htmlspecialchars)

### 2. Authentication
- Password hashing (bcrypt)
- Session management
- Login required for admin area

### 3. Security Headers
- X-Content-Type-Options
- X-Frame-Options
- X-XSS-Protection
- CORS configuration

### 4. Database Security
- Prepared statements
- Parameterized queries
- No direct SQL execution
- Error logging (not displayed)

---

## 🗄️ Database Schema

### Table: contact_submissions

```sql
CREATE TABLE contact_submissions (
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
);
```

### Table: admin_users

```sql
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME NULL
);
```

---

## 🔌 API Endpoints

### POST /api/submit-contact.php

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "message": "Hello, I'm interested in your services."
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Thank you! Your message has been received...",
  "data": {
    "submission_id": 123
  }
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Email is required"
}
```

---

## 🎨 Admin Dashboard Features

### Statistics Dashboard
- Total submissions count
- New messages count
- Read messages count
- Replied messages count

### Filtering Options
- All submissions
- New only
- Read only
- Replied only
- Archived only

### Search Functionality
- Search by name
- Search by email
- Search by message content

### Status Management
- Mark as Read
- Mark as Replied
- Archive
- Delete

### Submission Details
- Sender name
- Email address
- Phone number (if provided)
- Message content
- Submission date/time
- IP address
- Browser info
- Current status

---

## 🚀 Performance Optimizations

### Frontend
- Form validation before submission
- Loading states for better UX
- Error handling
- Success feedback

### Backend
- PDO for efficient database queries
- Prepared statements (prevents SQL injection)
- Input sanitization
- Error logging

### Database
- Indexed columns for faster queries
- Optimized table structure
- UTF-8 encoding

---

## 📊 Status Workflow

```
NEW
 ↓
READ (Admin views the message)
 ↓
REPLIED (Admin responds to sender)
 ↓
ARCHIVED (Message archived for record keeping)
```

Or directly:
```
NEW → ARCHIVED (Skip to archive)
NEW → DELETED (Permanently remove)
```

---

## 🔄 Backup & Maintenance

### Regular Backups
```sql
-- Export database
mysqldump -u root -p softnetix_db > backup.sql

-- Import database
mysql -u root -p softnetix_db < backup.sql
```

### Clean Old Submissions
```sql
-- Delete archived submissions older than 1 year
DELETE FROM contact_submissions 
WHERE status = 'archived' 
AND submitted_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

---

## 🌐 Production Deployment Checklist

- [ ] Update database credentials
- [ ] Change admin password
- [ ] Enable HTTPS
- [ ] Update CORS settings
- [ ] Disable error display
- [ ] Configure email notifications
- [ ] Set up regular backups
- [ ] Secure admin directory
- [ ] Test all functionality
- [ ] Monitor error logs

---

## 📈 Future Enhancements

Possible additions:
- Export submissions to CSV/Excel
- Email templates for auto-replies
- Multiple admin users with roles
- Submission analytics
- Spam protection (CAPTCHA)
- File attachments
- Custom fields
- API for mobile apps
- Webhook integrations

---

**System is production-ready and fully functional!** 🎉

