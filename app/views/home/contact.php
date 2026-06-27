
<?php use App\Models\Setting; ?>
<div class="container py-5">
    <h1 class="mb-4">Contact Us</h1>
    <p class="lead text-muted mb-4">Have questions? We'd love to hear from you. Get in touch with us today.</p>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Send us a Message</h5>
                    <form method="POST" action="<?= route('contact') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= is_auth() ? auth()['first_name'] . ' ' . auth()['last_name'] : old('name') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= is_auth() ? auth()['email'] : old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= is_auth() ? auth()['phone'] : old('phone') ?>" placeholder="+234...">
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?= old('message') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Contact Information</h5>
                    <div class="mb-3">
                        <h6><i class="fas fa-map-marker-alt"></i> Address</h6>
                        <p class="text-muted"><?= Setting::get('company_address', 'Not available') ?></p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-phone"></i> Phone</h6>
                        <p class="text-muted">
                            <a href="tel:<?= Setting::get('company_phone', '') ?>">
                                <?= Setting::get('company_phone', 'Not available') ?>
                            </a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-envelope"></i> Email</h6>
                        <p class="text-muted">
                            <a href="mailto:<?= Setting::get('company_email', '') ?>">
                                <?= Setting::get('company_email', 'Not available') ?>
                            </a>
                        </p>
                    </div>
                    <div>
                        <h6><i class="fas fa-globe"></i> Website</h6>
                        <p class="text-muted"><?= Setting::get('company_website', config('app.url')) ?></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Business Hours</h5>
                    <ul class="list-unstyled">
                        <li><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</li>
                        <li><strong>Saturday:</strong> 10:00 AM - 3:00 PM</li>
                        <li><strong>Sunday:</strong> Closed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

