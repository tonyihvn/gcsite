
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Type</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
            <tr>
                <td><?= htmlspecialchars($feedback['name']) ?></td>
                <td><?= htmlspecialchars($feedback['email'] ?? '-') ?></td>
                <td><?= htmlspecialchars($feedback['company'] ?? '-') ?></td>
                <td><span class="badge bg-secondary"><?= ucfirst($feedback['type']) ?></span></td>
                <td>
                    <?php for ($i = 0; $i < $feedback['rating']; $i++): ?>
                    <i class="fas fa-star text-warning"></i>
                    <?php endfor; ?>
                </td>
                <td><span class="badge bg-<?= $feedback['status'] === 'new' ? 'info' : 'success' ?>"><?= ucfirst($feedback['status']) ?></span></td>
                <td>
                    <a href="<?= route('admin/feedbacks/' . $feedback['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

