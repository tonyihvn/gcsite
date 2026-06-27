# GINTEC Solutions - Quick Reference Guide

## What's Been Completed

### ✅ All TODOs Finished
- [x] **Pause Subscription API** - Users can pause active subscriptions
- [x] **Cancel Subscription API** - Users can cancel subscriptions  
- [x] **Deployment Checklist** - Complete 20-step production deployment guide
- [x] **Production .env Template** - Ready-to-use production configuration
- [x] **Email System** - Forgot password & feedback responses
- [x] **Password Management** - Users & admins can change/reset passwords
- [x] **Routing System** - Fixed for shared hosting with detailed debugging
- [x] **Menu System** - Dropdown menus and reordering working

---

## Feature Documentation

### 1. Pause Subscription
**What it does:** Users can temporarily pause their subscription without cancelling
**Location:** Dashboard → My Subscriptions
**Button:** "Pause" (yellow button, visible only on active subscriptions)
**What happens:** 
- Subscription status changes to "paused"
- No charges until resumed
- Can be resumed from same dashboard
**Technical:** `POST /dashboard/subscriptions/{id}/pause`

### 2. Cancel Subscription
**What it does:** Users can permanently cancel their subscription
**Location:** Dashboard → My Subscriptions
**Button:** "Cancel" (red button, visible only on active subscriptions)
**What happens:**
- Subscription status changes to "cancelled"
- End date is set to today
- User loses access to product
**Technical:** `POST /dashboard/subscriptions/{id}/cancel`

### 3. Email Features
**Forgot Password Flow:**
1. User goes to login → Forgot Password
2. Enters email address
3. System sends reset link (valid 24 hours)
4. User clicks link and sets new password
5. Email is sent via MAIL_HOST configured in .env

**Feedback Response Flow:**
1. Admin views feedback in Admin Panel
2. Clicks "Respond" button
3. Enters response message
4. System sends email to feedback sender with:
   - Original feedback message
   - Admin's response
   - Company contact info

---

## Security Features

### Password Management
- Passwords hashed with bcrypt
- CSRF tokens on all forms
- Password validation:
  - Minimum 8 characters
  - Cannot reuse same password
  - User must verify current password to change
  - Admin can force reset (user must use new password)

### Database Security
- Prepared statements prevent SQL injection
- PDO with bound parameters
- User data isolated by user_id in queries

### Session Security
- Session timeout: 3600 seconds (1 hour)
- Remember me duration: 2592000 seconds (30 days)
- Session data encrypted
- CSRF tokens validated on POST requests

---

## Database Schema

