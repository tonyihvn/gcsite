
<div class="container py-5">
    <h1 class="mb-4">About GINTEC Solutions</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Who We Are</h5>
                    <p>
                        GINTEC Solutions Consults Ltd is a leading provider of innovative IT solutions and consultancy services. 
                        Based in Abuja, Nigeria, we are committed to helping businesses transform through technology.
                    </p>
                    <p>
                        Our team of experienced professionals delivers comprehensive solutions across various sectors, 
                        from enterprise resource planning systems to artificial intelligence integration.
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Our Mission</h5>
                    <p>
                        To empower organizations with cutting-edge technology solutions that drive growth, efficiency, 
                        and innovation while maintaining the highest standards of quality and customer service.
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Our Values</h5>
                    <ul>
                        <li><strong>Innovation:</strong> We stay ahead of technology trends</li>
                        <li><strong>Excellence:</strong> Quality is never compromised</li>
                        <li><strong>Integrity:</strong> We build trust through transparency</li>
                        <li><strong>Partnership:</strong> Your success is our success</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title">Company Information</h5>
                    <dl class="row">
                        <dt class="col-sm-5">Name:</dt>
                        <dd class="col-sm-7"><?= config('company.name') ?></dd>
                        
                        <dt class="col-sm-5">CEO:</dt>
                        <dd class="col-sm-7"><?= config('company.ceo') ?></dd>
                        
                        <dt class="col-sm-5">Location:</dt>
                        <dd class="col-sm-7">Abuja, Nigeria</dd>
                        
                        <dt class="col-sm-5">Address:</dt>
                        <dd class="col-sm-7"><?= config('company.address') ?></dd>
                    </dl>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="<?= route('contact') ?>" class="btn btn-primary">Get in Touch</a>
                        <a href="<?= route('products') ?>" class="btn btn-outline-primary">View Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
