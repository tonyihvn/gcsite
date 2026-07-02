# 🚀 GINTEC Solutions - Complete Deployment Guide

## What's Been Done ✅
- Image upload system fixed and tested locally
- All code changes committed to GitHub
- Security properly configured (.env excluded)
- Setup and diagnostics scripts ready
- Slider layout fixed and responsive

## What You Need to Do (3 Simple Steps)

---

## STEP 1: Download Files from GitHub

Go to: https://github.com/tonyihvn/gcsite

Download or copy these 5 files:

### File 1: public/.htaccess
**Path in repo:** `public/.htaccess`
**Upload to:** `/public_html/gcsite/public/.htaccess`

### File 2: public/setup-uploads.php  
**Path in repo:** `public/setup-uploads.php`
**Upload to:** `/public_html/gcsite/public/setup-uploads.php`

### File 3: public/diagnostics.php
**Path in repo:** `public/diagnostics.php`
**Upload to:** `/public_html/gcsite/public/diagnostics.php`

### File 4: core/FileUploader.php
**Path in repo:** `core/FileUploader.php`
**Upload to:** `/public_html/gcsite/core/FileUploader.php`

### File 5: app/views/home/index.php
**Path in repo:** `app/views/home/index.php`
**Upload to:** `/public_html/gcsite/app/views/home/index.php`

---

## STEP 2: Upload Files via cPanel

### Option A: Using cPanel File Manager (Easiest)

1. **Log into cPanel** → Click "File Manager"
2. Navigate to `/public_html/gcsite/public/`
3. Click "Upload Files"
4. Upload these files:
   - `.htaccess`
   - `setup-uploads.php`
   - `diagnostics.php`
5. Navigate to `/public_html/gcsite/core/`
6. Upload: `FileUploader.php`
7. Navigate to `/public_html/gcsite/app/views/home/`
8. Upload: `index.php` (overwrite existing)

**Replace if prompted** (yes, overwrite)

### Option B: Using FTP (WinSCP, FileZilla)

1. Connect to your server via FTP
2. Navigate to `/public_html/gcsite/`
3. Upload each file to its correct location
4. Replace existing files

---

## STEP 3: Initialize Upload Directories

### Visit this URL in your browser:

```
https://gintec.com.ng/setup-uploads.php?token=gintec-super-secret-key-development-only-change-in-production
```

### What you should see:
- ✅ Green status page with checkmarks
- ✅ "Upload directories setup complete!"
- ✅ All directories created (slides, services, products, etc.)

**If you see 404:** Your `.htaccess` file wasn't uploaded correctly. Re-upload it to `/public_html/gcsite/public/.htaccess`

---

## STEP 4: Test Everything Works

### Visit diagnostics page:

```
https://gintec.com.ng/diagnostics.php?token=gintec-super-secret-key-development-only-change-in-production
```

### You should see:
- ✅ Directory status table showing all 3 directories exist and are writable
- ✅ Test upload section

### Test each directory:
1. **Select "Slides"** → Upload test image → Should see ✅ Success
2. **Select "Services"** → Upload test image → Should see ✅ Success  
3. **Select "Products"** → Upload test image → Should see ✅ Success

---

## STEP 5: Test in Admin Panel

1. **Log into admin:** https://gintec.com.ng/admin
2. **Go to admin** → **Slides** → **Create New Slide**
3. **Upload an image** → Click "Create Slide"
4. **Go to frontend homepage** → Verify the slide appears with image
5. **Repeat for Services** and **Products**

---

## STEP 6: Cleanup & Security

After everything works:

1. **Delete these files from production** (for security):
   - Delete `/public_html/gcsite/public/diagnostics.php`
   - Delete `/public_html/gcsite/public/setup-uploads.php`

2. **Verify in cPanel** that `.env` is NOT visible in file listing (it shouldn't be)

3. **Test one more time** that images still upload and display

---

## ✅ Complete Checklist

```
UPLOAD FILES:
[ ] public/.htaccess
[ ] public/setup-uploads.php
[ ] public/diagnostics.php
[ ] core/FileUploader.php
[ ] app/views/home/index.php

INITIALIZE:
[ ] Visit setup-uploads.php URL
[ ] See green ✅ status page

TEST DIRECTORIES:
[ ] diagnostics.php - test Slides upload
[ ] diagnostics.php - test Services upload
[ ] diagnostics.php - test Products upload

TEST IN ADMIN:
[ ] Upload Slide image → verify on homepage
[ ] Upload Service image → verify on services page
[ ] Upload Product image → verify on products page

CLEANUP:
[ ] Delete diagnostics.php from production
[ ] Delete setup-uploads.php from production
[ ] Verify images still work
```

---

## 🆘 Troubleshooting

### Setup script shows 404
**Solution:** Re-upload `.htaccess` file to `/public_html/gcsite/public/.htaccess`

### Diagnostics shows directories don't exist
**Solution:** Check if setup-uploads.php ran successfully with green status

### Upload test fails in diagnostics
**Solution:** Check file permissions - should be 755
Run setup-uploads.php again

### Images don't display on frontend
**Solution:** 
- Check browser console for 404 errors
- Verify image path is correct in database
- Check `/public_html/gcsite/public/assets/uploads/` contains images

---

## 📱 Support URLs

**GitHub Repo:** https://github.com/tonyihvn/gcsite
**Main Website:** https://gintec.com.ng
**Admin Panel:** https://gintec.com.ng/admin

---

## ⏱️ Estimated Time: 15-20 minutes

1. Download files: 2 min
2. Upload files: 5 min
3. Run setup: 1 min
4. Test: 5 min
5. Cleanup: 2 min

**Total: ~15 minutes**

---

## Next Steps

1. Follow this guide step-by-step
2. Upload the 5 files
3. Run setup-uploads.php
4. Test in diagnostics.php
5. Test in admin panel
6. Cleanup and you're done! 🎉

If anything doesn't work or you have questions, check the troubleshooting section above.
