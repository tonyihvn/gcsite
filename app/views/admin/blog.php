<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage Blog Posts</h3>
    <a href="<?= route('admin/blog/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Post
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= $post['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($post['title'] ?? '') ?></td>
                            <td><?= htmlspecialchars($post['author'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($post['category'] ?? 'Uncategorized') ?></td>
                            <td><?= $post['views'] ?? 0 ?></td>
                            <td>
                                <span class="badge bg-<?= ($post['status'] ?? '') === 'published' ? 'success' : 'warning' ?>">
                                    <?= $post['status'] ?? 'draft' ?>
                                </span>
                            </td>
                            <td><?= substr($post['published_at'] ?? '', 0, 10) ?></td>
                            <td>
                                <a href="<?= route('admin/blog/edit/' . $post['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= route('admin/blog/delete/' . $post['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this blog post?', 'true', this); return false;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No blog posts found. <a href="<?= route('admin/blog/create') ?>">Create one</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
