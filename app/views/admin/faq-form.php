<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($faq) ? 'Edit FAQ' : 'Create New FAQ' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/faqs' . (isset($faq) ? '/' . $faq['id'] : '')) ?>" class="row g-3">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Question *</label>
                <input type="text" class="form-control" name="question" value="<?= $faq['question'] ?? '' ?>" placeholder="Enter the FAQ question" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="category" value="<?= $faq['category'] ?? '' ?>" placeholder="e.g., General, Technical, Billing">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Sort Order</label>
                <input type="number" class="form-control" name="sort_order" value="<?= $faq['sort_order'] ?? 0 ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" <?= ($faq['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($faq['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="col-12">
                <label class="form-label">Answer *</label>
                <textarea class="form-control wyswyg" name="answer" rows="8" placeholder="Detailed answer to the question" required><?= $faq['answer'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($faq) ? 'Update FAQ' : 'Create FAQ' ?>
                    </button>
                    <a href="<?= route('admin/faqs') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
