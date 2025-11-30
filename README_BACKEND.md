# 📧 SOFTNETIX Contact Form Backend

## Complete PHP Backend System with Admin Dashboard

---

## ✨ Features

### Frontend
- ✅ Beautiful contact form with validation
- ✅ Real-time error messages
- ✅ Loading states
- ✅ Success/error feedback
- ✅ Mobile responsive

### Backend
- ✅ Secure PHP API
- ✅ MySQL database storage
- ✅ Input validation & sanitization
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Email notifications (optional)

### Admin Dashboard
- ✅ Secure login system
- ✅ View all submissions
- ✅ Filter by status (new/read/replied/archived)
- ✅ Search functionality
- ✅ Status management
- ✅ Statistics dashboard
- ✅ Delete submissions
- ✅ Responsive design

---

## 🚀 Quick Start

### 1. Start WAMP Server
```
Launch WAMP → Wait for GREEN icon
```

### 2. Copy Files
```
Copy project to: C:\wamp64\www\softnetix\
```

### 3. Install Database
```
Visit: http://localhost/softnetix/api/install.php
Click: "Install Database"
```

### 4. Access Website
```
Website: http://localhost/softnetix/
Admin: http://localhost/softnetix/admin/
```

### 5. Login to Admin
```
Username: admin
Password: Admin@123
```

**Done! 🎉**

---

## 📁 What's Included

### Backend Files
```
api/
├── config.php              # Configuration
├── submit-contact.php      # Form handler
└── install.php            # Database installer
```

### Admin Files
```
admin/
├── index.php              # Login page
├── dashboard.php          # Main dashboard
└── logout.php            # Logout handler
```

### Frontend Files
```
js/form.js                 # Form handling (updated)
index.html                 # Contact form
```

### Documentation
```
SETUP_INSTRUCTIONS.md      # Full setup guide
QUICK_START.md            # 5-minute guide
SYSTEM_ARCHITECTURE.md    # Technical details
README_BACKEND.md         # This file
```

---

## 🔐 Default Credentials

**Admin Dashboard:**
- Username: `admin`
- Password: `Admin@123`

**Database:**
- Host: `localhost`
- User: `root`
- Password: (empty)
- Database: `softnetix_db`

⚠️ **IMPORTANT:** Change these in production!

---

## 📊 Database Tables

### contact_submissions
Stores all form submissions with:
- Name, email, phone, message
- IP address, browser info
- Status (new/read/replied/archived)
- Timestamps

### admin_users
Stores admin login credentials:
- Username, hashed password
- Email, last login time

---

## 🎯 How It Works

1. **User submits form** → JavaScript validates
2. **AJAX POST** → Sends to `api/submit-contact.php`
3. **PHP validates** → Sanitizes and checks data
4. **Saves to MySQL** → Stores in database
5. **Sends email** → Notifies admin (optional)
6. **Returns response** → Shows success message

---

## 🛠️ Configuration

### Change Admin Credentials

Edit `api/config.php`:
```php
define('ADMIN_USERNAME', 'your_username');
define('ADMIN_PASSWORD', 'YourStrongPassword123!');
```

### Enable Email Notifications

Edit `api/config.php`:
```php
define('SMTP_ENABLED', true);
define('ADMIN_EMAIL', 'your-email@gmail.com');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
```

---

## 📱 Admin Dashboard

### Features
- **Statistics** - View total, new, read, replied counts
- **Filters** - Filter by status
- **Search** - Search by name, email, or message
- **Actions** - Mark as read/replied, archive, delete

### Status Workflow
```
NEW → READ → REPLIED → ARCHIVED
```

---

## 🔒 Security Features

✅ Password hashing (bcrypt)  
✅ SQL injection prevention (PDO)  
✅ XSS protection (htmlspecialchars)  
✅ CSRF protection (session-based)  
✅ Input validation & sanitization  
✅ Security headers  
✅ Error logging (not displayed)  

---

## 🧪 Testing

### Test Form Submission
1. Go to contact form
2. Fill out all fields
3. Submit
4. Check for success message

### Test Admin Dashboard
1. Login to admin panel
2. View submission
3. Try all actions (read, replied, archive, delete)
4. Test filters and search

---

## 🌐 Production Deployment

### Before Going Live:

1. **Update config.php**
   - Change database credentials
   - Change admin password
   - Update admin email

2. **Update submit-contact.php**
   - Change CORS origin from `*` to your domain
   - Disable error display

3. **Security**
   - Enable HTTPS
   - Secure admin directory
   - Set strong passwords

4. **Testing**
   - Test form submission
   - Test admin login
   - Test all features

---

## 📞 Support & Troubleshooting

### Common Issues

**WAMP not starting?**
- Check if ports 80/3306 are free
- Restart all services

**Database connection error?**
- Verify WAMP is running
- Check credentials in config.php

**Form not submitting?**
- Check browser console (F12)
- Verify API path is correct

**Can't login to admin?**
- Run install.php again
- Use default credentials

---

## 📚 Documentation

- **Full Setup:** See `SETUP_INSTRUCTIONS.md`
- **Quick Start:** See `QUICK_START.md`
- **Architecture:** See `SYSTEM_ARCHITECTURE.md`

---

## ✅ Checklist

Before deployment:

- [ ] WAMP server running
- [ ] Database installed
- [ ] Form tested
- [ ] Admin dashboard tested
- [ ] Credentials changed
- [ ] Email configured (optional)
- [ ] Security settings updated
- [ ] HTTPS enabled (production)
- [ ] Backup created

---

## 🎉 You're All Set!

Your contact form backend is now:

✅ **Functional** - Saving submissions to database  
✅ **Secure** - Protected against common attacks  
✅ **Professional** - Beautiful admin dashboard  
✅ **Production-Ready** - Ready to deploy  

**Need help?** Check the documentation files!

---

**Made with ❤️ for SOFTNETIX**

