# GINTEC Solutions - Production Deployment Checklist

## Pre-Deployment Setup

### 1. Create Production .env File
```bash
# Copy development .env and modify for production
cp .env .env.production
```

**Update these values in production .env:**
```
APP_NAME=GINTEC Solutions Consults Ltd
APP_URL=https://gintec.com.ng
APP_ENV=production
APP_DEBUG=false  # CRITICAL: Must be false in production

# Database - Update with hosting provider credentials
DB_HOST=provided_by_hosting
DB_PORT=3306
DB_NAME=gintec_solutions
DB_USER=provided_by_hosting
DB_PASS=provided_by_hosting

# Email - Verify with hosting provider
MAIL_MAILER=smtp
MAIL_HOST=mail.gintec.com.ng
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USER=info@gintec.com.ng
MAIL_PASS=@@AdminGintec22  # Use strong password
MAIL_FROM=info@gintec.com.ng
MAIL_FROM_NAME=GINTEC Solutions Consults Ltd

# Security - Generate new random keys
APP_KEY=generate_with_32_random_chars
ENCRYPTION_KEY=generate_with_32_random_chars
```

### 2. Directory Structure on Shared Hosting
```
/home/gintecadmin/
├── public_html/          # Empty or minimal files
└── gcsite/               # Main application
    ├── .env              # Production environment file
    ├── .env.example      # Reference only
    ├── .gitignore
    ├── README.md
    ├── app/              # Application code
    ├── config/           # Configuration files
    ├── core/             # Core framework
    ├── database/         # Migrations & seeds
    ├── public/           # Web root
    │   ├── .htaccess     # URL rewriting
    │   ├── index.php     # Entry point
    │   └── assets/       # CSS, JS, uploads
    └── DEPLOYMENT_CHECKLIST.md
```

---

## Deployment Steps

### 3. Upload Application Files
```bash
# SCP or FTP entire gcsite/ folder to ~/gcsite/
# Ensure .env is NOT tracked in git for production

# Critical files that must be present:
- public/.htaccess       # URL rewriting rules
- public/index.php       # Application bootstrap
- core/DotEnv.php        # Environment loader
- app/controllers/       # All controllers
- app/models/           # All models
- app/views/            # All views
```

### 4. Verify File Permissions
```bash
# SSH into hosting
chmod 755 gcsite/
chmod 755 gcsite/public/
chmod 755 gcsite/app/
chmod 755 gcsite/config/
chmod 755 gcsite/core/

# Make uploads directory writable
chmod 755 gcsite/public/assets/
chmod 755 gcsite/public/assets/uploads/
chmod 755 gcsite/public/assets/uploads/products/
chmod 755 gcsite/public/assets/uploads/services/
chmod 755 gcsite/public/assets/uploads/about/
chmod 755 gcsite/public/assets/uploads/team/
chmod 755 gcsite/public/assets/uploads/slides/
```

### 5. Create .env File on Hosting
```bash
# SSH into hosting
cd ~/gcsite/
nano .env

# Paste production configuration (from step 1)
# Ctrl+O to save, Ctrl+X to exit
```

### 6. Verify Apache Modules
**Contact hosting provider to verify:**
- [ ] Apache mod_rewrite is enabled
- [ ] PHP 8.1+ is installed
- [ ] PDO MySQL extension enabled
- [ ] mail() function is available
- [ ] cURL extension enabled

**Test in control panel or SSH:**
```bash
php -m | grep -i pdo
php -m | grep -i curl
```

### 7. Run Database Migrations
```bash
# SSH into hosting
cd ~/gcsite/
php public/run-migration.php

# Expected output: Migration completed successfully
```

---

## Production Testing Checklist

### 8. Test Website Access
- [ ] **Homepage:** https://gintec.com.ng/ loads correctly
- [ ] **About page:** https://gintec.com.ng/about displays correctly
- [ ] **Products page:** https://gintec.com.ng/products shows all products
- [ ] **Services page:** https://gintec.com.ng/services shows all services
- [ ] **Blog page:** https://gintec.com.ng/blog displays blog posts
- [ ] **Contact page:** https://gintec.com.ng/contact form appears

### 9. Test Authentication
- [ ] **Register:** Can create new user account
- [ ] **Login:** Can login with credentials
- [ ] **Logout:** Session clears properly
- [ ] **Dashboard:** https://gintec.com.ng/dashboard loads

### 10. Test User Features
- [ ] **Profile:** Can view and edit profile
- [ ] **Change Password:** https://gintec.com.ng/dashboard/change-password works
- [ ] **Subscriptions:** https://gintec.com.ng/dashboard/subscriptions loads
- [ ] **Pause Subscription:** Click pause button and verify status changes
- [ ] **Cancel Subscription:** Click cancel button and verify status changes
- [ ] **Invoices:** Can view invoices list

### 11. Test Email Features
- [ ] **Forgot Password:** Email received within 2 minutes
- [ ] **Reset Password:** Can reset password via email link
- [ ] **Feedback Response:** Admin can send feedback response email
- [ ] **Check email logs:** Verify no errors in hosting error log

**Debug email issues:**
```bash
# Check hosting error log
tail -f /home/gintecadmin/error_log | grep Mailer

# Test PHP mail function
php -r "mail('test@gmail.com', 'Test', 'Body'); echo 'Sent';"
```

