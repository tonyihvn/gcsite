<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Subscribe to <?= htmlspecialchars($product['name']) ?></h5>
                </div>
                <div class="card-body">
                    <?php if ($existing_subscription): ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle"></i>
                            You already have an active subscription to this product. 
                            <a href="<?= route('dashboard/subscriptions') ?>">View your subscriptions</a>
                        </div>
                    <?php else: ?>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Product Details</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Product:</dt>
                                    <dd class="col-sm-6"><?= htmlspecialchars($product['name']) ?></dd>
                                    
                                    <dt class="col-sm-6">Category:</dt>
                                    <dd class="col-sm-6"><?= htmlspecialchars($product['category'] ?? 'N/A') ?></dd>
                                    
                                    <dt class="col-sm-6">Price Model:</dt>
                                    <dd class="col-sm-6"><?= ucfirst($product['pricing_model']) ?></dd>
                                    
                                    <?php if (!empty($product['base_price'])): ?>
                                    <dt class="col-sm-6">Base Price:</dt>
                                    <dd class="col-sm-6"><strong class="text-primary">₦<?= number_format($product['base_price'], 2) ?></strong></dd>
                                    <?php endif; ?>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6>Your Details</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Name:</dt>
                                    <dd class="col-sm-6"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></dd>
                                    
                                    <dt class="col-sm-6">Email:</dt>
                                    <dd class="col-sm-6"><?= htmlspecialchars($user['email']) ?></dd>
                                    
                                    <dt class="col-sm-6">Phone:</dt>
                                    <dd class="col-sm-6"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <form method="POST" action="<?= route('subscribe/' . $product['id']) ?>" class="row g-3">
                            <?= csrf_field() ?>
                            
                            <div class="col-md-6">
                                <label class="form-label">Plan Name *</label>
                                <select class="form-control" name="plan_name" required>
                                    <option value="">-- Select a plan --</option>
                                    <option value="Basic">Basic Plan</option>
                                    <option value="Professional">Professional Plan</option>
                                    <option value="Enterprise">Enterprise Plan</option>
                                    <option value="Custom">Custom Plan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Billing Cycle *</label>
                                <select class="form-control" name="billing_cycle" required onchange="updateRenewalDate()">
                                    <option value="">-- Select billing cycle --</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly (3 months)</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="one_time">One Time</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Subscription Price (₦)</label>
                                <input type="number" class="form-control" name="plan_price" step="0.01" min="0" value="<?= $product['base_price'] ?? 0 ?>" placeholder="₦0.00">
                                <small class="text-muted">Leave empty to use product base price</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Expected Renewal</label>
                                <input type="text" class="form-control" id="renewalDate" readonly>
                                <small class="text-muted">Auto-calculated based on billing cycle</small>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="auto_renew" value="1" id="autoRenew" checked>
                                    <label class="form-check-label" for="autoRenew">
                                        Enable auto-renewal (subscription will automatically renew when it expires)
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong><i class="fas fa-lightbulb"></i> Note:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Your subscription will be active immediately upon confirmation</li>
                                        <li>You can manage your subscription from your dashboard</li>
                                        <li>Auto-renewal can be disabled anytime from your subscription settings</li>
                                        <li>An invoice will be generated for your records</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?= route('products/' . $product['slug']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Product
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check"></i> Confirm Subscription
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateRenewalDate() {
        const billingCycle = document.querySelector('select[name="billing_cycle"]').value;
        const renewalDateInput = document.getElementById('renewalDate');
        
        const today = new Date();
        let renewalDate = new Date(today);
        
        switch(billingCycle) {
            case 'monthly':
                renewalDate.setMonth(renewalDate.getMonth() + 1);
                break;
            case 'quarterly':
                renewalDate.setMonth(renewalDate.getMonth() + 3);
                break;
            case 'yearly':
                renewalDate.setFullYear(renewalDate.getFullYear() + 1);
                break;
            case 'one_time':
                renewalDateInput.value = 'No renewal (one-time payment)';
                return;
            default:
                renewalDate = null;
        }
        
        if (renewalDate) {
            renewalDateInput.value = renewalDate.toLocaleDateString('en-NG', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        } else {
            renewalDateInput.value = '';
        }
    }

    // Initialize renewal date on page load
    window.addEventListener('DOMContentLoaded', function() {
        updateRenewalDate();
    });
</script>
