

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 150px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-section h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-section p {
    font-size: 1.3rem;
    margin-bottom: 40px;
    opacity: 0.9;
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
    font-size: 2rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
}

.products-section {
    padding: 100px 0;
}

.products-section h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 50px;
    text-align: center;
    font-weight: 600;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.product-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    color: white;
}

.product-content {
    padding: 30px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-name {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.product-category {
    font-size: 0.85rem;
    color: #667eea;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.product-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
}

.product-price {
    color: #667eea;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.product-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #667eea;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    align-self: flex-start;
}

.product-btn:hover {
    background: #764ba2;
    color: white;
    text-decoration: none;
}

.services-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.services-section h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 50px;
    text-align: center;
    font-weight: 600;
}

.service-card {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-top: 4px solid #667eea;
    margin-bottom: 30px;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.service-icon {
    font-size: 3rem;
    margin-bottom: 20px;
}

.service-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.service-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.service-details {
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.service-btn {
    display: inline-block;
    padding: 8px 15px;
    background: #667eea;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.service-btn:hover {
    background: #764ba2;
    color: white;
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.cta-section h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.cta-section p {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.9;
}

.why-section {
    padding: 100px 0;
}

.why-section h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 50px;
    text-align: center;
    font-weight: 600;
}

.why-card {
    padding: 30px;
    background: #f8f9fa;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 30px;
    border-left: 4px solid #667eea;
}

.why-icon {
    font-size: 2.5rem;
    color: #667eea;
    margin-bottom: 20px;
}

.why-card h4 {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 15px;
    font-weight: 600;
}

.why-card p {
    color: #666;
    line-height: 1.6;
}
</style>

<!-- Carousel Section -->
<?php if (!empty($slides)): ?>
<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($slides as $index => $slide): ?>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" style="min-height: 500px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden;">
                <!-- Background Image -->
                <?php if (!empty($slide['image_url'])): ?>
                    <img src="<?= \Core\FileUploader::getImageUrl($slide['image_url']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; z-index: 0;">
                <?php endif; ?>
                
                <!-- Dark Overlay -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.3); z-index: 1;"></div>
                
                <!-- Content -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; z-index: 2; padding: 40px 20px;">
                    <div style="text-align: center; color: white; max-width: 800px;">
                        <?php if (!empty($slide['title'])): ?>
                            <h2 class="carousel-title" style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; margin-bottom: 20px; line-height: 1.2; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"><?= htmlspecialchars($slide['title']) ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($slide['description'])): ?>
                            <p class="carousel-description" style="font-size: clamp(1rem, 2vw, 1.3rem); margin-bottom: 30px; opacity: 0.95; line-height: 1.6; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"><?= htmlspecialchars($slide['description']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($slide['button_text']) && !empty($slide['link_url'])): ?>
                            <div style="margin-top: 30px;">
                                <a href="<?= htmlspecialchars($slide['link_url']) ?>" class="btn btn-light btn-lg" style="font-weight: 600; padding: 12px 40px;"><?= htmlspecialchars($slide['button_text']) ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Carousel Responsive CSS -->
<style>
    @media (max-width: 768px) {
        .carousel-item {
            min-height: 350px !important;
        }
        .carousel-title {
            font-size: 1.8rem !important;
        }
        .carousel-description {
            font-size: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .carousel-item {
            min-height: 300px !important;
        }
        .carousel-title {
            font-size: 1.5rem !important;
        }
        .carousel-description {
            font-size: 0.95rem !important;
            margin-bottom: 20px !important;
        }
        .btn-lg {
            padding: 10px 25px !important;
            font-size: 0.95rem !important;
        }
    }
    
    #homeCarousel .carousel-item {
        transition: opacity 0.6s ease-in-out;
    }
</style>
<?php endif; ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1>Welcome to GINTEC Solutions</h1>
        <p>Leading Provider of Innovative IT Solutions & Digital Transformation</p>
        <a href="<?= route('auth/register') ?>" class="btn btn-light btn-lg">Get Started</a>
        <a href="<?= route('contact') ?>" class="btn btn-outline-light btn-lg ms-2">Schedule Demo</a>
    </div>
</div>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Satisfied Clients</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Expert Team</span>
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
                    <span class="stat-label">Projects Delivered</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if (!empty($featured_products)): ?>
<section class="products-section">
    <div class="container">
        <h2>Our Products</h2>
        <div class="row">
            <?php foreach (array_slice($featured_products, 0, 6) as $product): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-card">
                    <div class="product-image">
                        <?php echo strtoupper(substr($product['name'], 0, 1)); ?>
                    </div>
                    <div class="product-content">
                        <div class="product-category"><?= htmlspecialchars($product['category'] ?? 'Software') ?></div>
                        <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                        <p class="product-description">
                            <?= htmlspecialchars(substr($product['description'] ?? 'High-quality business solution', 0, 120)) ?>...
                        </p>
                        <div class="product-price">₦<?= number_format($product['base_price'] ?? 0, 2) ?></div>
                        <a href="<?= route('products/' . $product['slug']) ?>" class="product-btn">Learn More</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= route('products') ?>" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<?php if (!empty($services)): ?>
<section class="services-section">
    <div class="container">
        <h2>Our Services</h2>
        <div class="row">
            <?php foreach (array_slice($services, 0, 6) as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">📊</div>
                    <div class="service-name"><?= htmlspecialchars($service['name']) ?></div>
                    <p class="service-description">
                        <?= htmlspecialchars(substr($service['description'] ?? $service['detailed_content'] ?? '', 0, 100)) ?>...
                    </p>
                    <?php if (!empty($service['delivery_days'])): ?>
                    <div class="service-details">⏱️ Delivery: <?= htmlspecialchars($service['delivery_days']) ?> days</div>
                    <?php endif; ?>
                    <a href="<?= route('services/' . $service['slug']) ?>" class="service-btn">Learn More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= route('services') ?>" class="btn btn-primary btn-lg">View All Services</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us Section -->
<section class="why-section">
    <div class="container">
        <h2>Why Choose GINTEC Solutions?</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">🎯</div>
                    <h4>Expert Expertise</h4>
                    <p>Over 8 years of proven experience delivering world-class IT solutions across diverse industries.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">💡</div>
                    <h4>Innovation First</h4>
                    <p>Cutting-edge technologies and continuous innovation keep us ahead in the fast-moving tech landscape.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">🤝</div>
                    <h4>Partnership Approach</h4>
                    <p>We treat every client as a partner, invested in understanding and exceeding your unique goals.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">📊</div>
                    <h4>Proven Results</h4>
                    <p>Deliver measurable ROI and tangible business impact through strategic IT investments.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">🔒</div>
                    <h4>Security & Compliance</h4>
                    <p>Industry-leading security practices and full compliance with international standards.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">⚡</div>
                    <h4>24/7 Support</h4>
                    <p>Dedicated support team available round-the-clock to ensure your systems run smoothly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Transform Your Business?</h2>
        <p>Join hundreds of companies that trust GINTEC Solutions for their IT needs</p>
        <a href="<?= route('contact') ?>" class="btn btn-light btn-lg">Contact Us Today</a>
        <a href="<?= route('about') ?>" class="btn btn-outline-light btn-lg ms-2">Learn More</a>
    </div>
</section>