### 12. Test Admin Features
- [ ] **Admin Login:** https://gintec.com.ng/admin/dashboard
- [ ] **Create Product:** Can add new product with uploads
- [ ] **Create Service:** Can add new service with uploads
- [ ] **Create Blog:** Can add blog post
- [ ] **View Feedbacks:** https://gintec.com.ng/admin/feedbacks
- [ ] **Respond to Feedback:** Email sent successfully
- [ ] **Manage Users:** Can view and modify users
- [ ] **Change User Password:** Can reset user password

### 13. Test File Uploads
- [ ] **Product brochure upload:** Works correctly
- [ ] **Service brochure upload:** Works correctly
- [ ] **Team member photo:** Uploads and displays
- [ ] **Slide image:** Uploads to correct directory

**Verify uploads are in correct locations:**
```bash
ls -la ~/gcsite/public/assets/uploads/
```

### 14. Test Menu System
- [ ] **Homepage menu:** All items display correctly
- [ ] **Dropdown menus:** Work properly
- [ ] **Menu reordering:** Admin can reorder menu items
- [ ] **Dynamic pages:** Custom pages appear in menu

---

## Security Checks

### 15. Security Verification
- [ ] [ ] APP_DEBUG is `false` in production .env
- [ ] [ ] APP_KEY is unique (not default)
- [ ] [ ] ENCRYPTION_KEY is unique (32 chars)
- [ ] [ ] Database credentials are secure
- [ ] [ ] .env file is NOT publicly accessible
- [ ] [ ] .htaccess enables mod_rewrite
- [ ] [ ] Static files (css, js, images) serve correctly
- [ ] [ ] CSRF tokens work on all forms
- [ ] [ ] Authentication middleware protects dashboard

**Test .env is not accessible:**
```bash
curl https://gintec.com.ng/.env
# Should return 404 or 403, not file contents
```

### 16. Performance Check
- [ ] **Homepage loads in < 3 seconds**
- [ ] **Images load quickly** (check image optimization)
- [ ] **Database queries are efficient** (check query logs)
- [ ] **No PHP errors** in hosting error log
- [ ] **Session handling works** (login stays persistent)

---

## Post-Deployment

### 17. Monitoring & Logs
**Monitor these logs regularly:**
```bash
# PHP errors
tail -f /home/gintecadmin/error_log

# Check for email errors
grep "Mailer" /home/gintecadmin/error_log

# Check for routing issues
grep "Router DEBUG" /home/gintecadmin/error_log

# Set up log rotation if needed
logrotate /etc/logrotate.conf
```

### 18. Backup Strategy
**Regular backups of:**
- [ ] Database: `~/gcsite/../database_backups/`
- [ ] Uploads: `~/gcsite/public/assets/uploads/`
- [ ] Configuration: `~/gcsite/.env` (secure location)

### 19. Email Configuration Troubleshooting
If emails aren't sending:

1. **Check mail credentials:**
   ```bash
   # Verify mail server connection
   telnet mail.gintec.com.ng 465
   ```

2. **Check PHP mail logs:**
   ```bash
   grep "mail()" /home/gintecadmin/error_log
   ```

3. **Verify MX records:**
   ```bash
   nslookup -type=MX gintec.com.ng
   ```

4. **Check spam folder:**
   - Emails might be going to spam
   - Ask test recipient to check spam
   - May need SPF/DKIM records

5. **Contact hosting provider:**
   - Ask for SMTP relay settings
   - Verify mail server is running
   - Check if port 465 or 587 is preferred

### 20. SSL Certificate
- [ ] **SSL enabled:** https:// works
- [ ] **Certificate valid:** No browser warnings
- [ ] **Auto-renewal:** Certificate auto-renews before expiry
- [ ] **Redirect http to https:** All traffic encrypted

---

## Rollback Plan

If issues occur in production:

1. **Disable debug mode to prevent info leaks:**
   ```bash
   # Edit .env
   APP_DEBUG=false
   ```

2. **Revert to previous version:**
   ```bash
   # If using git
   git checkout previous_commit
   # Or restore from backup
   ```

3. **Check error logs:**
   ```bash
   tail -100 /home/gintecadmin/error_log
   ```

4. **Contact hosting support:**
   - Provide error messages
   - Ask for Apache/PHP logs
   - Request help with mod_rewrite setup

---

## Final Sign-Off

### Production Deployment Complete When:
- [x] All tests from sections 8-16 pass
- [x] No errors in hosting error log
- [x] Website accessible at https://gintec.com.ng
- [x] Admin can manage content
- [x] Users can create accounts and subscribe
- [x] Emails are being sent successfully
- [x] SSL certificate is valid
- [x] Backups are in place

---

## Quick Reference

### Common Commands
```bash
# Check Apache mod_rewrite
apache2ctl -M | grep rewrite

# Check PHP version
php -v

# Check installed PHP extensions
php -m

# Check error log in real-time
tail -f /home/gintecadmin/error_log

# Check disk space
df -h

# Restart Apache (if you have permissions)
systemctl restart apache2
```

### Important URLs
- **Homepage:** https://gintec.com.ng/
- **Admin Panel:** https://gintec.com.ng/admin/dashboard
- **User Dashboard:** https://gintec.com.ng/dashboard
- **Error Logs:** SSH to `/home/gintecadmin/error_log`
- **Uploads:** SSH to `/home/gintecadmin/gcsite/public/assets/uploads/`

### Support Contacts
- **Hosting Provider:** [Add contact info]
- **Domain Registrar:** [Add contact info]
- **Email Support:** [Add contact info]
- **Developer:** [Add contact info]

---

**Last Updated:** 2026-06-27  
**Version:** 1.0  
**Status:** Ready for Production Deployment
