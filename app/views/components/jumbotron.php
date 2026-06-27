<?php
/**
 * Jumbotron Component
 * GINTEC Solutions
 * 
 * Usage:
 * include __DIR__ . '/../../components/jumbotron.php';
 * renderJumbotron($title, $subtitle, $backgroundImage, $backgroundColor);
 */

function renderJumbotron($title, $subtitle = '', $backgroundImage = '', $backgroundColor = '#f8f9fa') {
    $bgStyle = '';
    
    if (!empty($backgroundImage)) {
        $imagePath = $backgroundImage;
        $bgStyle = "background-image: url('{$imagePath}'); background-size: cover; background-position: center;";
    } else {
        $bgStyle = "background-color: {$backgroundColor};";
    }
    ?>
    <section class="jumbotron-section" style="<?php echo $bgStyle; ?>">
        <div class="jumbotron-overlay"></div>
        <div class="container jumbotron-content">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="jumbotron-title">
                        <?php echo htmlspecialchars($title); ?>
                    </h1>
                    <?php if (!empty($subtitle)): ?>
                        <p class="jumbotron-subtitle">
                            <?php echo htmlspecialchars($subtitle); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <style>
        .jumbotron-section {
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .jumbotron-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .jumbotron-content {
            position: relative;
            z-index: 2;
        }

        .jumbotron-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .jumbotron-subtitle {
            font-size: 1.25rem;
            color: #f0f0f0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.6;
        }

        .min-vh-50 {
            min-height: 50vh;
        }

        @media (max-width: 768px) {
            .jumbotron-section {
                min-height: 250px;
                margin-bottom: 2rem;
            }

            .jumbotron-title {
                font-size: 2.5rem;
            }

            .jumbotron-subtitle {
                font-size: 1rem;
            }
        }
    </style>
<?php
}
?>
