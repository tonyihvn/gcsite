<?php
/**
 * Menu Manager Admin Page
 * GINTEC Solutions
 */
?>

<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Menu Manager</h2>
            <p class="text-muted">Manage website navigation menus. Drag to reorder, click to edit.</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" onclick="openMenuModal()">
                <i class="fas fa-plus"></i> Add New Menu Item
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div id="menu-hierarchy" class="menu-tree">
                        <?php
                        if (!empty($hierarchy)) {
                            renderMenuTree($hierarchy, $menus);
                        } else {
                            echo '<p class="text-muted">No menu items found</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($pages_without_menu)): ?>
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Available Pages</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">These pages don't have menu items yet. Click to create menu items for them.</p>
                    <div class="list-group">
                        <?php foreach ($pages_without_menu as $page): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($page['title']); ?></h6>
                                <small class="text-muted">/<?php echo htmlspecialchars($page['slug']); ?></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-success" onclick="createMenuFromPage(<?php echo $page['id']; ?>)">
                                <i class="fas fa-plus"></i> Add to Menu
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Menu Details</h5>
                </div>
                <div class="card-body" id="menu-details">
                    <p class="text-muted">Select a menu item to edit</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Modal -->
<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="menuForm" onsubmit="saveMenu(event)">
                <div class="modal-body">
                    <input type="hidden" id="menuId" name="id">

                    <div class="mb-3">
                        <label class="form-label">Label *</label>
                        <input type="text" class="form-control" id="menuLabel" name="label" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" id="menuUrl" name="url" placeholder="/example">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome class)</label>
                        <input type="text" class="form-control" id="menuIcon" name="icon" placeholder="fas fa-home">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent Menu</label>
                        <select class="form-control" id="menuParent" name="parent_id">
                            <option value="">-- Root Menu --</option>
                            <?php foreach ($menus as $menu): ?>
                                <?php if (empty($menu['parent_id'])): ?>
                                    <option value="<?php echo $menu['id']; ?>">
                                        <?php echo htmlspecialchars($menu['label']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" id="menuStatus" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" class="form-control" id="menuOrder" name="menu_order" min="0" value="0">
                        <small class="text-muted">Lower numbers appear first. Parent items should have lower numbers than their children.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Menu Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.menu-tree {
    list-style: none;
    padding: 0;
}

.menu-item {
    padding: 12px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-left: 3px solid #667eea;
    border-radius: 4px;
    cursor: move;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s;
}

.menu-item:hover {
    background: #e9ecef;
}

.menu-item.nested {
    margin-left: 40px;
    border-left-color: #764ba2;
}

.menu-item-content {
    flex-grow: 1;
}

.menu-item-label {
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.menu-item-icon {
    color: #667eea;
    font-size: 14px;
}

.menu-item-url {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

.menu-item-actions {
    display: flex;
    gap: 8px;
}

.menu-item-actions button {
    padding: 4px 8px;
    font-size: 12px;
}

.drag-handle {
    cursor: grab;
    color: #adb5bd;
    margin-right: 8px;
}

.drag-handle:active {
    cursor: grabbing;
}
</style>

<script>
let currentMenuModal = null;

function openMenuModal(menuId = null) {
    if (currentMenuModal) {
        currentMenuModal.show();
    } else {
        currentMenuModal = new bootstrap.Modal(document.getElementById('menuModal'));
        currentMenuModal.show();
    }

    // Reset form
    document.getElementById('menuForm').reset();
    document.getElementById('menuId').value = '';

    if (menuId) {
        // Load menu data for editing
        loadMenuData(menuId);
    }
}

function loadMenuData(menuId) {
    const menu = <?php echo json_encode($menus); ?>.find(m => m.id == menuId);
    if (menu) {
        document.getElementById('menuId').value = menu.id;
        document.getElementById('menuLabel').value = menu.label;
        document.getElementById('menuUrl').value = menu.url || '';
        document.getElementById('menuIcon').value = menu.icon || '';
        document.getElementById('menuParent').value = menu.parent_id || '';
        document.getElementById('menuStatus').value = menu.status || 'active';
        document.getElementById('menuOrder').value = menu.menu_order || 0;
    }
}

function saveMenu(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('menuForm'));

    fetch('/admin/menu/save', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Failed to save menu');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Menu item saved successfully',
                    confirmButtonText: 'OK'
                }).then(() => {
                    currentMenuModal.hide();
                    location.reload();
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Error saving menu',
                confirmButtonText: 'OK'
            });
        });
}

function deleteMenuItem(menuId) {
    Swal.fire({
        icon: 'warning',
        title: 'Delete Menu Item?',
        text: 'This action cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = '/admin/menu/delete/' + menuId;
        }
    });
}

function editMenuItem(menuId) {
    openMenuModal(menuId);
}

