

<style>
.about-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.about-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.about-hero p {
    font-size: 1.3rem;
    max-width: 700px;
    margin: 0 auto;
    opacity: 0.9;
}

.about-section {
    padding: 80px 0;
    border-bottom: 1px solid #e0e0e0;
}

.about-section:last-child {
    border-bottom: none;
}

.about-section h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 30px;
    font-weight: 600;
}

.about-section p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
    max-width: 800px;
}

.about-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 40px;
}

.about-card {
    padding: 30px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.about-card h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 15px;
}

.about-card p {
    color: #666;
    line-height: 1.6;
}

.values-list {
    list-style: none;
    padding: 0;
    font-size: 1.1rem;
    line-height: 2;
    color: #555;
}

.values-list li:before {
    content: "✓ ";
    color: #667eea;
    font-weight: bold;
    margin-right: 10px;
}

.stats-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.stat-item {
    margin: 20px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}
</style>

<div class="about-hero">
    <div class="container">
        <h1>About GINTEC Solutions</h1>
        <p>Transforming businesses through innovative IT solutions since 2015</p>
    </div>
</div>

<div class="container">
    <?php foreach ($about_sections as $section): ?>
        <section class="about-section">
            <div class="row align-items-center">
                <?php if (!empty($section['image']) || !empty($section['image_url'])): ?>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <img src="<?= \Core\FileUploader::getImageUrl($section['image'] ?? $section['image_url']) ?>" alt="<?= htmlspecialchars($section['title']) ?>" class="img-fluid rounded" style="max-width: 100%;">
                    </div>
                    <div class="col-md-6">
                <?php else: ?>
                    <div class="col-12">
                <?php endif; ?>
                        <h2><?= htmlspecialchars($section['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($section['content'])) ?></p>
                    </div>
            </div>
        </section>
    <?php endforeach; ?>

    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Clients Served</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Team Members</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">8+</span>
                        <span class="stat-label">Years Experience</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Projects Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section">
        <h2>Why Choose GINTEC Solutions?</h2>
        <div class="about-grid">
            <div class="about-card">
                <h3>🎯 Expertise</h3>
                <p>Over 8 years of experience delivering world-class IT solutions to businesses across various industries.</p>
            </div>
            <div class="about-card">
                <h3>💡 Innovation</h3>
                <p>We stay ahead of the curve with cutting-edge technologies and continuous innovation in all our services.</p>
            </div>
            <div class="about-card">
                <h3>🤝 Partnership</h3>
                <p>We treat every client as a partner, committed to understanding and exceeding their expectations.</p>
            </div>
            <div class="about-card">
                <h3>📊 Results</h3>
                <p>Proven track record of delivering measurable results and ROI for our clients' IT investments.</p>
            </div>
        </div>
    </section>
</div>
