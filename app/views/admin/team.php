<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage Team Members</h3>
    <a href="<?= route('admin/team/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Member
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($team_members)): ?>
                    <?php foreach ($team_members as $member): ?>
                        <tr>
                            <td><?= $member['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($member['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($member['title'] ?? '') ?></td>
                            <td><?= htmlspecialchars($member['department'] ?? '') ?></td>
                            <td><?= htmlspecialchars($member['email'] ?? '') ?></td>
                            <td>
                                <span class="badge bg-<?= ($member['status'] ?? '') === 'active' ? 'success' : 'secondary' ?>">
                                    <?= $member['status'] ?? 'inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= route('admin/team/edit/' . $member['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= route('admin/team/delete/' . $member['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this team member?', 'true', this); return false;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No team members found. <a href="<?= route('admin/team/create') ?>">Add one</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
