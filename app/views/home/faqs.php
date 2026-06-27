
<div class="container py-5">
    <h1 class="mb-4">FAQs</h1>
    <p class="lead text-muted mb-4">Find answers to common questions about our products and services.</p>

    <div class="row">
        <div class="col-md-8">
            <div id="accordion">
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $index => $faq): ?>
                    <div class="card mb-2">
                        <div class="card-header" id="heading<?= $index ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                    <?= htmlspecialchars($faq['question']) ?>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse<?= $index ?>" class="collapse" data-bs-parent="#accordion">
                            <div class="card-body">
                                <?= $faq['answer'] ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="alert alert-info">
                    No FAQs available yet.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Still have questions?</h5>
                    <p class="text-muted">Can't find the answer you're looking for? Please contact our support team.</p>
                    <a href="<?= route('contact') ?>" class="btn btn-primary w-100">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
