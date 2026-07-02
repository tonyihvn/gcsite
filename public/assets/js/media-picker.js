/**
 * Media Library Picker
 * GINTEC Solutions
 *
 * Wires up any button with class "media-browse-btn" to open the shared
 * #mediaLibraryModal, letting the admin pick an existing image, upload a
 * new one, or delete images from the server.
 *
 * Button attributes:
 *   data-target  = name of the input to receive the selected relative path
 *   data-preview = id of an <img> element to update with the selected image
 */
(function () {
    'use strict';

    var modalEl = document.getElementById('mediaLibraryModal');
    if (!modalEl || typeof bootstrap === 'undefined') {
        return;
    }

    var modal = new bootstrap.Modal(modalEl);
    var grid = document.getElementById('mediaLibraryGrid');
    var statusEl = document.getElementById('mediaLibraryStatus');
    var uploadForm = document.getElementById('mediaUploadForm');
    var uploadInput = document.getElementById('mediaUploadInput');

    var targetInput = null;
    var previewEl = null;

    function url(path) {
        return (window.BASE_URL || '') + '/' + String(path).replace(/^\/+/, '');
    }

    function setStatus(text) {
        statusEl.textContent = text;
    }

    function loadImages() {
        grid.innerHTML = '';
        setStatus('Loading images\u2026');

        fetch(url('admin/media/library'), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success || !data.images || !data.images.length) {
                    setStatus('No images yet. Upload one above.');
                    return;
                }
                setStatus(data.images.length + ' image(s). Click one to select.');
                data.images.forEach(function (img) { addTile(img, false); });
            })
            .catch(function () { setStatus('Failed to load images.'); });
    }

    function addTile(img, prepend) {
        var col = document.createElement('div');
        col.className = 'col-6 col-md-3';

        var card = document.createElement('div');
        card.className = 'card h-100 media-tile';
        card.style.cursor = 'pointer';

        var image = document.createElement('img');
        image.src = img.url;
        image.className = 'card-img-top';
        image.style.height = '110px';
        image.style.objectFit = 'cover';
        image.loading = 'lazy';

        var body = document.createElement('div');
        body.className = 'card-body p-2';

        var name = document.createElement('p');
        name.className = 'small text-truncate mb-1';
        name.title = img.name;
        name.textContent = img.name;

        var del = document.createElement('button');
        del.type = 'button';
        del.className = 'btn btn-sm btn-outline-danger w-100';
        del.innerHTML = '<i class="fas fa-trash"></i>';

        del.addEventListener('click', function (e) {
            e.stopPropagation();
            deleteImage(img, col);
        });

        card.addEventListener('click', function () { selectImage(img); });

        body.appendChild(name);
        body.appendChild(del);
        card.appendChild(image);
        card.appendChild(body);
        col.appendChild(card);

        if (prepend && grid.firstChild) {
            grid.insertBefore(col, grid.firstChild);
        } else {
            grid.appendChild(col);
        }
    }

    function selectImage(img) {
        if (targetInput) {
            targetInput.value = img.path;
            targetInput.dispatchEvent(new Event('change'));
        }
        if (previewEl) {
            previewEl.src = img.url;
            previewEl.style.display = 'inline-block';
        }
        modal.hide();
    }

    function deleteImage(img, col) {
        if (!window.confirm('Permanently delete "' + img.name + '" from the server?')) {
            return;
        }
        var body = new FormData();
        body.append('csrf_token', window.CSRF_TOKEN);
        body.append('path', img.path);

        fetch(url('admin/media/browser-delete'), { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    col.remove();
                } else {
                    window.alert(data.message || 'Delete failed.');
                }
            })
            .catch(function () { window.alert('Delete failed.'); });
    }

    if (uploadForm) {
        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!uploadInput.files.length) {
                return;
            }
            var body = new FormData();
            body.append('csrf_token', window.CSRF_TOKEN);
            body.append('media_file', uploadInput.files[0]);
            setStatus('Uploading\u2026');

            fetch(url('admin/media/browser-upload'), { method: 'POST', body: body })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        addTile(data.image, true);
                        uploadInput.value = '';
                        setStatus('Uploaded. Click an image to select.');
                    } else {
                        setStatus(data.message || 'Upload failed.');
                    }
                })
                .catch(function () { setStatus('Upload failed.'); });
        });
    }

    document.querySelectorAll('.media-browse-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetName = btn.getAttribute('data-target');
            var previewId = btn.getAttribute('data-preview');
            targetInput = targetName ? document.getElementsByName(targetName)[0] : null;
            previewEl = previewId ? document.getElementById(previewId) : null;
            loadImages();
            modal.show();
        });
    });
})();
