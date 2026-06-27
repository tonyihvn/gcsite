<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($team_member) ? 'Edit Team Member' : 'Add New Team Member' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/team' . (isset($team_member) ? '/' . $team_member['id'] : '')) ?>" class="row g-3">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" value="<?= $team_member['name'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Title *</label>
                <input type="text" class="form-control" name="title" value="<?= $team_member['title'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Department *</label>
                <input type="text" class="form-control" name="department" value="<?= $team_member['department'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" value="<?= $team_member['email'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" value="<?= $team_member['phone'] ?? '' ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">LinkedIn URL</label>
                <input type="url" class="form-control" name="linkedin_url" value="<?= $team_member['linkedin_url'] ?? '' ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Twitter URL</label>
                <input type="url" class="form-control" name="twitter_url" value="<?= $team_member['twitter_url'] ?? '' ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" <?= ($team_member['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($team_member['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="team_image" accept="image/*" onchange="previewTeamImage(event)">
                <small class="text-muted">JPG, PNG, or WebP (Recommended: 400x500px)</small>
                <?php if (isset($team_member) && !empty($team_member['image'])): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($team_member['image']) ?>" alt="<?= $team_member['name'] ?>" style="max-width: 150px; border-radius: 8px;">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-12">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="5"><?= $team_member['bio'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <img id="teamImagePreview" style="display: none; max-width: 150px; border-radius: 8px; margin-top: 10px;">
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($team_member) ? 'Update' : 'Create' ?>
                    </button>
                    <a href="<?= route('admin/team') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview team image on file selection
    function previewTeamImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('teamImagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
