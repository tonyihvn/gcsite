# Upload Directory Fix - Deployment Guide

## ✅ What Was Fixed (Commit 083cc27)

The root cause of images uploading to the wrong directory has been identified and fixed across **5 files**:

### Root Cause
- **FileUploader.php**: Path detection was saving files to `/public_html/gcsite/public/assets/uploads/`
- **AdminController.php**: `handleFileUpload()` had hardcoded old paths
- Both were saving files to one location while database stored paths to another location
- Result: URL would show correct path but file wasn't actually there

### Files Fixed
1. **core/FileUploader.php** - Constructor path detection improved
2. **app/controllers/AdminController.php** - handleFileUpload() now uses correct path
3. **config/app.php** - Dynamic upload directory configuration
4. **app/helpers/functions.php** - Updated upload_path() helper
5. **create_upload_dirs.php** - Path detection for setup script

---

## 🚀 Production Deployment Steps

### Step 1: Upload Fixed Files to Production

Use FTP or cPanel File Manager to upload these files to `/public_html/gcsite/`:

```
core/FileUploader.php                   → /public_html/gcsite/core/FileUploader.php
app/controllers/AdminController.php     → /public_html/gcsite/app/controllers/AdminController.php
config/app.php                          → /public_html/gcsite/config/app.php
app/helpers/functions.php               → /public_html/gcsite/app/helpers/functions.php
```

### Step 2: Verify Directory Structure on Production

Ensure `/public_html/uploads/` exists with proper subdirectories:

```bash
/public_html/
├── uploads/                 ← CREATE IF MISSING
│   ├── slides/             ← CREATE IF MISSING
│   ├── services/           ← CREATE IF MISSING
│   ├── products/           ← CREATE IF MISSING
│   ├── blog/               ← CREATE IF MISSING
│   ├── pages/              ← CREATE IF MISSING
│   ├── about/              ← CREATE IF MISSING
│   ├── partners/           ← CREATE IF MISSING
│   ├── team/               ← CREATE IF MISSING
│   └── site/               ← CREATE IF MISSING
```

**Via cPanel File Manager:**
1. Navigate to `/public_html`
2. Create folder: `uploads` (if not exists)
3. Inside `uploads`, create all subdirectories listed above
4. Ensure each directory has permissions: `755`

**Or use terminal (if available):**
```bash
cd /public_html
mkdir -p uploads/{slides,services,products,blog,pages,about,partners,team,site}
chmod -R 755 uploads
```

### Step 3: Test Upload System

#### Via Browser (Recommended - No Terminal Needed)
1. Go to: `https://gintec.com.ng/diagnostics.php?token=YOUR_APP_KEY`
   - Replace `YOUR_APP_KEY` with value from `.env` file
   - Shows upload directory status
   - Test upload functionality

2. Go to: `https://gintec.com.ng/setup-uploads.php?token=YOUR_APP_KEY`
   - Creates/initializes all upload directories
   - Shows success/error messages

#### Via Admin Panel
1. Login to admin dashboard
2. Go to: **Slides** → Create New Slide
3. Upload an image
4. Verify it appears in URL (check browser DevTools Network tab)
5. Go to homepage and verify slider displays the image ✓

6. Go to: **Services** → Create New Service
7. Upload an image for service
8. Verify image displays on Services page ✓

9. Go to: **Products** → Create New Product
10. Upload an image for product
11. Verify image displays on Products page ✓

---

## 🔍 How to Verify Fix is Working

### Check 1: Verify Image URLs
- Inspect any image in browser DevTools (Right-click → Inspect)
- Image URL should start with: `https://gintec.com.ng/uploads/`
- Examples: 
  - ✓ `https://gintec.com.ng/uploads/slides/img_xxx.jpg`
  - ✓ `https://gintec.com.ng/uploads/services/img_xxx.jpg`
  - ✗ `https://gintec.com.ng/assets/uploads/slides/img_xxx.jpg` (old format)

### Check 2: Check Server Logs
- Look for FileUploader debug logs:
  - `[INFO] FileUploader: Shared hosting detected. Upload dir = /public_html/uploads/`
  - `[INFO] FileUploader: Successfully uploaded 'filename' to '/public_html/uploads/subdir/...'`
- Indicates fix is working correctly

### Check 3: Verify Files on Disk
Via cPanel File Manager:
1. Navigate to `/public_html/uploads/`
2. Check subdirectories contain uploaded files
3. Files should be directly in `/uploads/slides/`, not `/uploads/assets/uploads/slides/`

---

## 📋 Troubleshooting

### Problem: Still Showing "Old Format" URLs
**Cause:** Old FileUploader.php still running from production server
**Fix:** Ensure you've uploaded the NEW FileUploader.php file to production

### Problem: Images Not Uploading at All
**Cause:** `/public_html/uploads/` directory doesn't exist or not writable
**Fix:** 
1. Create directory via cPanel File Manager
2. Set permissions to `755`
3. Try upload again

### Problem: Images Upload But Don't Display
**Cause:** Path mismatch between file location and URL
**Fix:** This is exactly what this update fixed. Ensure all 5 files are updated to latest version.

### Problem: Getting Permission Denied Errors
**Cause:** Directory permissions too restrictive
**Fix:** Set directory permissions to `755` for uploads folder and all subdirectories

---

## ✅ Success Indicators

After deployment, you should see:

1. ✓ Images upload successfully via admin panel
2. ✓ Image URLs show format: `https://gintec.com.ng/uploads/subdir/filename.jpg`
3. ✓ Images display correctly on website pages
4. ✓ Homepage slider shows all slide images
5. ✓ Services page shows all service images
6. ✓ Products page shows all product images
7. ✓ FileUploader logs show "Shared hosting detected"

---

## 📞 Support

If issues persist after deployment:

1. **Clear Browser Cache** - Ctrl+Shift+Delete (Windows) or Cmd+Shift+Delete (Mac)
2. **Check File Permissions** - All upload directories should be `755`
3. **Verify .env Configuration** - Ensure `APP_URL=https://gintec.com.ng`
4. **Check Error Logs** - Look in cPanel Error logs or application error logs
5. **Verify File Upload** - Use diagnostics.php tool to test (link above)

---

## 📝 Notes

- Old database entries with `assets/uploads/` paths will still work (backward compatible)
- New uploads will use `uploads/` path format
- The fix automatically detects shared hosting vs local development environment
- Both Windows and Unix path separators are handled correctly

---

**Commit Hash:** 083cc27  
**Date:** 2026-07-02  
**Status:** Ready for Production Deployment
