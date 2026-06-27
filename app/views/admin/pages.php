
<div class="table-responsive">
    <div class="mb-3">
        <a href="<?= route('admin/pages/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Page
        </a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Visibility</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= htmlspecialchars($page['title']) ?></td>
                <td><code><?= htmlspecialchars($page['slug']) ?></code></td>
                <td><span class="badge bg-<?= $page['status'] === 'published' ? 'success' : 'warning' ?>"><?= ucfirst($page['status']) ?></span></td>
                <td><span class="badge bg-info"><?= ucfirst($page['visibility']) ?></span></td>
                <td><?= substr($page['created_at'], 0, 10) ?></td>
                <td>
                    <a href="<?= route('admin/pages/' . $page['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="<?= route('admin/pages/' . $page['id'] . '/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmFormDelete(this.form, 'Are you sure you want to delete this page?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

