# 📸 Visual Setup Guide

## Step-by-Step with Screenshots Guide

---

## 🎯 What You'll Have

After following this guide, you'll have:

```
┌─────────────────────────────────────┐
│   SOFTNETIX Website                 │
│   with Working Contact Form         │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│   PHP Backend API                   │
│   Saves to MySQL Database           │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│   Admin Dashboard                   │
│   View & Manage Submissions         │
└─────────────────────────────────────┘
```

---

## 📋 Step 1: Start WAMP Server

### What to do:
1. Find WAMP icon on your desktop or start menu
2. Double-click to launch
3. Look at system tray (bottom-right of screen)
4. Wait for WAMP icon to turn **GREEN**

### What you'll see:
```
🔴 RED    = Services stopped
🟠 ORANGE = Some services running
🟢 GREEN  = All services running ✅
```

### If icon stays RED or ORANGE:
1. Right-click WAMP icon
2. Click "Restart All Services"
3. Wait for GREEN

---

## 📋 Step 2: Copy Project Files

### What to do:
1. Open File Explorer
2. Navigate to: `C:\wamp64\www\`
3. Create folder: `softnetix`
4. Copy all your project files into this folder

### Your folder should look like:
```
C:\wamp64\www\softnetix\
├── api\
│   ├── config.php
│   ├── submit-contact.php
│   └── install.php
├── admin\
│   ├── index.php
│   ├── dashboard.php
│   └── logout.php
├── css\
├── js\
├── assets\
├── index.html
└── ...
```

---

## 📋 Step 3: Install Database

### What to do:
1. Open your web browser (Chrome, Firefox, etc.)
2. Type in address bar: `http://localhost/softnetix/api/install.php`
3. Press Enter

### What you'll see:
```
┌─────────────────────────────────────────┐
│  🚀 SOFTNETIX Database Installation     │
│                                         │
│  Step 1: Prerequisites                  │
│  Step 2: Database Configuration         │
│  Step 3: Tables                         │
│                                         │
│  [Install Database] ← Click this button │
└─────────────────────────────────────────┘
```

### After clicking "Install Database":
```
┌─────────────────────────────────────────┐
│  ✓ Database 'softnetix_db' created!    │
│  ✓ Table 'contact_submissions' created! │
│  ✓ Table 'admin_users' created!        │
│  ✓ Default admin user created!         │
│                                         │
│  🎉 Installation completed!             │
│                                         │
│  Admin Credentials:                     │
│  Username: admin                        │
│  Password: Admin@123                    │
│                                         │
│  [Go to Admin Dashboard]                │
└─────────────────────────────────────────┘
```

✅ **Success!** Database is now installed!

---

## 📋 Step 4: View Your Website

### What to do:
1. Open new browser tab
2. Type: `http://localhost/softnetix/`
3. Press Enter

### What you'll see:
```
┌─────────────────────────────────────────┐
│  SOFTNETIX                              │
│  Committed to a better future           │
│                                         │
│  [Your beautiful website loads here]    │
│                                         │
│  ... scroll down ...                    │
│                                         │
│  Get In Touch                           │
│  ┌─────────────────────────────────┐   │
│  │ Name: [____________]            │   │
│  │ Email: [____________]           │   │
│  │ Phone: [____________]           │   │
│  │ Message: [___________]          │   │
│  │          [___________]          │   │
│  │                                 │   │
│  │ [Send Message]                  │   │
│  └─────────────────────────────────┘   │
└─────────────────────────────────────────┘
```

---

## 📋 Step 5: Test Contact Form

### What to do:
1. Scroll to "Get In Touch" section
2. Fill out the form:
   ```
   Name:    Test User
   Email:   test@example.com
   Phone:   1234567890
   Message: This is a test message from my contact form!
   ```
3. Click "Send Message"

### What you'll see:
```
[Sending...]  ← Loading state

Then:

✓ Thank you! Your message has been received.
  We will get back to you soon.
```

✅ **Success!** Form is working!

---

## 📋 Step 6: Access Admin Dashboard

### What to do:
1. Open new browser tab
2. Type: `http://localhost/softnetix/admin/`
3. Press Enter

