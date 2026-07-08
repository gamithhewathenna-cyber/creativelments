# Creative Elements — PHP/MySQL Website
## CPanel Deployment Guide

---

### STEP 1 — Create Database (CPanel > MySQL Databases)
1. Create a new database, e.g. `youruser_ce`
2. Create a DB user with a strong password
3. Assign the user to the database with **All Privileges**
4. Go to **phpMyAdmin** → select your database → click **Import**
5. Upload `install.sql` and click Go

---

### STEP 2 — Edit Configuration
Open `includes/config.php` and update:

```php
define('DB_HOST', 'localhost');         // Almost always localhost on CPanel
define('DB_USER', 'youruser_ce');       // Your DB username
define('DB_PASS', 'YourPassword123');  // Your DB password
define('DB_NAME', 'youruser_ce');       // Your DB name
define('SITE_URL', 'https://yourdomain.com');
define('ADMIN_EMAIL', 'you@yourdomain.com');
```

---

### STEP 3 — Upload Files (CPanel > File Manager)
Upload ALL files to `public_html/` (or your subdomain folder).

Folder structure after upload:
```
public_html/
├── index.php
├── about.php
├── services.php
├── our-work.php
├── blog.php
├── blog-post.php
├── contact.php
├── contact-handler.php
├── .htaccess
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/style.css
│   └── js/main.js
├── uploads/
│   └── projects/      ← make this writable (chmod 755)
└── admin/
    ├── login.php
    ├── dashboard.php
    ├── enquiries.php
    ├── projects.php
    ├── services.php
    ├── testimonials.php
    ├── posts.php
    ├── settings.php
    └── logout.php
```

---

### STEP 4 — Set Folder Permissions
In CPanel File Manager, right-click `uploads/` and `uploads/projects/` → set permissions to **755**

---

### STEP 5 — Admin Login
URL: `https://yourdomain.com/admin/login.php`

Default credentials:
- **Username:** `admin`
- **Password:** `Admin@1234`

⚠️ **CHANGE YOUR PASSWORD IMMEDIATELY** after first login via Admin → Settings.

---

### STEP 6 — Enable HTTPS
In CPanel, activate **AutoSSL** (free Let's Encrypt SSL), then uncomment the HTTPS redirect lines in `.htaccess`.

---

### FEATURES INCLUDED
- ✅ Homepage with hero, services, portfolio, testimonials, CTA
- ✅ Services page
- ✅ Portfolio / Our Work page with category filter
- ✅ About page
- ✅ Blog with single post view
- ✅ Contact page with AJAX form + database storage
- ✅ WhatsApp floating button
- ✅ Fully responsive (mobile-first)
- ✅ Admin panel:
  - Dashboard with enquiry stats
  - View/reply to contact enquiries
  - Add/edit/delete portfolio projects (with image upload)
  - Add/edit/delete services
  - Add/edit/delete testimonials
  - Blog post editor (publish/draft)
  - Site settings (phone, email, social links, about text)
  - Change admin password
- ✅ Security: PDO prepared statements, XSS sanitization, file upload validation
- ✅ SEO-friendly .htaccess with caching & gzip
- ✅ CPanel-compatible PHP (no Composer required)
