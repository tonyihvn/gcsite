<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-images"></i> Media Library</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form id="pageMediaUploadForm" class="row g-2 align-items-center">
            <div class="col-md-8">
                <input type="file" class="form-control" id="pageMediaUploadInput" name="media_file" accept="image/*">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-upload"></i> Upload Image</button>
            </div>
            <div class="col-12"><small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small></div>
        </form>
        <div id="pageMediaStatus" class="small text-muted mt-2"></div>
    </div>
</div>

<div class="row g-3" id="pageMediaGrid">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $file): ?>
            <div class="col-6 col-md-3" data-path="<?= htmlspecialchars($file['path']) ?>">
                <div class="card h-100">
                    <img src="<?= htmlspecialchars($file['url']) ?>" alt="<?= htmlspecialchars($file['name']) ?>" style="height: 150px; object-fit: cover;" class="card-img-top" loading="lazy">
                    <div class="card-body text-center p-2">
                        <p class="small text-truncate mb-1" title="<?= htmlspecialchars($file['name']) ?>"><?= htmlspecialchars($file['name']) ?></p>
                        <small class="text-muted d-block mb-2"><?= htmlspecialchars($file['size']) ?></small>
                        <button type="button" class="btn btn-sm btn-danger w-100 page-media-del" data-path="<?= htmlspecialchars($file['path']) ?>" data-name="<?= htmlspecialchars($file['name']) ?>">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center mb-0">
                <i class="fas fa-info-circle"></i> No images uploaded yet. Use the form above to add one.
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
(function () {
    var status = document.getElementById('pageMediaStatus');
    var grid = document.getElementById('pageMediaGrid');

    function url(path) {
        return (window.BASE_URL || '') + '/' + String(path).replace(/^\/+/, '');
    }

    function bindDelete(btn) {
        btn.addEventListener('click', function () {
            var path = btn.getAttribute('data-path');
            var name = btn.getAttribute('data-name');
            if (!window.confirm('Permanently delete "' + name + '" from the server?')) {
                return;
            }
            var body = new FormData();
            body.append('csrf_token', window.CSRF_TOKEN);
            body.append('path', path);
            fetch(url('admin/media/browser-delete'), { method: 'POST', body: body })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        var col = btn.closest('[data-path]');
                        if (col) { col.remove(); }
                    } else {
                        window.alert(data.message || 'Delete failed.');
                    }
                })
                .catch(function () { window.alert('Delete failed.'); });
        });
    }

    document.querySelectorAll('.page-media-del').forEach(bindDelete);

    var form = document.getElementById('pageMediaUploadForm');
    var input = document.getElementById('pageMediaUploadInput');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!input.files.length) { return; }
        var body = new FormData();
        body.append('csrf_token', window.CSRF_TOKEN);
        body.append('media_file', input.files[0]);
        status.textContent = 'Uploading\u2026';
        fetch(url('admin/media/browser-upload'), { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) { status.textContent = data.message || 'Upload failed.'; return; }
                status.textContent = 'Uploaded successfully.';
                input.value = '';
                var col = document.createElement('div');
                col.className = 'col-6 col-md-3';
                col.setAttribute('data-path', data.image.path);
                col.innerHTML =
                    '<div class="card h-100">' +
                        '<img src="' + data.image.url + '" style="height:150px;object-fit:cover;" class="card-img-top">' +
                        '<div class="card-body text-center p-2">' +
                            '<p class="small text-truncate mb-1" title="' + data.image.name + '">' + data.image.name + '</p>' +
                            '<button type="button" class="btn btn-sm btn-danger w-100 page-media-del" data-path="' + data.image.path + '" data-name="' + data.image.name + '"><i class="fas fa-trash"></i> Delete</button>' +
                        '</div>' +
                    '</div>';
                if (grid.firstChild) { grid.insertBefore(col, grid.firstChild); } else { grid.appendChild(col); }
                bindDelete(col.querySelector('.page-media-del'));
            })
            .catch(function () { status.textContent = 'Upload failed.'; });
    });
})();
</script>
