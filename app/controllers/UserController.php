<?php
/**
 * User Dashboard Controller
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Product;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::check();
    }

    public function dashboard()
    {
        $user = auth();
        $subscriptions = (new Subscription())->where('user_id', $user['id']);
        $invoices = (new Invoice())->where('user_id', $user['id']);

        // Enhance subscriptions with product data
        $enhancedSubscriptions = [];
        $productModel = new Product();
        foreach ($subscriptions as $sub) {
            $product = $productModel->find($sub['product_id']);
            $sub['product_name'] = $product['name'] ?? 'Unknown Product';
            $enhancedSubscriptions[] = $sub;
        }

        $activeSubscriptions = array_filter($subscriptions, function($s) {
            return $s['status'] === 'active';
        });

        $totalSpent = array_sum(array_map(function($i) {
            return $i['amount'] ?? 0;
        }, $invoices));

        $data = [
            'user' => $user,
            'subscriptions' => $enhancedSubscriptions,
            'invoices' => $invoices,
            'active_count' => count($activeSubscriptions),
            'total_spent' => $totalSpent,
            'page_title' => 'Dashboard - ' . config('company.name'),
        ];

        $this->view('user.dashboard', $data);
    }

    public function profile()
    {
        $user = auth();

        $data = [
            'user' => $user,
            'page_title' => 'Profile - ' . config('company.name'),
        ];

        $this->view('user.profile', $data);
    }

    public function updateProfile()
    {
        $user = auth();

        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'phone' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('dashboard/profile');
        }

        (new User())->update($user['id'], [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone'],
        ]);

        $_SESSION['user']['first_name'] = $_POST['first_name'];
        $_SESSION['user']['last_name'] = $_POST['last_name'];
        $_SESSION['user']['phone'] = $_POST['phone'];

        set_flash('success', 'Profile updated successfully');
        $this->redirect('dashboard/profile');
    }

    public function subscriptions()
    {
        $user = auth();
        $subscriptions = (new Subscription())->where('user_id', $user['id']);
        
        // Enhance subscriptions with product data
        $enhancedSubscriptions = [];
        $productModel = new Product();
        foreach ($subscriptions as $sub) {
            $product = $productModel->find($sub['product_id']);
            $sub['product_name'] = $product['name'] ?? 'Unknown Product';
            $sub['product_slug'] = $product['slug'] ?? '';
            $enhancedSubscriptions[] = $sub;
        }

        $data = [
            'subscriptions' => $enhancedSubscriptions,
            'page_title' => 'My Subscriptions - ' . config('company.name'),
        ];

        $this->view('user.subscriptions', $data);
    }

    public function invoices()
    {
        $user = auth();
        $invoices = (new Invoice())->where('user_id', $user['id']);

        $data = [
            'invoices' => $invoices,
            'page_title' => 'My Invoices - ' . config('company.name'),
        ];

        $this->view('user.invoices', $data);
    }

    public function invoiceDetail($id)
    {
        $user = auth();
        $invoice = (new Invoice())->find($id);

        if (!$invoice || $invoice['user_id'] !== $user['id']) {
            http_response_code(403);
            die('Unauthorized');
        }

        $data = [
            'invoice' => $invoice,
            'page_title' => 'Invoice #' . $invoice['invoice_number'],
        ];

        $this->view('user.invoice-detail', $data);
    }

    public function payments()
    {
        $user = auth();
        $invoiceModel = new Invoice();
        $invoices = $invoiceModel->where('user_id', $user['id']);

        $data = [
            'invoices' => $invoices,
            'page_title' => 'Payment History - ' . config('company.name'),
        ];

        $this->view('user.payments', $data);
    }

    public function products()
    {
        $products = (new Product())->getPublished();

        $data = [
            'products' => $products,
            'page_title' => 'Browse Products - ' . config('company.name'),
        ];

        $this->view('user.products', $data);
    }
}
