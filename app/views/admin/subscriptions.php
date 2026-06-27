
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Product</th>
                <th>Plan</th>
                <th>Price</th>
                <th>Billing Cycle</th>
                <th>Status</th>
                <th>Renewal Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscriptions as $sub): ?>
            <?php 
                // Get user information
                $user = null;
                if (!empty($sub['user_id'])) {
                    try {
                        $userModel = new \App\Models\User();
                        $user = $userModel->find($sub['user_id']);
                    } catch (Exception $e) {
                        $user = null;
                    }
                }
                $userName = ($user && !empty($user['first_name'])) ? htmlspecialchars($user['first_name'] . ' ' . ($user['last_name'] ?? '')) : 'Unknown User';
            ?>
            <tr>
                <td><?= $userName ?></td>
                <td><?= htmlspecialchars($sub['product_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($sub['plan_name'] ?? '-') ?></td>
                <td>₦<?= number_format($sub['plan_price'] ?? 0, 2) ?></td>
                <td><?= ucfirst($sub['billing_cycle']) ?></td>
                <td><span class="badge bg-<?= $sub['status'] === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($sub['status']) ?></span></td>
                <td><?= $sub['renewal_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

