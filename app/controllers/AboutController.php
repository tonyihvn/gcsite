<?php
/**
 * About Controller - About, Team, Partners pages
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\About;
use App\Models\TeamMember;
use App\Models\Partner;

class AboutController extends Controller
{
    public function index()
    {
        $aboutSections = (new About())->all() ?? [];
        
        $this->view('pages.about', [
            'page_title' => 'About Us - ' . config('company.name'),
            'about_sections' => $aboutSections,
        ]);
    }

    public function team()
    {
        $teamModel = new TeamMember();
        $teamMembers = [];
        
        try {
            $teamMembers = $teamModel->where('status', 'active') ?? [];
        } catch (\Exception $e) {
            $teamMembers = [];
        }
        
        $this->view('pages.team', [
            'page_title' => 'Our Team - ' . config('company.name'),
            'team_members' => $teamMembers,
        ]);
    }

    public function partners()
    {
        $partnerModel = new Partner();
        $partners = [];
        $featured_partners = [];
        
        try {
            $partners = $partnerModel->all() ?? [];
            $featured_partners = $partnerModel->where('featured', 1) ?? [];
        } catch (\Exception $e) {
            $partners = [];
            $featured_partners = [];
        }
        
        $this->view('pages.partners', [
            'page_title' => 'Our Partners - ' . config('company.name'),
            'partners' => $partners,
            'featured_partners' => $featured_partners,
        ]);
    }
}