function moveMenuItemUp(menuId) {
    const allMenus = <?php echo json_encode($menus); ?>;
    const currentMenu = allMenus.find(m => m.id == menuId);
    
    if (!currentMenu) return;
    
    // Find the order to change to (decrease by 1)
    const newOrder = Math.max(0, (currentMenu.menu_order || 0) - 1);
    updateMenuOrder(menuId, newOrder);
}

function moveMenuItemDown(menuId) {
    const allMenus = <?php echo json_encode($menus); ?>;
    const currentMenu = allMenus.find(m => m.id == menuId);
    
    if (!currentMenu) return;
    
    // Find the order to change to (increase by 1)
    const newOrder = (currentMenu.menu_order || 0) + 1;
    updateMenuOrder(menuId, newOrder);
}

function createMenuFromPage(pageId) {
    fetch('/admin/menu/create-from-page/' + pageId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Menu item created successfully',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.error || 'Failed to create menu item',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to create menu item: ' + error.message,
                confirmButtonText: 'OK'
            });
        });
}

function updateMenuOrder(menuId, newOrder) {
    fetch('/admin/menu/update-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: menuId, menu_order: newOrder })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to update menu order',
                confirmButtonText: 'OK'
            });
        });
}

// Initialize drag-and-drop
document.addEventListener('DOMContentLoaded', function () {
    initializeDragDrop();
});

function initializeDragDrop() {
    const items = document.querySelectorAll('.menu-item');

    items.forEach(item => {
        item.draggable = true;

        item.addEventListener('dragstart', function (e) {
            e.dataTransfer.effectAllowed = 'move';
            this.classList.add('dragging');
        });

        item.addEventListener('dragend', function (e) {
            this.classList.remove('dragging');
        });

        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
        });

        item.addEventListener('dragleave', function (e) {
            this.classList.remove('drag-over');
        });

        item.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            const dragging = document.querySelector('.menu-item.dragging');
            if (dragging && dragging !== this) {
                this.parentNode.insertBefore(dragging, this);
                saveMenuOrder();
            }
        });
    });
}

function saveMenuOrder() {
    const items = [];
    document.querySelectorAll('.menu-item').forEach((item, index) => {
        const menuId = item.dataset.menuId;
        const parentId = item.dataset.parentId || null;
        items.push({ id: menuId, parent_id: parentId });
    });

    fetch('/admin/menus/reorder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items: items }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save menu order');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Menu order saved successfully');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Failed to save menu order',
                confirmButtonText: 'OK'
            });
        });
}
</script>

<?php
/**
 * Helper function to render menu tree
 */
function renderMenuTree($items, $allMenus) {
    foreach ($items as $item) {
        $hasChildren = !empty($item['children']);
        $statusClass = $item['status'] === 'active' ? '' : 'opacity-50';

        echo '<div class="menu-item ' . $statusClass . '" data-menu-id="' . $item['id'] . '" data-parent-id="' . ($item['parent_id'] ?? '') . '" data-order="' . ($item['menu_order'] ?? 0) . '">';
        echo '<span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>';
        echo '<div class="menu-item-content">';
        echo '<div class="menu-item-label">';

        if (!empty($item['icon'])) {
            echo '<i class="' . htmlspecialchars($item['icon']) . ' menu-item-icon"></i>';
        }

        echo htmlspecialchars($item['label']);
        echo ' <span class="badge bg-secondary">#' . ($item['menu_order'] ?? 0) . '</span>';
        echo '</div>';

        if (!empty($item['url'])) {
            echo '<div class="menu-item-url">' . htmlspecialchars($item['url']) . '</div>';
        }

        echo '</div>';
        echo '<div class="menu-item-actions">';
        echo '<button type="button" class="btn btn-sm btn-warning" onclick="moveMenuItemUp(' . $item['id'] . ')" title="Move Up">';
        echo '<i class="fas fa-arrow-up"></i></button>';
        echo '<button type="button" class="btn btn-sm btn-warning" onclick="moveMenuItemDown(' . $item['id'] . ')" title="Move Down">';
        echo '<i class="fas fa-arrow-down"></i></button>';
        echo '<button type="button" class="btn btn-sm btn-info" onclick="editMenuItem(' . $item['id'] . ')">';
        echo '<i class="fas fa-edit"></i> Edit</button>';
        echo '<button type="button" class="btn btn-sm btn-danger" onclick="deleteMenuItem(' . $item['id'] . ')">';
        echo '<i class="fas fa-trash"></i> Delete</button>';
        echo '</div>';
        echo '</div>';

        // Render children
        if ($hasChildren) {
            echo '<div style="margin-left: 20px;">';
            renderMenuTree($item['children'], $allMenus);
            echo '</div>';
        }
    }
}
?>