### Subscriptions Table
```sql
CREATE TABLE gintec_subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    plan_name VARCHAR(255),
    plan_price DECIMAL(10,2),
    billing_cycle VARCHAR(50),
    status ENUM('active', 'paused', 'cancelled') DEFAULT 'active',
    start_date DATETIME,
    end_date DATETIME NULL,
    renewal_date DATETIME NULL,
    auto_renew BOOLEAN DEFAULT 1,
    payment_method VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Status Meanings
- `active` - Currently active, charges apply
- `paused` - Temporarily paused, no charges
- `cancelled` - Permanently cancelled

---

## Common Tasks

### Test Pause/Cancel Locally
1. Open http://localhost:8001/dashboard
2. Go to "My Subscriptions"
3. Click "Pause" or "Cancel" button
4. Confirm in popup
5. Page reloads and status updates

### Check Email Sending
1. Enable `APP_DEBUG=true` in .env
2. Test forgot password feature
3. Check error log: `tail -f error.log | grep Mailer`
4. Look for "Mailer:" entries showing email attempts

### Debug Routing Issues
1. Set `APP_DEBUG=true`
2. Access problematic URL
3. Check error log for "=== ROUTER DEBUG START ===" section
4. Shows REQUEST_URI, SCRIPT_NAME, extracted path, and matched routes

### View Database
```bash
# SSH into hosting
mysql -u username -p database_name
SELECT * FROM gintec_subscriptions;
SELECT * FROM gintec_users;
SELECT * FROM gintec_feedbacks;
```

---

## Deployment Checklist Highlights

### Before Going Live
1. Set `APP_DEBUG=false` in production .env
2. Generate new `APP_KEY` and `ENCRYPTION_KEY`
3. Update database credentials
4. Verify email credentials with hosting
5. Enable Apache mod_rewrite
6. Run database migrations
7. Test all user features

### After Going Live
1. Monitor error logs daily
2. Check email delivery
3. Verify backups are running
4. Monitor disk space usage
5. Watch for security issues

---

## Troubleshooting

### 404 Errors on Routes
**Issue:** Routes like `/dashboard/change-password` return 404
**Solution:**
1. Check .htaccess exists in public/
2. Verify Apache mod_rewrite is enabled
3. Check REQUEST_URI vs extracted path in error logs (with APP_DEBUG=true)
4. May need RewriteBase adjustment if in subdirectory

### Emails Not Sending
**Issue:** Forgot password or feedback emails not received
**Solutions:**
1. Verify MAIL_* settings in .env match hosting provider
2. Check /home/gintecadmin/error_log for Mailer errors
3. Test SMTP connection: `telnet mail.gintec.com.ng 465`
4. Check spam folder
5. Contact hosting provider for email configuration help

### Password Change Not Working
**Issue:** User gets error when changing password
**Possible causes:**
- Current password entered incorrectly
- New passwords don't match
- CSRF token expired (refresh page and try again)
- Database not updated with migration (run migrations)

### Subscriptions Not Showing
**Issue:** User dashboard shows no subscriptions
**Possible causes:**
- No subscriptions created yet
- Database connection issue
- User_id mismatch in database
- Check error logs for SQL errors

---

## File Structure

```
gcsite/
├── .env                              # Production credentials (DO NOT COMMIT)
├── .env.example                      # Development reference
├── .env.production.example           # Production template
├── DEPLOYMENT_CHECKLIST.md          # 20-step deployment guide
├── QUICK_REFERENCE.md               # This file
├── app/
│   ├── controllers/
│   │   ├── UserController.php       # Contains pauseSubscription, cancelSubscription
│   │   └── AdminController.php
│   ├── models/
│   │   ├── Subscription.php         # Contains pause/cancel methods
│   │   └── User.php
│   └── views/
│       └── user/subscriptions.php   # Pause/cancel UI
├── public/
│   ├── .htaccess                    # URL rewriting (must exist)
│   ├── index.php                    # Application entry point
│   └── assets/uploads/              # User uploads
└── core/
    ├── Router.php                   # Routing with debug logging
    └── Mailer.php                   # Email sending
```

---

## API Endpoints

### User Features
```
POST /dashboard/change-password        # Change user password
POST /dashboard/subscriptions/{id}/pause    # Pause subscription
POST /dashboard/subscriptions/{id}/cancel   # Cancel subscription
GET  /dashboard/subscriptions          # View all subscriptions
```

### Admin Features
```
POST /admin/users/{id}/change-password      # Admin reset user password
GET  /admin/feedbacks/{id}                  # View feedback detail
POST /admin/feedbacks/{id}                  # Send feedback response email
```

### Authentication
```
POST /auth/forgot-password             # Request password reset
GET  /auth/reset-password/{token}      # Reset password form
POST /auth/reset-password              # Process password reset
```

---

## Contact & Support

**For deployment help:**
1. Follow DEPLOYMENT_CHECKLIST.md step by step
2. Check error logs: `/home/gintecadmin/error_log`
3. Enable APP_DEBUG=true temporarily for detailed error messages
4. Contact hosting provider for:
   - Apache mod_rewrite setup
   - Email/SMTP configuration
   - Database access issues
   - SSL certificate installation

**Common hosting provider info needed:**
- Database host, username, password
- SMTP host, port, username, password
- Whether mod_rewrite is enabled
- PHP version (must be 8.1+)
- Available PHP extensions (PDO MySQL, cURL)

---

## Development vs Production

| Setting | Development | Production |
|---------|------------|-----------|
| APP_DEBUG | true | **false** |
| APP_ENV | development | production |
| APP_URL | http://localhost:8001 | https://gintec.com.ng |
| Database | localhost (local) | Hosting provider |
| Email | test or local | Your email server |
| Error logging | On screen | File only |
| Cache | None | Recommended |
| Backups | Optional | Daily |

---

**Last Updated:** 2026-06-27  
**Version:** 1.0
