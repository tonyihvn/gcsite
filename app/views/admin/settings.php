<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">System Settings</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= route('admin/settings') ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="company_name" value="<?= \App\Models\Setting::get('company_name', '') ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Company Email</label>
                <input type="email" class="form-control" name="company_email" value="<?= \App\Models\Setting::get('company_email', '') ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Company Phone</label>
                <input type="tel" class="form-control" name="company_phone" value="<?= \App\Models\Setting::get('company_phone', '') ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Company Address</label>
                <input type="text" class="form-control" name="company_address" value="<?= \App\Models\Setting::get('company_address', '') ?>">
            </div>
            
            <div class="col-12">
                <label class="form-label">Company Website</label>
                <input type="text" class="form-control" name="company_website" value="<?= \App\Models\Setting::get('company_website', '') ?>">
            </div>
            
            <div class="col-12">
                <label class="form-label">Company CEO</label>
                <input type="text" class="form-control" name="company_ceo" value="<?= \App\Models\Setting::get('company_ceo', '') ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Company Description</label>
                <textarea class="form-control" name="company_description" rows="4"><?= \App\Models\Setting::get('company_description', '') ?></textarea>
            </div>

            <!-- Site Media Section -->
            <div class="col-12 mt-4 pt-3 border-top">
                <h6 class="mb-3"><i class="fas fa-images"></i> Site Media</h6>
            </div>

            <div class="col-md-4">
                <label class="form-label">Site Logo</label>
                <input type="file" class="form-control" name="site_logo" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                <small class="text-muted">PNG, JPG (Recommended: 200x60px)</small>
                <?php if ($logo = \App\Models\Setting::get('site_logo')): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($logo) ?>" alt="Logo" style="max-width: 150px; max-height: 60px;">
                    </div>
                <?php endif; ?>
                <img id="logoPreview" style="display:none; max-width: 150px; max-height: 60px; margin-top: 10px;">
            </div>

            <div class="col-md-4">
                <label class="form-label">Site Favicon</label>
                <input type="file" class="form-control" name="site_favicon" accept="image/*" onchange="previewImage(this, 'faviconPreview')">
                <small class="text-muted">ICO, PNG (Recommended: 32x32px)</small>
                <?php if ($favicon = \App\Models\Setting::get('site_favicon')): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($favicon) ?>" alt="Favicon" style="max-width: 50px; max-height: 50px;">
                    </div>
                <?php endif; ?>
                <img id="faviconPreview" style="display:none; max-width: 50px; max-height: 50px; margin-top: 10px;">
            </div>

            <div class="col-md-4">
                <label class="form-label">Page Banner</label>
                <input type="file" class="form-control" name="page_banner" accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                <small class="text-muted">JPG, PNG (Recommended: 1200x400px)</small>
                <?php if ($banner = \App\Models\Setting::get('page_banner')): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($banner) ?>" alt="Banner" style="max-width: 200px; max-height: 100px;">
                    </div>
                <?php endif; ?>
                <img id="bannerPreview" style="display:none; max-width: 200px; max-height: 100px; margin-top: 10px;">
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
