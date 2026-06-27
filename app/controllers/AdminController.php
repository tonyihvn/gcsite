<?php
/**
 * Admin Dashboard Controller
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\Page;
use App\Models\Slide;
use App\Models\Feedback;
use App\Models\Setting;
use App\Models\Faq;
use App\Models\BlogPost;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\TeamMember;
use App\Models\Partner;
use App\Models\About;
use App\Models\ThemeSetting;
use App\Models\Menu;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::checkAdmin();
    }

    /**
     * Handle file upload for images
     */
    protected function handleImageUpload($fileInputName, $urlInputName, $currentPath = '', $subdir = 'images')
    {
        $uploader = new \Core\FileUploader();

        try {
            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadedPath = $uploader->upload($fileInputName, $subdir);
                if ($uploadedPath) {
                    return $uploadedPath;
                }
            }
        } catch (\Exception $e) {
            error_log('Upload error: ' . $e->getMessage());
        }

        // Fallback to URL input if provided
        if (!empty($_POST[$urlInputName])) {
            return $_POST[$urlInputName];
        }

        // Return existing image if no new upload
        return $currentPath;
    }

    protected function handleFileUpload($fileInputName, $subdir = 'files', $currentPath = '')
    {
        try {
            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES[$fileInputName];
                $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $allowed_exts = ['pdf', 'doc', 'docx'];
                
                // Check file type
                if (!in_array($file['type'], $allowed_types)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    if (!in_array(strtolower($ext), $allowed_exts)) {
                        error_log('Invalid file type: ' . $file['type']);
                        return $currentPath;
                    }
                }
                
                // Create uploads directory if it doesn't exist
                $upload_dir = __DIR__ . '/../../public/assets/uploads/' . $subdir;
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
                $destination = $upload_dir . '/' . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return 'uploads/' . $subdir . '/' . $filename;
                }
            }
        } catch (\Exception $e) {
            error_log('File upload error: ' . $e->getMessage());
        }
        
        // Return existing file if no new upload
        return $currentPath;
    }

    // ===== DASHBOARD =====
    public function dashboard()
    {
        $data = [
            'page_title' => 'Admin Dashboard',
            'total_users' => (new User())->count(),
            'total_products' => (new Product())->count(),
            'total_services' => (new Service())->count(),
            'active_subscriptions' => count((new Subscription())->where('status', 'active')),
            'pending_feedbacks' => count((new Feedback())->where('status', 'new')),
        ];

        $this->view('admin.dashboard', $data);
    }

    // ===== SETTINGS =====
    public function settings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateSettings();
            return;
        }

        $data = [
            'page_title' => 'Site Settings',
        ];

        $this->view('admin.settings', $data);
    }

    public function updateSettings()
    {
        // Handle file uploads
        $siteLogo = $this->handleImageUpload('site_logo', 'site_logo', '', 'site');
        $siteFavicon = $this->handleImageUpload('site_favicon', 'site_favicon', '', 'site');
        $pageBanner = $this->handleImageUpload('page_banner', 'page_banner', '', 'site');

        // Update text settings
        Setting::set('company_name', $_POST['company_name'] ?? '');
        Setting::set('company_email', $_POST['company_email'] ?? '');
        Setting::set('company_phone', $_POST['company_phone'] ?? '');
        Setting::set('company_address', $_POST['company_address'] ?? '');
        Setting::set('company_website', $_POST['company_website'] ?? '');
        Setting::set('company_ceo', $_POST['company_ceo'] ?? '');
        Setting::set('company_description', $_POST['company_description'] ?? '');

        // Update file settings if new files were uploaded
        if (!empty($siteLogo)) {
            Setting::set('site_logo', $siteLogo);
        }
        if (!empty($siteFavicon)) {
            Setting::set('site_favicon', $siteFavicon);
        }
        if (!empty($pageBanner)) {
            Setting::set('page_banner', $pageBanner);
        }

        set_flash('success', 'Settings updated successfully');
        $this->redirect('admin/settings');
    }

    // ===== PAGES MANAGEMENT =====
    public function pages()
    {
        $pages = (new Page())->all();

        $data = [
            'pages' => $pages,
            'page_title' => 'Pages',
        ];

        $this->view('admin.pages', $data);
    }

    public function createPageForm()
    {
        $allPages = (new Page())->all();
        $this->view('admin.page-form', [
            'page_title' => 'Create Page',
            'all_pages' => $allPages,
        ]);
    }

    public function createPage()
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/pages/create');
        }

        $featuredImage = $this->handleImageUpload('featured_image_file', 'featured_image', '', 'pages');
        $headerBgImage = $this->handleImageUpload('header_bg_image_file', 'header_bg_image', '', 'pages');

        (new Page())->create([
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'content' => $_POST['content'] ?? '',
            'featured_image' => $featuredImage,
            'meta_title' => $_POST['meta_title'] ?? $_POST['title'],
            'meta_description' => $_POST['meta_description'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'visibility' => $_POST['visibility'] ?? 'public',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'menu_order' => (int)($_POST['menu_order'] ?? 0),
            'page_header' => $_POST['page_header'] ?? '',
            'page_subheader' => $_POST['page_subheader'] ?? '',
            'header_bg_image' => $headerBgImage,
            'header_bg_color' => $_POST['header_bg_color'] ?? '#f8f9fa',
        ]);

        set_flash('success', 'Page created successfully');
        $this->redirect('admin/pages');
    }

    public function editPageForm($id)
    {
        $page = (new Page())->find($id);

        if (!$page) {
            http_response_code(404);
            die('Page not found');
        }

        $allPages = (new Page())->all();

        $this->view('admin.page-form', [
            'page' => $page,
            'all_pages' => $allPages,
            'page_title' => 'Edit: ' . $page['title'],
        ]);
    }

    public function updatePage($id)
    {
        $page = (new Page())->find($id);

        if (!$page) {
            http_response_code(404);
            die('Page not found');
        }

        $featuredImage = $this->handleImageUpload('featured_image_file', 'featured_image', $page['featured_image'], 'pages');
        $headerBgImage = $this->handleImageUpload('header_bg_image_file', 'header_bg_image', $page['header_bg_image'] ?? '', 'pages');

        (new Page())->update($id, [
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'content' => $_POST['content'] ?? '',
            'featured_image' => $featuredImage,
            'meta_title' => $_POST['meta_title'] ?? $_POST['title'],
            'meta_description' => $_POST['meta_description'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'menu_order' => (int)($_POST['menu_order'] ?? 0),
            'page_header' => $_POST['page_header'] ?? '',
            'page_subheader' => $_POST['page_subheader'] ?? '',
            'header_bg_image' => $headerBgImage,
            'header_bg_color' => $_POST['header_bg_color'] ?? '#f8f9fa',
        ]);

        set_flash('success', 'Page updated successfully');
        $this->redirect('admin/pages');
    }

    public function deletePage($id)
    {
        (new Page())->delete($id);
        set_flash('success', 'Page deleted successfully');
        $this->redirect('admin/pages');
    }

    // ===== PRODUCTS MANAGEMENT =====
    public function products()
    {
        $products = (new Product())->all();
        $data = [
            'products' => $products,
            'page_title' => 'Products',
        ];
        $this->view('admin.products', $data);
    }

    public function createProductForm()
    {
        $this->view('admin.product-form', ['page_title' => 'Create Product']);
    }

    public function createProduct()
    {
        $rules = ['name' => 'required', 'slug' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/products/create');
        }

        $image = $this->handleImageUpload('image_file', 'image_url', '', 'products');
        $brochure = $this->handleFileUpload('brochure_file', 'products');
        $proposal = $this->handleFileUpload('proposal_file', 'products');

        (new Product())->create([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'features' => $_POST['features'] ?? '',
            'pricing_model' => $_POST['pricing_model'] ?? 'fixed',
            'base_price' => $_POST['base_price'] ?? 0,
            'category' => $_POST['category'] ?? '',
            'image_url' => $image,
            'icon' => $_POST['icon'] ?? '',
            'demo_url' => $_POST['demo_url'] ?? '',
            'documentation_url' => $_POST['documentation_url'] ?? '',
            'website' => $_POST['website'] ?? '',
            'brochure_url' => $brochure,
            'proposal_url' => $proposal,
            'status' => $_POST['status'] ?? 'draft',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'Product created successfully');
        $this->redirect('admin/products');
    }

    public function editProductForm($id)
    {
        $product = (new Product())->find($id);
        if (!$product) {
            set_flash('error', 'Product not found');
            $this->redirect('admin/products');
            return;
        }
        $this->view('admin.product-form', [
            'product' => $product,
            'page_title' => 'Edit Product',
        ]);
    }

    public function updateProduct($id)
    {
        $product = (new Product())->find($id);
        if (!$product) {
            set_flash('error', 'Product not found');
            $this->redirect('admin/products');
            return;
        }

        $image = $this->handleImageUpload('image_file', 'image_url', $product['image_url'], 'products');
        $brochure = $this->handleFileUpload('brochure_file', 'products', $product['brochure_url'] ?? '');
        $proposal = $this->handleFileUpload('proposal_file', 'products', $product['proposal_url'] ?? '');

        (new Product())->update($id, [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'features' => $_POST['features'] ?? '',
            'pricing_model' => $_POST['pricing_model'] ?? 'fixed',
            'base_price' => $_POST['base_price'] ?? 0,
            'category' => $_POST['category'] ?? '',
            'image_url' => $image,
            'icon' => $_POST['icon'] ?? '',
            'demo_url' => $_POST['demo_url'] ?? '',
            'documentation_url' => $_POST['documentation_url'] ?? '',
            'website' => $_POST['website'] ?? '',
            'brochure_url' => $brochure,
            'proposal_url' => $proposal,
            'status' => $_POST['status'] ?? 'draft',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'Product updated successfully');
        $this->redirect('admin/products');
    }

    public function deleteProduct($id)
    {
        (new Product())->delete($id);
        set_flash('success', 'Product deleted successfully');
        $this->redirect('admin/products');
    }

    // ===== SERVICES MANAGEMENT =====
    public function services()
    {
        $services = (new Service())->all();
        $data = [
            'services' => $services,
            'page_title' => 'Services',
        ];
        $this->view('admin.services', $data);
    }

    public function createServiceForm()
    {
        $this->view('admin.service-form', ['page_title' => 'Create Service']);
    }

    public function createService()
    {
        $rules = ['name' => 'required', 'slug' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/services/create');
        }

        $image = $this->handleImageUpload('image_file', 'image_url', '', 'services');
        $brochure = $this->handleFileUpload('brochure_file', 'services');
        $proposal = $this->handleFileUpload('proposal_file', 'services');

        (new Service())->create([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'detailed_content' => $_POST['detailed_content'] ?? '',
            'icon' => $_POST['icon'] ?? '',
            'image_url' => $image,
            'website' => $_POST['website'] ?? '',
            'base_price' => $_POST['base_price'] ?? 0,
            'delivery_days' => (int)($_POST['delivery_days'] ?? 0),
            'brochure_url' => $brochure,
            'proposal_url' => $proposal,
            'status' => $_POST['status'] ?? 'draft',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'Service created successfully');
        $this->redirect('admin/services');
    }

    public function editServiceForm($id)
    {
        $service = (new Service())->find($id);
        if (!$service) {
            set_flash('error', 'Service not found');
            $this->redirect('admin/services');
            return;
        }
        $this->view('admin.service-form', [
            'service' => $service,
            'page_title' => 'Edit Service',
        ]);
    }

    public function updateService($id)
    {
        $service = (new Service())->find($id);
        if (!$service) {
            set_flash('error', 'Service not found');
            $this->redirect('admin/services');
            return;
        }

        $image = $this->handleImageUpload('image_file', 'image_url', $service['image_url'], 'services');
        $brochure = $this->handleFileUpload('brochure_file', 'services', $service['brochure_url'] ?? '');
        $proposal = $this->handleFileUpload('proposal_file', 'services', $service['proposal_url'] ?? '');

        (new Service())->update($id, [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'] ?? '',
            'detailed_content' => $_POST['detailed_content'] ?? '',
            'icon' => $_POST['icon'] ?? '',
            'image_url' => $image,
            'website' => $_POST['website'] ?? '',
            'base_price' => $_POST['base_price'] ?? 0,
            'delivery_days' => (int)($_POST['delivery_days'] ?? 0),
            'brochure_url' => $brochure,
            'proposal_url' => $proposal,
            'status' => $_POST['status'] ?? 'draft',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'Service updated successfully');
        $this->redirect('admin/services');
    }

    public function deleteService($id)
    {
        (new Service())->delete($id);
        set_flash('success', 'Service deleted successfully');
        $this->redirect('admin/services');
    }

    // ===== SLIDES MANAGEMENT =====
    public function slides()
    {
        $slides = (new Slide())->all();
        $data = [
            'slides' => $slides,
            'page_title' => 'Slides',
        ];
        $this->view('admin.slides', $data);
    }

    public function createSlideForm()
    {
        $this->view('admin.slide-form', ['page_title' => 'Create Slide']);
    }

    public function createSlide()
    {
        $rules = ['title' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/slides/create');
        }

        $image = $this->handleImageUpload('image_file', 'image_url', '', 'slides');

        (new Slide())->create([
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'image_url' => $image,
            'link_url' => $_POST['link_url'] ?? '',
            'button_text' => $_POST['button_text'] ?? 'Learn More',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        set_flash('success', 'Slide created successfully');
        $this->redirect('admin/slides');
    }

    public function editSlideForm($id)
    {
        $slide = (new Slide())->find($id);
        if (!$slide) {
            set_flash('error', 'Slide not found');
            $this->redirect('admin/slides');
            return;
        }
        $this->view('admin.slide-form', [
            'slide' => $slide,
            'page_title' => 'Edit Slide',
        ]);
    }

    public function updateSlide($id)
    {
        $slide = (new Slide())->find($id);
        if (!$slide) {
            set_flash('error', 'Slide not found');
            $this->redirect('admin/slides');
            return;
        }

        $image = $this->handleImageUpload('image_file', 'image_url', $slide['image_url'], 'slides');

        (new Slide())->update($id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'image_url' => $image,
            'link_url' => $_POST['link_url'] ?? '',
            'button_text' => $_POST['button_text'] ?? 'Learn More',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        set_flash('success', 'Slide updated successfully');
        $this->redirect('admin/slides');
    }

    public function deleteSlide($id)
    {
        (new Slide())->delete($id);
        set_flash('success', 'Slide deleted successfully');
        $this->redirect('admin/slides');
    }

    // ===== FEEDBACK MANAGEMENT =====
    public function feedbacks()
    {
        $feedbacks = (new Feedback())->all();
        $data = [
            'feedbacks' => $feedbacks,
            'page_title' => 'Feedbacks',
        ];
        $this->view('admin.feedbacks', $data);
    }

    public function feedbackDetail($id)
    {
        $feedback = (new Feedback())->find($id);
        if (!$feedback) {
            set_flash('error', 'Feedback not found');
            $this->redirect('admin/feedbacks');
            return;
        }
        $data = [
            'feedback' => $feedback,
            'page_title' => 'Feedback from ' . $feedback['name'],
        ];
        $this->view('admin.feedback-detail', $data);
    }

    public function respondFeedback($id)
    {
        $feedback = (new Feedback())->find($id);
        
        if (!$feedback) {
            set_flash('error', 'Feedback not found');
            $this->redirect('admin/feedbacks');
            return;
        }
        
        $adminNotes = $_POST['admin_notes'] ?? '';
        $status = $_POST['status'] ?? 'reviewed';
        
        // Update feedback record
        (new Feedback())->update($id, [
            'status' => $status,
            'admin_notes' => $adminNotes,
            'responded_at' => date('Y-m-d H:i:s'),
        ]);

        // Send email to feedback sender with admin response
        if (!empty($feedback['email']) && !empty($adminNotes)) {
            try {
                $mailer = new \Core\Mailer();
                // Use the original feedback title/subject for the reply
                $subject = 'Re: ' . ($feedback['title'] ?? $feedback['subject'] ?? 'Your Feedback') . ' - ' . config('company.name');
                
                $emailBody = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                        .header { background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; margin: -20px -20px 20px -20px; }
                        .header h2 { margin: 0; font-size: 20px; }
                        .section { margin: 20px 0; }
                        .section h3 { color: #0369a1; border-bottom: 2px solid #0369a1; padding-bottom: 10px; }
                        .message-box { background: #f5f5f5; padding: 15px; border-left: 4px solid #0369a1; border-radius: 4px; }
                        .response-box { background: #f0f7ff; padding: 15px; border-left: 4px solid #10b981; border-radius: 4px; }
                        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #999; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>📧 Response to Your Feedback</h2>
                        </div>
                        
                        <p>Dear <strong>" . htmlspecialchars($feedback['name']) . "</strong>,</p>
                        
                        <p>Thank you for submitting your feedback to " . config('company.name') . ". We appreciate your input and have reviewed your message carefully.</p>
                        
                        <div class='section'>
                            <h3>Your Feedback</h3>
                            <div class='message-box'>
                                <p><strong>Subject:</strong> " . htmlspecialchars($feedback['title'] ?? 'No Subject') . "</p>
                                <p>" . nl2br(htmlspecialchars($feedback['message'])) . "</p>
                            </div>
                        </div>
                        
                        <div class='section'>
                            <h3>Our Response</h3>
                            <div class='response-box'>
                                <p>" . nl2br(htmlspecialchars($adminNotes)) . "</p>
                            </div>
                        </div>
                        
                        <div class='section'>
                            <p>If you have any further questions or concerns, please don't hesitate to contact us.</p>
                        </div>
                        
                        <div class='footer'>
                            <p>Best regards,<br><strong>" . config('company.name') . "</strong><br>" . config('company.address') . "<br>" . config('company.phone') . "</p>
                            <p style='margin-top: 15px; color: #ccc;'>This is an automated response. Please do not reply to this email.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                // Send the email
                $sent = $mailer->send(
                    $feedback['email'],
                    $subject,
                    $emailBody,
                    true // $isHtml = true
                );
                
                if ($sent) {
                    error_log('Feedback response email sent successfully to: ' . $feedback['email']);
                } else {
                    error_log('Failed to send feedback response email to: ' . $feedback['email']);
                }
            } catch (\Exception $e) {
                error_log('Exception while sending feedback response email: ' . $e->getMessage());
            }
        }

        set_flash('success', 'Feedback updated successfully and response sent to sender');
        $this->redirect('admin/feedbacks');
    }

    // ===== FAQ MANAGEMENT =====
    public function faqs()
    {
        $faqs = (new Faq())->all();
        $data = [
            'faqs' => $faqs,
            'page_title' => 'FAQs',
        ];
        $this->view('admin.faqs', $data);
    }

    public function createFaqForm()
    {
        $this->view('admin.faq-form', ['page_title' => 'Create FAQ']);
    }

    public function createFaq()
    {
        $rules = ['question' => 'required', 'answer' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/faqs/create');
        }

        (new Faq())->create([
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'category' => $_POST['category'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        set_flash('success', 'FAQ created successfully');
        $this->redirect('admin/faqs');
    }

    public function editFaqForm($id)
    {
        $faq = (new Faq())->find($id);
        if (!$faq) {
            set_flash('error', 'FAQ not found');
            $this->redirect('admin/faqs');
            return;
        }
        $this->view('admin.faq-form', [
            'faq' => $faq,
            'page_title' => 'Edit FAQ',
        ]);
    }

    public function updateFaq($id)
    {
        $faq = (new Faq())->find($id);
        if (!$faq) {
            set_flash('error', 'FAQ not found');
            $this->redirect('admin/faqs');
            return;
        }

        (new Faq())->update($id, [
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'category' => $_POST['category'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        set_flash('success', 'FAQ updated successfully');
        $this->redirect('admin/faqs');
    }

    public function deleteFaq($id)
    {
        (new Faq())->delete($id);
        set_flash('success', 'FAQ deleted successfully');
        $this->redirect('admin/faqs');
    }

    // ===== BLOG MANAGEMENT =====
    public function blog()
    {
        $posts = (new BlogPost())->all();
        $data = [
            'posts' => $posts,
            'page_title' => 'Blog Posts',
        ];
        $this->view('admin.blog', $data);
    }

    public function createBlogPostForm()
    {
        $this->view('admin.blog-form', ['page_title' => 'Create Blog Post']);
    }

    public function createBlogPost()
    {
        $rules = ['title' => 'required', 'slug' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/blog/create');
        }

        $image = $this->handleImageUpload('image_file', 'image_url', '', 'blog');

        (new BlogPost())->create([
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'] ?? '',
            'image_url' => $image,
            'category' => $_POST['category'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
        ]);

        set_flash('success', 'Blog post created successfully');
        $this->redirect('admin/blog');
    }

    public function editBlogPostForm($id)
    {
        $post = (new BlogPost())->find($id);
        if (!$post) {
            set_flash('error', 'Blog post not found');
            $this->redirect('admin/blog');
            return;
        }
        $this->view('admin.blog-form', [
            'post' => $post,
            'page_title' => 'Edit Blog Post',
        ]);
    }

    public function updateBlogPost($id)
    {
        $post = (new BlogPost())->find($id);
        if (!$post) {
            set_flash('error', 'Blog post not found');
            $this->redirect('admin/blog');
            return;
        }

        $image = $this->handleImageUpload('image_file', 'image_url', $post['image_url'], 'blog');

        (new BlogPost())->update($id, [
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'] ?? '',
            'image_url' => $image,
            'category' => $_POST['category'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
        ]);

        set_flash('success', 'Blog post updated successfully');
        $this->redirect('admin/blog');
    }

    public function deleteBlogPost($id)
    {
        (new BlogPost())->delete($id);
        set_flash('success', 'Blog post deleted successfully');
        $this->redirect('admin/blog');
    }

    // ===== TEAM MANAGEMENT =====
    public function team()
    {
        $team_members = (new TeamMember())->all();
        $data = [
            'team_members' => $team_members,
            'page_title' => 'Team Members',
        ];
        $this->view('admin.team', $data);
    }

    public function createTeamMemberForm()
    {
        $this->view('admin.team-form', ['page_title' => 'Add Team Member']);
    }

    public function createTeamMember()
    {
        $rules = ['name' => 'required', 'title' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/team/create');
        }

        $image = $this->handleImageUpload('team_image', 'image', '', 'team');

        (new TeamMember())->create([
            'name' => $_POST['name'],
            'title' => $_POST['title'],
            'department' => $_POST['department'] ?? '',
            'image' => $image,
            'bio' => $_POST['bio'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'linkedin_url' => $_POST['linkedin_url'] ?? '',
            'twitter_url' => $_POST['twitter_url'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ]);

        set_flash('success', 'Team member created successfully');
        $this->redirect('admin/team');
    }

    public function editTeamMemberForm($id)
    {
        $team_member = (new TeamMember())->find($id);
        if (!$team_member) {
            set_flash('error', 'Team member not found');
            $this->redirect('admin/team');
            return;
        }
        $this->view('admin.team-form', [
            'team_member' => $team_member,
            'page_title' => 'Edit Team Member',
        ]);
    }

    public function updateTeamMember($id)
    {
        $team_member = (new TeamMember())->find($id);
        if (!$team_member) {
            set_flash('error', 'Team member not found');
            $this->redirect('admin/team');
            return;
        }

        $image = $this->handleImageUpload('team_image', 'image', $team_member['image'] ?? '', 'team');

        (new TeamMember())->update($id, [
            'name' => $_POST['name'],
            'title' => $_POST['title'],
            'department' => $_POST['department'] ?? '',
            'image' => $image ?: $team_member['image'],
            'bio' => $_POST['bio'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'linkedin_url' => $_POST['linkedin_url'] ?? '',
            'twitter_url' => $_POST['twitter_url'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ]);
        set_flash('success', 'Team member updated successfully');
        $this->redirect('admin/team');
    }

    public function deleteTeamMember($id)
    {
        (new TeamMember())->delete($id);
        set_flash('success', 'Team member deleted successfully');
        $this->redirect('admin/team');
    }

    // ===== PARTNERS MANAGEMENT =====
    public function partners()
    {
        $partners = (new Partner())->all();
        $data = [
            'partners' => $partners,
            'page_title' => 'Partners',
        ];
        $this->view('admin.partners', $data);
    }

    public function createPartnerForm()
    {
        $this->view('admin.partners-form', ['page_title' => 'Add Partner']);
    }

    public function createPartner()
    {
        $rules = ['name' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/partners/create');
        }

        $logo = $this->handleImageUpload('logo_file', 'logo_url', '', 'partners');

        (new Partner())->create([
            'name' => $_POST['name'],
            'logo_url' => $logo,
            'website' => $_POST['website'] ?? '',
            'description' => $_POST['description'] ?? '',
        ]);

        set_flash('success', 'Partner created successfully');
        $this->redirect('admin/partners');
    }

    public function editPartnerForm($id)
    {
        $partner = (new Partner())->find($id);
        if (!$partner) {
            set_flash('error', 'Partner not found');
            $this->redirect('admin/partners');
            return;
        }
        $this->view('admin.partners-form', [
            'partner' => $partner,
            'page_title' => 'Edit Partner',
        ]);
    }

    public function updatePartner($id)
    {
        $partner = (new Partner())->find($id);
        if (!$partner) {
            set_flash('error', 'Partner not found');
            $this->redirect('admin/partners');
            return;
        }

        $logo = $this->handleImageUpload('logo_file', 'logo_url', $partner['logo_url'], 'partners');

        (new Partner())->update($id, [
            'name' => $_POST['name'],
            'logo_url' => $logo,
            'website' => $_POST['website'] ?? '',
            'description' => $_POST['description'] ?? '',
        ]);

        set_flash('success', 'Partner updated successfully');
        $this->redirect('admin/partners');
    }

    public function deletePartner($id)
    {
        (new Partner())->delete($id);
        set_flash('success', 'Partner deleted successfully');
        $this->redirect('admin/partners');
    }

    // ===== ABOUT MANAGEMENT =====
    public function about()
    {
        $sections = (new About())->all();
        $data = [
            'sections' => $sections,
            'page_title' => 'About Sections',
        ];
        $this->view('admin.about', $data);
    }

    public function createAboutSectionForm()
    {
        $this->view('admin.about-form', ['page_title' => 'Create About Section']);
    }

    public function createAboutSection()
    {
        $rules = ['title' => 'required'];
        $errors = $this->validate($_POST, $rules);
        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/about/create');
        }

        $image = $this->handleImageUpload('image_file', 'image_url', '', 'about');

        (new About())->create([
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'image_url' => $image,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'About section created successfully');
        $this->redirect('admin/about');
    }

    public function editAboutSectionForm($id)
    {
        $about = (new About())->find($id);
        if (!$about) {
            set_flash('error', 'About section not found');
            $this->redirect('admin/about');
            return;
        }
        $this->view('admin.about-form', [
            'about' => $about,
            'page_title' => 'Edit About Section',
        ]);
    }

    public function updateAboutSection($id)
    {
        $about = (new About())->find($id);
        if (!$about) {
            set_flash('error', 'About section not found');
            $this->redirect('admin/about');
            return;
        }

        $image = $this->handleImageUpload('image_file', 'image_url', $about['image_url'], 'about');

        (new About())->update($id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'image_url' => $image,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        set_flash('success', 'About section updated successfully');
        $this->redirect('admin/about');
    }

    public function deleteAboutSection($id)
    {
        (new About())->delete($id);
        set_flash('success', 'About section deleted successfully');
        $this->redirect('admin/about');
    }

    // ===== USERS MANAGEMENT =====
    public function users()
    {
        $users = (new User())->all();
        $data = [
            'users' => $users,
            'page_title' => 'Users',
        ];
        $this->view('admin.users', $data);
    }

    public function userDetail($id)
    {
        $user = (new User())->find($id);
        if (!$user) {
            set_flash('error', 'User not found');
            $this->redirect('admin/users');
            return;
        }
        $data = [
            'user' => $user,
            'page_title' => 'User: ' . $user['email'],
        ];
        $this->view('admin.user-detail', $data);
    }

    public function changeUserPassword($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
        }

        // Validate CSRF
        if (!isset($_POST['csrf_token']) || !\Core\Security::verifyCsrfToken($_POST['csrf_token'])) {
            set_flash('error', 'Invalid security token');
            $this->redirect('admin/users/' . $id);
        }

        $user = (new User())->find($id);
        if (!$user) {
            set_flash('error', 'User not found');
            $this->redirect('admin/users');
        }

        $rules = [
            'new_password' => 'required|min:8',
            'confirm_password' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('admin/users/' . $id);
        }

        // Verify new passwords match
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            set_flash('error', 'Passwords do not match');
            $this->redirect('admin/users/' . $id);
        }

        // Update password
        (new User())->update($id, [
            'password' => \Core\Security::hashPassword($_POST['new_password']),
        ]);

        set_flash('success', 'User password changed successfully');
        $this->redirect('admin/users/' . $id);
    }

    // ===== MEDIA MANAGEMENT =====
    public function media()
    {
        $data = [
            'page_title' => 'Media Gallery',
        ];
        $this->view('admin.media', $data);
    }

    public function uploadMedia()
    {
        set_flash('success', 'Media uploaded successfully');
        $this->redirect('admin/media');
    }

    public function deleteMedia($id)
    {
        set_flash('success', 'Media deleted successfully');
        $this->redirect('admin/media');
    }

    // ===== INVOICES & PAYMENTS =====
    public function invoices()
    {
        $invoices = (new Invoice())->all();
        $data = [
            'invoices' => $invoices,
            'page_title' => 'Invoices',
        ];
        $this->view('admin.invoices', $data);
    }

    public function subscriptions()
    {
        $subscriptions = (new Subscription())->all();
        $data = [
            'subscriptions' => $subscriptions,
            'page_title' => 'Subscriptions',
        ];
        $this->view('admin.subscriptions', $data);
    }

    public function payments()
    {
        $data = [
            'page_title' => 'Payments',
        ];
        $this->view('admin.payments', $data);
    }

    // ===== THEME SETTINGS MANAGEMENT =====
    public function themeSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateThemeSettings();
            return;
        }

        $themeSettings = ThemeSetting::getCurrent();

        $data = [
            'theme_settings' => $themeSettings,
            'page_title' => 'Theme & Color Settings',
        ];

        $this->view('admin.theme-settings', $data);
    }

    public function updateThemeSettings()
    {
        // Handle background image upload
        $backgroundImage = $this->handleImageUpload('background_image_file', 'background_image', '', 'theme');

        $updateData = [
            'primary_color' => $_POST['primary_color'] ?? '#667eea',
            'secondary_color' => $_POST['secondary_color'] ?? '#764ba2',
            'accent_color' => $_POST['accent_color'] ?? '#667eea',
            'text_color' => $_POST['text_color'] ?? '#333333',
            'heading_font' => $_POST['heading_font'] ?? 'Segoe UI, Roboto, sans-serif',
            'body_font' => $_POST['body_font'] ?? 'Segoe UI, Roboto, sans-serif',
            'heading_size' => (int)($_POST['heading_size'] ?? 28),
            'body_size' => (int)($_POST['body_size'] ?? 14),
            'button_style' => $_POST['button_style'] ?? 'rounded',
            'border_radius' => (int)($_POST['border_radius'] ?? 5),
            'bg_opacity' => (float)($_POST['bg_opacity'] ?? 1),
            'bg_overlay_color' => $_POST['bg_overlay_color'] ?? 'rgba(0, 0, 0, 0)',
            'bg_position' => $_POST['bg_position'] ?? 'center',
            'bg_size' => $_POST['bg_size'] ?? 'cover',
            'bg_repeat' => $_POST['bg_repeat'] ?? 'no-repeat',
            'bg_attachment' => $_POST['bg_attachment'] ?? 'fixed',
        ];
        
        // Only update background image if a new one was uploaded
        if (!empty($backgroundImage)) {
            $updateData['background_image'] = $backgroundImage;
        }

        ThemeSetting::updateTheme($updateData);

        set_flash('success', 'Theme settings updated successfully');
        $this->redirect('admin/theme');
    }

    public function themeCSS()
    {
        header('Content-Type: text/css; charset=utf-8');
        echo ThemeSetting::generateCSS();
        exit;
    }

    // ===== MENU MANAGEMENT =====
    public function menus()
    {
        $allMenus = (new Menu())->all();
        $hierarchy = Menu::getMenuHierarchy();
        
        // Get menu URLs for comparison
        $menuUrls = array_map(function($m) { return $m['url']; }, $allMenus);
        
        // Get published pages that don't have corresponding menu items
        $allPages = (new Page())->where('status', 'published');
        $pagesWithoutMenu = array_filter($allPages, function($page) use ($menuUrls) {
            $pageUrl = '/' . $page['slug'];
            return !in_array($pageUrl, $menuUrls);
        });
        
        $data = [
            'menus' => $allMenus,
            'hierarchy' => $hierarchy,
            'pages_without_menu' => array_values($pagesWithoutMenu),
            'page_title' => 'Menu Manager',
        ];
        $this->view('admin.menu-manager', $data);
    }

    public function saveMenu()
    {
        header('Content-Type: application/json');
        
        try {
            $id = $_POST['id'] ?? null;
            $label = $_POST['label'] ?? '';
            $url = $_POST['url'] ?? '';
            $icon = $_POST['icon'] ?? '';
            $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
            $status = $_POST['status'] ?? 'active';

            if (empty($label)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Menu label is required']);
                exit;
            }

            $menu = new Menu();

            if ($id) {
                // Update existing menu
                $menu->update($id, [
                    'label' => $label,
                    'url' => $url,
                    'icon' => $icon,
                    'parent_id' => $parent_id,
                    'status' => $status,
                ]);
                $message = 'Menu item updated successfully';
            } else {
                // Create new menu - get max order safely
                try {
                    $stmt = $menu->query("SELECT COALESCE(MAX(menu_order), 0) as max_order FROM {$menu->getFullTableName()}");
                    $stmt->execute();
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $maxOrder = $result['max_order'] ?? 0;
                } catch (Exception $e) {
                    $maxOrder = 0;
                }

                $menu->create([
                    'label' => $label,
                    'url' => $url,
                    'icon' => $icon,
                    'parent_id' => $parent_id,
                    'menu_order' => $maxOrder + 1,
                    'status' => $status,
                ]);
                $message = 'Menu item created successfully';
            }

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => $message]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function deleteMenu($id)
    {
        (new Menu())->delete($id);
        set_flash('success', 'Menu item deleted successfully');
        $this->redirect('admin/menus');
    }

    public function updateMenuOrder()
    {
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Menu ID is required']);
                exit;
            }

            $id = $data['id'];
            $newOrder = $data['menu_order'] ?? 0;

            (new Menu())->update($id, ['menu_order' => $newOrder]);

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Menu order updated']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function reorderMenus()
    {
        $items = $_POST['items'] ?? [];
        
        foreach ($items as $order => $item) {
            (new Menu())->update($item['id'], [
                'menu_order' => $order + 1,
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Menus reordered successfully']);
        exit;
    }

    public function createMenuFromPage($pageId)
    {
        header('Content-Type: application/json');
        try {
            $page = (new Page())->find($pageId);
            if (!$page) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Page not found']);
                exit;
            }
            
            // Get highest menu order
            $allMenus = (new Menu())->all();
            $maxOrder = 0;
            foreach ($allMenus as $menu) {
                if (($menu['menu_order'] ?? 0) > $maxOrder) {
                    $maxOrder = $menu['menu_order'] ?? 0;
                }
            }
            
            // Create menu item from page
            (new Menu())->create([
                'label' => $page['title'],
                'url' => '/' . $page['slug'],
                'icon' => '',
                'parent_id' => null,
                'menu_order' => $maxOrder + 1,
                'status' => 'active',
            ]);
            
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Menu item created from page']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}
