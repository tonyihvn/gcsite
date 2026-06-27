
<div class="table-responsive">
    <div class="mb-3">
        <a href="<?= route('admin/faqs/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New FAQ
        </a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Question</th>
                <th>Category</th>
                <th>Status</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faqs as $faq): ?>
            <tr>
                <td><?= substr(htmlspecialchars($faq['question']), 0, 50) ?>...</td>
                <td><?= htmlspecialchars($faq['category'] ?? '-') ?></td>
                <td><span class="badge bg-<?= $faq['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($faq['status']) ?></span></td>
                <td><?= $faq['sort_order'] ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFaqModal" onclick="editFaq(<?= $faq['id'] ?>)">Edit</button>
                    <form method="POST" action="<?= route('admin/faqs/' . $faq['id'] . '/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmFormDelete(this.form, 'Are you sure you want to delete this FAQ?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
