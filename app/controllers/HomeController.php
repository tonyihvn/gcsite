<?php
/**
 * Home Controller - Public Website
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Page;
use App\Models\Slide;
use App\Models\Feedback;
use App\Models\Faq;
use App\Models\BlogPost;
use App\Models\Subscription;

class HomeController extends Controller
{
    public function index()
    {
        $slides = (new Slide())->getActive();
        $featuredProducts = (new Product())->all();
        $services = (new Service())->getActive();
        
        $data = [
            'slides' => $slides,
            'featured_products' => array_slice($featuredProducts, 0, 6),
            'services' => array_slice($services, 0, 6),
            'page_title' => 'Home - ' . config('company.name'),
        ];

        $this->view('home.index', $data);
    }

    public function about()
    {
        $data = [
            'page_title' => 'About Us - ' . config('company.name'),
        ];

        $this->view('home.about', $data);
    }

    public function services()
    {
        $services = (new Service())->getActive();

        $data = [
            'services' => $services,
            'page_title' => 'Services - ' . config('company.name'),
        ];

        $this->view('home.services', $data);
    }

    public function serviceDetail($slug)
    {
        $service = (new Service())->first('slug', $slug);

        if (!$service) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $data = [
            'service' => $service,
            'page_title' => $service['name'] . ' - ' . config('company.name'),
        ];

        $this->view('home.service-detail', $data);
    }

    public function products()
    {
        $products = (new Product())->getPublished();

        $data = [
            'products' => $products,
            'page_title' => 'Products - ' . config('company.name'),
        ];

        $this->view('home.products', $data);
    }

    public function productDetail($slug)
    {
        $product = (new Product())->first('slug', $slug);

        if (!$product) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $data = [
            'product' => $product,
            'page_title' => $product['name'] . ' - ' . config('company.name'),
        ];

        $this->view('home.product-detail', $data);
    }

    public function contact()
    {
        $data = [
            'page_title' => 'Contact Us - ' . config('company.name'),
        ];

        $this->view('home.contact', $data);
    }

    public function submitContact()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|min:10',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('contact');
        }

        $feedback = new Feedback();
        $feedback->create([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'title' => $_POST['subject'],
            'message' => $_POST['message'],
            'type' => 'feedback',
        ]);

        set_flash('success', 'Thank you! We\'ve received your message. We\'ll get back to you soon.');
        $this->redirect('contact');
    }

    public function blog()
    {
        $posts = (new BlogPost())->getPublished();

        $data = [
            'posts' => $posts,
            'page_title' => 'Blog - ' . config('company.name'),
        ];

        $this->view('home.blog', $data);
    }

    public function blogDetail($slug)
    {
        $post = (new BlogPost())->getBySlug($slug);

        if (!$post) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $data = [
            'post' => $post,
            'page_title' => $post['title'] . ' - ' . config('company.name'),
        ];

        $this->view('home.blog-detail', $data);
    }

    public function page($slug)
    {
        $page = (new Page())->first('slug', $slug);

        if (!$page || $page['status'] !== 'published') {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $data = [
            'page' => $page,
            'page_title' => $page['title'] . ' - ' . config('company.name'),
        ];

        $this->view('home.page', $data);
    }

    public function faqs()
    {
        $faqs = (new Faq())->getActive();

        $data = [
            'faqs' => $faqs,
            'page_title' => 'FAQs - ' . config('company.name'),
        ];

        $this->view('home.faqs', $data);
    }

    public function subscribeForm($product_id)
    {
        if (!is_auth()) {
            set_flash('error', 'Please login to subscribe');
            $this->redirect('auth/login');
            return;
        }

        $product = (new Product())->find($product_id);

        if (!$product) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $user = auth();

        // Check if user already has active subscription
        $subscriptions = (new Subscription())->where('user_id', $user['id']);
        $existingSubscription = array_filter($subscriptions, function($s) use ($product_id) {
            return $s['product_id'] == $product_id && $s['status'] === 'active';
        });

        $data = [
            'product' => $product,
            'user' => $user,
            'existing_subscription' => !empty($existingSubscription),
            'page_title' => 'Subscribe to ' . $product['name'],
        ];

        $this->view('home.subscribe', $data);
    }

    public function createSubscription($product_id)
    {
        if (!is_auth()) {
            set_flash('error', 'Please login to subscribe');
            $this->redirect('auth/login');
            return;
        }

        $product = (new Product())->find($product_id);

        if (!$product) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $user = auth();

        $rules = [
            'plan_name' => 'required',
            'billing_cycle' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('subscribe/' . $product_id);
            return;
        }

        // Check for existing active subscription
        $subscriptions = (new Subscription())->where('user_id', $user['id']);
        $existingSubscription = array_filter($subscriptions, function($s) use ($product_id) {
            return $s['product_id'] == $product_id && $s['status'] === 'active';
        });

        if (!empty($existingSubscription)) {
            set_flash('error', 'You already have an active subscription to this product');
            $this->redirect('subscribe/' . $product_id);
            return;
        }

        // Get plan price from product or form
        $planPrice = $_POST['plan_price'] ?? $product['base_price'] ?? 0;

        // Calculate renewal date based on billing cycle
        $startDate = date('Y-m-d');
        $renewalDate = date('Y-m-d');
        
        switch ($_POST['billing_cycle']) {
            case 'monthly':
                $renewalDate = date('Y-m-d', strtotime('+1 month'));
                break;
            case 'quarterly':
                $renewalDate = date('Y-m-d', strtotime('+3 months'));
                break;
            case 'yearly':
                $renewalDate = date('Y-m-d', strtotime('+1 year'));
                break;
            case 'one_time':
                $renewalDate = null;
                break;
        }

        $subscriptionId = (new Subscription())->create([
            'user_id' => $user['id'],
            'product_id' => $product_id,
            'plan_name' => $_POST['plan_name'],
            'plan_price' => $planPrice,
            'billing_cycle' => $_POST['billing_cycle'],
            'status' => 'active',
            'start_date' => $startDate,
            'renewal_date' => $renewalDate,
            'auto_renew' => isset($_POST['auto_renew']) ? true : false,
            'payment_method' => 'direct',
        ]);

        set_flash('success', 'Subscription created successfully! Check your dashboard for details.');
        $this->redirect('dashboard/subscriptions');
    }
}