### What you'll see:
```
┌─────────────────────────────────────────┐
│  🔐 SOFTNETIX                           │
│     Admin Dashboard                     │
│                                         │
│  Username: [____________]               │
│  Password: [____________]               │
│                                         │
│  [Login]                                │
│                                         │
│  © 2024 SOFTNETIX                       │
└─────────────────────────────────────────┘
```

### Login with:
```
Username: admin
Password: Admin@123
```

---

## 📋 Step 7: View Submissions

### After login, you'll see:
```
┌─────────────────────────────────────────────────────────┐
│  📊 SOFTNETIX Admin Dashboard    👤 admin    [Logout]   │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ │
│  │  Total   │ │   New    │ │   Read   │ │ Replied  │ │
│  │    1     │ │    1     │ │    0     │ │    0     │ │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘ │
│                                                         │
│  [All] [New] [Read] [Replied] [Archived] [Search...]   │
│                                                         │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Test User                            [NEW]      │   │
│  │ 📧 test@example.com | 📱 1234567890             │   │
│  │ 🕒 Nov 27, 2024 14:30                           │   │
│  │                                                 │   │
│  │ This is a test message from my contact form!   │   │
│  │                                                 │   │
│  │ [Mark as Read] [Mark as Replied] [Archive]     │   │
│  │ [Delete]                                        │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

✅ **Success!** You can see your test submission!

---

## 🎮 Try These Actions

### 1. Mark as Read
Click "Mark as Read" button
→ Status changes to [READ]
→ Statistics update

### 2. Mark as Replied
Click "Mark as Replied" button
→ Status changes to [REPLIED]
→ Statistics update

### 3. Filter Submissions
Click filter buttons at top:
- [All] - Show everything
- [New] - Show only new
- [Read] - Show only read
- [Replied] - Show only replied
- [Archived] - Show archived

### 4. Search
Type in search box:
- Search by name
- Search by email
- Search by message content

### 5. Archive
Click "Archive" button
→ Submission moved to archived
→ Click [Archived] filter to see it

### 6. Delete
Click "Delete" button
→ Confirmation popup appears
→ Click OK to permanently delete

---

## 🎉 Congratulations!

You now have a fully functional contact form system!

### What's Working:
✅ Contact form on website  
✅ PHP backend saving to database  
✅ Admin dashboard to manage submissions  
✅ Filter and search features  
✅ Status management  
✅ Statistics dashboard  

---

## 🔄 Daily Usage

### When someone submits the form:
1. They fill out the contact form
2. Data is saved to your database
3. You login to admin dashboard
4. You see the new submission (marked as NEW)
5. You read it and mark as READ
6. You reply to them via email
7. You mark as REPLIED in dashboard
8. Later, you can ARCHIVE old submissions

---

## 🆘 Troubleshooting Visual Guide

### Problem: Can't access localhost

**Check 1:** Is WAMP running?
```
Look at system tray → Find WAMP icon
🟢 GREEN = Good!
🔴 RED = Not running → Start WAMP
```

**Check 2:** Try alternative URL
```
Instead of: http://localhost/softnetix/
Try: http://127.0.0.1/softnetix/
```

### Problem: Database error

**Solution:** Run install again
```
Go to: http://localhost/softnetix/api/install.php
Click: "Install Database"
```

### Problem: Can't login to admin

**Check credentials:**
```
Username: admin  (lowercase, no spaces)
Password: Admin@123  (exact case, with @ and numbers)
```

**If still not working:**
```
Run install again to reset admin user
```

### Problem: Form shows error

**Check browser console:**
```
1. Press F12 on keyboard
2. Click "Console" tab
3. Look for red error messages
4. Check if API path is correct
```

---

## 📱 Mobile Testing

### Test on your phone:
1. Find your computer's IP address:
   ```
   Open Command Prompt
   Type: ipconfig
   Look for: IPv4 Address (e.g., 192.168.1.100)
   ```

2. On your phone's browser:
   ```
   Go to: http://YOUR_IP/softnetix/
   Example: http://192.168.1.100/softnetix/
   ```

3. Test the contact form on mobile!

---

## 🎯 Next Steps

### 1. Change Password
```
Edit: api/config.php
Change: ADMIN_PASSWORD
Run: install.php again
```

### 2. Customize
- Update colors in CSS
- Change text content
- Add your logo
- Modify email templates

### 3. Go Live
- Get web hosting
- Upload files
- Update config.php
- Follow production guide

---

**You're all set! Enjoy your new contact form system! 🚀**

