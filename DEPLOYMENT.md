# Production Deployment Guide - GINTEC Solutions

## Overview
This guide explains how to deploy the latest clean codebase to production (gintec.com.ng).

## What's Changed
✅ Removed non-production files from git tracking
✅ Added setup-uploads.php for web-based directory initialization
✅ Updated .gitignore to prevent sensitive files from being tracked
✅ Verified FileUploader.php for production compatibility

## Deployment Steps

### 1. Pull Latest Changes
On your production server, pull the latest code:
```bash
cd /path/to/public_html
git pull origin main
```

### 2. Initialize Upload Directories
Visit the setup script in your browser:
```
https://gintec.com.ng/setup-uploads.php?token=YOUR_APP_KEY
```

Replace `YOUR_APP_KEY` with the value from your `.env` file.

**Example:**
```
https://gintec.com.ng/setup-uploads.php?token=gintec-super-secret-key-development-only-change-in-production
```

You should see a green status page confirming all directories were created.

### 3. Verify Permissions
Ensure correct permissions on upload directories:
```bash
chmod 755 public/assets/uploads
chmod 755 public/assets/uploads/*
```

### 4. Test Image Upload
1. Log into admin panel: https://gintec.com.ng/admin
2. Upload a test image to Slides, Products, or Blog
3. Verify the image displays on the frontend

## Files Removed from Production
The following files are now excluded from git and won't be deployed:
- `QUICK_REFERENCE.md` (development docs)
- `DEPLOYMENT_CHECKLIST.md` (dev checklist)
- `migrate.php` (local database migration)
- `migrate-run.php` (local migration runner)
- `create_upload_dirs.php` (CLI version, use web version instead)
- `router.php` (PHP dev server only)
- `public/create-admin.php` (development only)
- `public/run-*.php` (migration scripts)
- `.env` (never pushed, use .env.example as template)

## Production Configuration

### .env File
Your `.env` file should have:
```env
APP_URL=https://gintec.com.ng
APP_ENV=production
APP_DEBUG=false
```

These are already set in your latest .env file.

## Troubleshooting

### Setup script returns 404
1. Ensure `public/setup-uploads.php` is uploaded to server
2. Check that your APP_URL is correct in .env
3. Verify the file permissions: `ls -la public/setup-uploads.php`

### Images still not showing
1. Visit setup script again to verify directories exist
2. Check browser console for 404 errors on image URLs
3. Verify `/public/assets/uploads` has write permissions (755)
4. Check error logs: `tail -f error_log`

### Permission Denied errors
```bash
# Set correct permissions
chmod 755 public/assets/uploads
chmod 755 public/assets/uploads/*
chown -R www-data:www-data public/assets/uploads  # if needed
```

## What's Next
After deployment:
- [ ] Pull latest code to production
- [ ] Run setup-uploads.php via browser
- [ ] Test image upload/display
- [ ] Monitor error logs for issues
- [ ] Verify all images display correctly

## Security Reminders
- ✅ .env is never committed to git
- ✅ Non-production files excluded from deployment
- ✅ APP_DEBUG set to false in production
- ✅ Upload directories secured with .gitkeep

## Questions?
For deployment issues or questions, check:
- Error logs: `tail -f error_log`
- File permissions: `ls -la public/assets/uploads`
- Disk space: `df -h`
- Database connection: Check DATABASE section in .env
