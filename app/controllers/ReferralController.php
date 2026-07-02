<?php
/**
 * Referral Controller - user-facing referral dashboard
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralClick;
use App\Models\Service;
use App\Models\Product;

class ReferralController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::check();
    }

    public function index()
    {
        $user = auth();
        $userModel = new User();

        // Ensure the user has a referral code (lazy generation for existing accounts)
        if (empty($user['referral_code'])) {
            $code = $userModel->generateReferralCode($user['id']);
            $_SESSION['user']['referral_code'] = $code;
            $user['referral_code'] = $code;
        }

        $clickModel = new ReferralClick();
        $referralModel = new Referral();

        $data = [
            'user'             => $user,
            'referral_code'    => $user['referral_code'],
            'base_url'         => rtrim(config('url'), '/'),
            'total_clicks'     => $clickModel->countForUser($user['id']),
            'service_clicks'   => $clickModel->countForUserByType($user['id'], 'service'),
            'product_clicks'   => $clickModel->countForUserByType($user['id'], 'product'),
            'recent_clicks'    => $clickModel->recentForUser($user['id'], 15),
            'leads'            => $referralModel->forUser($user['id']),
            'converted_count'  => $referralModel->countForUser($user['id'], 'converted')
                                  + $referralModel->countForUser($user['id'], 'paid'),
            'services'         => (new Service())->getActive(),
            'products'         => (new Product())->getPublished(),
            'page_title'       => 'Referral Program - ' . config('company.name'),
        ];

        $this->view('user.referrals', $data);
    }

    /**
     * Manually (re)generate a referral code.
     */
    public function generateCode()
    {
        $user = auth();

        if (!isset($_POST['csrf_token']) || !\Core\Security::verifyCsrfToken($_POST['csrf_token'])) {
            set_flash('error', 'Invalid security token');
            $this->redirect('dashboard/referrals');
        }

        $code = (new User())->generateReferralCode($user['id']);
        $_SESSION['user']['referral_code'] = $code;

        set_flash('success', 'Your referral code has been generated.');
        $this->redirect('dashboard/referrals');
    }

    /**
     * Add a referred person (lead).
     */
    public function addLead()
    {
        $user = auth();

        if (!isset($_POST['csrf_token']) || !\Core\Security::verifyCsrfToken($_POST['csrf_token'])) {
            set_flash('error', 'Invalid security token');
            $this->redirect('dashboard/referrals');
        }

        $rules = [
            'name'  => 'required|min:2',
            'phone' => 'required|min:6',
        ];
        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('dashboard/referrals');
        }

        (new Referral())->create([
            'user_id'       => $user['id'],
            'name'          => trim($_POST['name']),
            'phone'         => trim($_POST['phone']),
            'email'         => trim($_POST['email'] ?? ''),
            'interested_in' => trim($_POST['interested_in'] ?? ''),
            'notes'         => trim($_POST['notes'] ?? ''),
            'status'        => 'pending',
        ]);

        set_flash('success', 'Referred contact added successfully.');
        $this->redirect('dashboard/referrals');
    }

    /**
     * Delete a lead (only if it belongs to the current user).
     */
    public function deleteLead($id)
    {
        $user = auth();
        $referralModel = new Referral();
        $lead = $referralModel->find($id);

        if ($lead && (int)$lead['user_id'] === (int)$user['id']) {
            $referralModel->delete($id);
            set_flash('success', 'Referred contact removed.');
        } else {
            set_flash('error', 'Contact not found.');
        }

        $this->redirect('dashboard/referrals');
    }
}
