<style>
    .color-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .color-input-group input[type="color"] {
        width: 60px;
        height: 50px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
    }
    .color-preview {
        display: inline-block;
        width: 50px;
        height: 50px;
        border-radius: 5px;
        border: 2px solid #ddd;
    }
    .typography-section, .colors-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .typography-preview {
        background: white;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid var(--primary-color);
        margin-top: 10px;
    }
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .theme-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .theme-card h5 {
        margin-bottom: 15px;
        color: var(--primary-color);
        font-weight: 600;
    }
</style>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-palette"></i> Theme & Color Settings</h2>
            <p class="text-muted">Customize the appearance and typography of your website</p>
        </div>
    </div>

    <form method="POST" action="<?= route('admin/theme') ?>" class="form-horizontal">
        <?= csrf_field() ?>

        <!-- Colors Section -->
        <div class="theme-card">
            <h5><i class="fas fa-swatchbook"></i> Color Scheme</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Primary Color</label>
                    <div class="color-input-group">
                        <input type="color" name="primary_color" class="form-control" value="<?= $theme_settings['primary_color'] ?? '#667eea' ?>" id="primaryColor">
                        <input type="text" class="form-control form-control-sm" value="<?= $theme_settings['primary_color'] ?? '#667eea' ?>" readonly style="max-width: 100px;">
                    </div>
                    <small class="text-muted">Main brand color used for buttons, links, and accents</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Secondary Color</label>
                    <div class="color-input-group">
                        <input type="color" name="secondary_color" class="form-control" value="<?= $theme_settings['secondary_color'] ?? '#764ba2' ?>" id="secondaryColor">
                        <input type="text" class="form-control form-control-sm" value="<?= $theme_settings['secondary_color'] ?? '#764ba2' ?>" readonly style="max-width: 100px;">
                    </div>
                    <small class="text-muted">Secondary brand color for hover states and gradients</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Accent Color</label>
                    <div class="color-input-group">
                        <input type="color" name="accent_color" class="form-control" value="<?= $theme_settings['accent_color'] ?? '#667eea' ?>" id="accentColor">
                        <input type="text" class="form-control form-control-sm" value="<?= $theme_settings['accent_color'] ?? '#667eea' ?>" readonly style="max-width: 100px;">
                    </div>
                    <small class="text-muted">Highlight and emphasis color</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Text Color</label>
                    <div class="color-input-group">
                        <input type="color" name="text_color" class="form-control" value="<?= $theme_settings['text_color'] ?? '#333333' ?>" id="textColor">
                        <input type="text" class="form-control form-control-sm" value="<?= $theme_settings['text_color'] ?? '#333333' ?>" readonly style="max-width: 100px;">
                    </div>
                    <small class="text-muted">Default text color for body content</small>
                </div>
            </div>

            <!-- Color Preview -->
            <div class="row g-3 mt-3">
                <div class="col-md-12">
                    <label class="form-label">Color Preview</label>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <div class="color-preview" id="primaryPreview" style="background: <?= $theme_settings['primary_color'] ?? '#667eea' ?>;"></div>
                            <small class="d-block text-center mt-2">Primary</small>
                        </div>
                        <div class="col-md-2">
                            <div class="color-preview" id="secondaryPreview" style="background: <?= $theme_settings['secondary_color'] ?? '#764ba2' ?>;"></div>
                            <small class="d-block text-center mt-2">Secondary</small>
                        </div>
                        <div class="col-md-2">
                            <div class="color-preview" id="accentPreview" style="background: <?= $theme_settings['accent_color'] ?? '#667eea' ?>;"></div>
                            <small class="d-block text-center mt-2">Accent</small>
                        </div>
                        <div class="col-md-2">
                            <div class="color-preview" id="textPreview" style="background: <?= $theme_settings['text_color'] ?? '#333333' ?>;"></div>
                            <small class="d-block text-center mt-2">Text</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Typography Section -->
        <div class="theme-card">
            <h5><i class="fas fa-font"></i> Typography</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Heading Font</label>
                    <input type="text" name="heading_font" class="form-control" value="<?= $theme_settings['heading_font'] ?? 'Segoe UI, Roboto, sans-serif' ?>" placeholder="e.g., Arial, Helvetica, sans-serif">
                    <small class="text-muted">Font stack for headings (h1-h6)</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Body Font</label>
                    <input type="text" name="body_font" class="form-control" value="<?= $theme_settings['body_font'] ?? 'Segoe UI, Roboto, sans-serif' ?>" placeholder="e.g., Georgia, serif">
                    <small class="text-muted">Font stack for body text</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Heading Size (px)</label>
                    <input type="number" name="heading_size" class="form-control" value="<?= $theme_settings['heading_size'] ?? 28 ?>" min="16" max="48" step="1">
                    <small class="text-muted">Base size for h1 headings</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Body Font Size (px)</label>
                    <input type="number" name="body_size" class="form-control" value="<?= $theme_settings['body_size'] ?? 14 ?>" min="10" max="20" step="1">
                    <small class="text-muted">Base size for paragraph text</small>
                </div>
            </div>

            <!-- Typography Preview -->
            <div class="typography-preview mt-3">
                <div style="font-size: <?= $theme_settings['heading_size'] ?? 28 ?>px; font-family: <?= $theme_settings['heading_font'] ?? 'Segoe UI, Roboto, sans-serif' ?>; font-weight: 600; color: <?= $theme_settings['text_color'] ?? '#333333' ?>; margin-bottom: 10px;">
                    Heading Preview
                </div>
                <div style="font-size: <?= $theme_settings['body_size'] ?? 14 ?>px; font-family: <?= $theme_settings['body_font'] ?? 'Segoe UI, Roboto, sans-serif' ?>; color: <?= $theme_settings['text_color'] ?? '#333333' ?>; line-height: 1.6;">
                    This is body text preview. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Your website content will appear in this font and size.
                </div>
            </div>
        </div>

        <!-- UI Elements Section -->
        <div class="theme-card">
            <h5><i class="fas fa-cube"></i> UI Elements</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Button Style</label>
                    <select name="button_style" class="form-select">
                        <option value="rounded" <?= ($theme_settings['button_style'] ?? 'rounded') === 'rounded' ? 'selected' : '' ?>>Rounded</option>
                        <option value="square" <?= ($theme_settings['button_style'] ?? 'rounded') === 'square' ? 'selected' : '' ?>>Square</option>
                        <option value="pill" <?= ($theme_settings['button_style'] ?? 'rounded') === 'pill' ? 'selected' : '' ?>>Pill Shaped</option>
                    </select>
                    <small class="text-muted">Button corner style</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Border Radius (px)</label>
                    <input type="number" name="border_radius" class="form-control" value="<?= $theme_settings['border_radius'] ?? 5 ?>" min="0" max="20" step="1">
                    <small class="text-muted">Corner radius for cards, buttons, and form elements</small>
                </div>
            </div>

            <!-- UI Preview -->
            <div class="row g-3 mt-3">
                <div class="col-md-12">
                    <label class="form-label">Button Preview</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn" style="background-color: <?= $theme_settings['primary_color'] ?? '#667eea' ?>; color: white; border-radius: <?= $theme_settings['border_radius'] ?? 5 ?>px; border: none; padding: 8px 16px; cursor: pointer;">
                            Primary Button
                        </button>
                        <button type="button" class="btn" style="background-color: <?= $theme_settings['secondary_color'] ?? '#764ba2' ?>; color: white; border-radius: <?= $theme_settings['border_radius'] ?? 5 ?>px; border: none; padding: 8px 16px; cursor: pointer;">
                            Secondary Button
                        </button>
                        <button type="button" class="btn" style="color: <?= $theme_settings['primary_color'] ?? '#667eea' ?>; border: 2px solid <?= $theme_settings['primary_color'] ?? '#667eea' ?>; border-radius: <?= $theme_settings['border_radius'] ?? 5 ?>px; background: transparent; padding: 8px 16px; cursor: pointer;">
                            Outline Button
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Background Settings Section -->
        <div class="theme-card">
            <h5><i class="fas fa-image"></i> Website Background</h5>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Background Image</label>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label-small">Upload Image File</label>
                            <input type="file" class="form-control" name="background_image_file" accept="image/*" onchange="previewBackgroundImage(event)">
                            <small class="text-muted">JPG, PNG, WebP (Max 10MB recommended for performance)</small>
                        </div>
                    </div>
                    <?php if (isset($theme_settings) && !empty($theme_settings['background_image'])): ?>
                        <div class="mt-2">
                            <small class="text-muted">Current background image:</small>
                            <img src="<?= \Core\FileUploader::getImageUrl($theme_settings['background_image']) ?>" alt="Background" style="max-width: 200px; max-height: 150px; border-radius: 4px; margin-top: 5px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Background Opacity</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="range" name="bg_opacity" class="form-range" value="<?= $theme_settings['bg_opacity'] ?? 1 ?>" min="0" max="1" step="0.1" id="bgOpacitySlider" style="flex: 1;" onchange="updateOpacityValue()">
                        <span id="opacityValue" class="badge bg-primary"><?= (float)($theme_settings['bg_opacity'] ?? 1) ?></span>
                    </div>
                    <small class="text-muted">1 = fully opaque background, 0 = fully transparent</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Overlay Color</label>
                    <div class="color-input-group">
                        <input type="color" name="bg_overlay_color_picker" class="form-control" value="#000000" id="overlayColorPicker" style="width: 60px; height: 50px;" onchange="updateOverlayColor()">
                        <div style="flex: 1;">
                            <input type="text" name="bg_overlay_color" class="form-control" id="overlayColorText" value="<?= $theme_settings['bg_overlay_color'] ?? 'rgba(0, 0, 0, 0)' ?>" readonly>
                            <small class="text-muted">Color to overlay on background image</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Background Position</label>
                    <select name="bg_position" class="form-select">
                        <option value="center" <?= ($theme_settings['bg_position'] ?? 'center') === 'center' ? 'selected' : '' ?>>Center</option>
                        <option value="top" <?= ($theme_settings['bg_position'] ?? 'center') === 'top' ? 'selected' : '' ?>>Top</option>
                        <option value="bottom" <?= ($theme_settings['bg_position'] ?? 'center') === 'bottom' ? 'selected' : '' ?>>Bottom</option>
                        <option value="left" <?= ($theme_settings['bg_position'] ?? 'center') === 'left' ? 'selected' : '' ?>>Left</option>
                        <option value="right" <?= ($theme_settings['bg_position'] ?? 'center') === 'right' ? 'selected' : '' ?>>Right</option>
                        <option value="top left" <?= ($theme_settings['bg_position'] ?? 'center') === 'top left' ? 'selected' : '' ?>>Top Left</option>
                        <option value="top right" <?= ($theme_settings['bg_position'] ?? 'center') === 'top right' ? 'selected' : '' ?>>Top Right</option>
                        <option value="bottom left" <?= ($theme_settings['bg_position'] ?? 'center') === 'bottom left' ? 'selected' : '' ?>>Bottom Left</option>
                        <option value="bottom right" <?= ($theme_settings['bg_position'] ?? 'center') === 'bottom right' ? 'selected' : '' ?>>Bottom Right</option>
                    </select>
                    <small class="text-muted">Position of the background image</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Background Size</label>
                    <select name="bg_size" class="form-select">
                        <option value="cover" <?= ($theme_settings['bg_size'] ?? 'cover') === 'cover' ? 'selected' : '' ?>>Cover (fills entire space)</option>
                        <option value="contain" <?= ($theme_settings['bg_size'] ?? 'cover') === 'contain' ? 'selected' : '' ?>>Contain (whole image visible)</option>
                        <option value="100% 100%" <?= ($theme_settings['bg_size'] ?? 'cover') === '100% 100%' ? 'selected' : '' ?>>Stretch (100% x 100%)</option>
                        <option value="auto" <?= ($theme_settings['bg_size'] ?? 'cover') === 'auto' ? 'selected' : '' ?>>Auto (original size)</option>
                        <option value="100% auto" <?= ($theme_settings['bg_size'] ?? 'cover') === '100% auto' ? 'selected' : '' ?>>100% width, auto height</option>
                        <option value="auto 100%" <?= ($theme_settings['bg_size'] ?? 'cover') === 'auto 100%' ? 'selected' : '' ?>>Auto width, 100% height</option>
                    </select>
                    <small class="text-muted">How the background image scales</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Background Repeat</label>
                    <select name="bg_repeat" class="form-select">
                        <option value="no-repeat" <?= ($theme_settings['bg_repeat'] ?? 'no-repeat') === 'no-repeat' ? 'selected' : '' ?>>No Repeat</option>
                        <option value="repeat" <?= ($theme_settings['bg_repeat'] ?? 'no-repeat') === 'repeat' ? 'selected' : '' ?>>Repeat (both)</option>
                        <option value="repeat-x" <?= ($theme_settings['bg_repeat'] ?? 'no-repeat') === 'repeat-x' ? 'selected' : '' ?>>Repeat X (horizontal)</option>
                        <option value="repeat-y" <?= ($theme_settings['bg_repeat'] ?? 'no-repeat') === 'repeat-y' ? 'selected' : '' ?>>Repeat Y (vertical)</option>
                    </select>
                    <small class="text-muted">Whether the background image repeats</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Background Attachment</label>
                    <select name="bg_attachment" class="form-select">
                        <option value="fixed" <?= ($theme_settings['bg_attachment'] ?? 'fixed') === 'fixed' ? 'selected' : '' ?>>Fixed (parallax effect)</option>
                        <option value="scroll" <?= ($theme_settings['bg_attachment'] ?? 'fixed') === 'scroll' ? 'selected' : '' ?>>Scroll (moves with content)</option>
                    </select>
                    <small class="text-muted">Fixed creates a parallax effect, Scroll moves with the page</small>
                </div>
            </div>

            <!-- Background Preview -->
            <div class="row g-3 mt-3">
                <div class="col-md-12">
                    <label class="form-label">Background Preview</label>
                    <div id="backgroundPreview" style="width: 100%; height: 300px; border-radius: 8px; border: 2px solid #ddd; overflow: hidden; background-attachment: fixed; background-size: cover; background-position: center;">
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                            <p style="font-size: 18px; font-weight: 600;">Background Preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Save Theme Settings
                </button>
                <a href="<?= route('admin') ?>" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
    // Background image preview
    function previewBackgroundImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('backgroundPreview').style.backgroundImage = `url(${e.target.result})`;
                updateBackgroundPreview();
            };
            reader.readAsDataURL(file);
        }
    }

    // Update opacity display
    function updateOpacityValue() {
        const value = document.getElementById('bgOpacitySlider').value;
        document.getElementById('opacityValue').textContent = value;
        updateBackgroundPreview();
    }

    // Update background preview with all settings
    function updateBackgroundPreview() {
        const preview = document.getElementById('backgroundPreview');
        const position = document.querySelector('select[name="bg_position"]').value;
        const size = document.querySelector('select[name="bg_size"]').value;
        const repeat = document.querySelector('select[name="bg_repeat"]').value;
        const attachment = document.querySelector('select[name="bg_attachment"]').value;
        
        preview.style.backgroundPosition = position;
        preview.style.backgroundSize = size;
        preview.style.backgroundRepeat = repeat;
        preview.style.backgroundAttachment = attachment;
    }

    // Update overlay color
    function updateOverlayColor() {
        const colorHex = document.getElementById('overlayColorPicker').value;
        const opacity = 1 - parseFloat(document.getElementById('bgOpacitySlider').value);
        
        // Convert hex to rgba
        const r = parseInt(colorHex.substr(1,2), 16);
        const g = parseInt(colorHex.substr(3,2), 16);
        const b = parseInt(colorHex.substr(5,2), 16);
        
        const rgbaColor = `rgba(${r}, ${g}, ${b}, ${opacity})`;
        document.getElementById('overlayColorText').value = rgbaColor;
        
        // Update preview
        const preview = document.getElementById('backgroundPreview');
        if (preview.style.backgroundImage) {
            // Create overlay effect
            preview.style.backgroundColor = 'transparent';
        }
    }

    // Initialize preview on page load
    window.addEventListener('load', function() {
        const bgOpacity = document.getElementById('bgOpacitySlider').value;
        document.getElementById('opacityValue').textContent = bgOpacity;
        
        // Set initial background preview if image exists
        <?php if (!empty($theme_settings['background_image'])): ?>
            document.getElementById('backgroundPreview').style.backgroundImage = "url('<?= \Core\FileUploader::getImageUrl($theme_settings['background_image']) ?>')";
            updateBackgroundPreview();
        <?php endif; ?>
    });
    
    // Update color hex values and previews in real-time
    document.getElementById('primaryColor').addEventListener('input', function(e) {
        document.getElementById('primaryPreview').style.background = e.target.value;
        this.parentElement.querySelector('input[type="text"]').value = e.target.value;
    });

    document.getElementById('secondaryColor').addEventListener('input', function(e) {
        document.getElementById('secondaryPreview').style.background = e.target.value;
        this.parentElement.querySelector('input[type="text"]').value = e.target.value;
    });

    document.getElementById('accentColor').addEventListener('input', function(e) {
        document.getElementById('accentPreview').style.background = e.target.value;
        this.parentElement.querySelector('input[type="text"]').value = e.target.value;
    });

    document.getElementById('textColor').addEventListener('input', function(e) {
        document.getElementById('textPreview').style.background = e.target.value;
        this.parentElement.querySelector('input[type="text"]').value = e.target.value;
    });

    // Update typography preview
    const headingFontInput = document.querySelector('input[name="heading_font"]');
    const bodyFontInput = document.querySelector('input[name="body_font"]');
    const headingSizeInput = document.querySelector('input[name="heading_size"]');
    const bodySizeInput = document.querySelector('input[name="body_size"]');
    const borderRadiusInput = document.querySelector('input[name="border_radius"]');

    function updatePreview() {
        const preview = document.querySelector('.typography-preview');
        preview.style.setProperty('--heading-font', headingFontInput.value);
        preview.style.setProperty('--body-font', bodyFontInput.value);
        preview.children[0].style.fontFamily = headingFontInput.value;
        preview.children[0].style.fontSize = headingSizeInput.value + 'px';
        preview.children[1].style.fontFamily = bodyFontInput.value;
        preview.children[1].style.fontSize = bodySizeInput.value + 'px';
    }

    headingFontInput.addEventListener('change', updatePreview);
    bodyFontInput.addEventListener('change', updatePreview);
    headingSizeInput.addEventListener('input', updatePreview);
    bodySizeInput.addEventListener('input', updatePreview);

    // Add event listeners for background settings
    document.querySelector('select[name="bg_position"]').addEventListener('change', updateBackgroundPreview);
    document.querySelector('select[name="bg_size"]').addEventListener('change', updateBackgroundPreview);
    document.querySelector('select[name="bg_repeat"]').addEventListener('change', updateBackgroundPreview);
    document.querySelector('select[name="bg_attachment"]').addEventListener('change', updateBackgroundPreview);
    document.getElementById('bgOpacitySlider').addEventListener('input', updateOpacityValue);
</script>
