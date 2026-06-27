

<style>
.partners-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.partners-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.partners-hero p {
    font-size: 1.3rem;
    opacity: 0.9;
}

.featured-partners-section {
    padding: 80px 0;
}

.featured-partners-section h2 {
    font-size: 2.5rem;
    color: #333;
    text-align: center;
    margin-bottom: 50px;
    font-weight: 600;
}

.featured-partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-bottom: 80px;
}

.partner-card {
    background: white;
    border-radius: 10px;
    padding: 40px 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-top: 4px solid #667eea;
}

.partner-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.partner-logo {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 48px;
    color: white;
}

.partner-card h3 {
    font-size: 1.4rem;
    color: #333;
    margin-bottom: 10px;
    font-weight: 600;
}

.partner-category {
    font-size: 0.9rem;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 15px;
}

.partner-card p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.partner-links {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.partner-links a {
    display: inline-block;
    padding: 8px 15px;
    background: #f0f0f0;
    color: #667eea;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.partner-links a:hover {
    background: #667eea;
    color: white;
}

.all-partners-section {
    padding: 80px 0;
    background: #f8f9fa;
    border-radius: 10px;
}

.all-partners-section h2 {
    font-size: 2.5rem;
    color: #333;
    text-align: center;
    margin-bottom: 50px;
    font-weight: 600;
}

.partners-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.partner-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.partner-item:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.partner-item h4 {
    color: #333;
    margin-bottom: 8px;
    font-weight: 600;
}

.partner-item .badge {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    display: inline-block;
}
</style>

<div class="partners-hero">
    <div class="container">
        <h1>Our Partners</h1>
        <p>Strategic partnerships that drive innovation and success</p>
    </div>
</div>

<div class="container">
    <?php if (!empty($featured_partners)): ?>
        <section class="featured-partners-section">
            <h2>Featured Partners</h2>
            <div class="featured-partners-grid">
                <?php foreach ($featured_partners as $partner): ?>
                    <div class="partner-card">
                        <div class="partner-logo">
                            <?php echo strtoupper(substr($partner['name'], 0, 1)); ?>
                        </div>
                        <h3><?= htmlspecialchars($partner['name']) ?></h3>
                        <div class="partner-category"><?= htmlspecialchars($partner['category']) ?></div>
                        <p><?= htmlspecialchars($partner['description']) ?></p>
                        <?php if (!empty($partner['website'])): ?>
                            <div class="partner-links">
                                <a href="<?= htmlspecialchars($partner['website']) ?>" target="_blank">Visit Website</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($partners)): ?>
        <section class="all-partners-section">
            <h2>All Partners</h2>
            <div class="partners-list">
                <?php foreach ($partners as $partner): ?>
                    <div class="partner-item">
                        <h4><?= htmlspecialchars($partner['name']) ?></h4>
                        <span class="badge"><?= htmlspecialchars($partner['category']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section style="padding: 80px 0; text-align: center;">
        <h2 style="font-size: 2.5rem; color: #333; margin-bottom: 30px;">Become a Partner</h2>
        <p style="font-size: 1.1rem; color: #666; max-width: 600px; margin: 0 auto 30px; line-height: 1.8;">
            We're always looking for innovative partners to expand our ecosystem. If you're interested in partnering with GINTEC Solutions, 
            let's discuss how we can create mutual value.
        </p>
        <a href="<?= route('contact') ?>" class="btn btn-primary btn-lg">Get In Touch</a>
    </section>
</div>
