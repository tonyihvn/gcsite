<?php
/**
 * Product Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'slug', 'description', 'features', 'pricing_model', 'base_price', 'category', 'image_url', 'icon', 'demo_url', 'documentation_url', 'website', 'brochure_url', 'proposal_url', 'status', 'sort_order'];

    public function getPublished()
    {
        return $this->where('status', 'published');
    }

    public function subscriptions()
    {
        $sql = "SELECT s.*, u.first_name, u.last_name, u.email FROM {$this->prefix}subscriptions s
                JOIN {$this->prefix}users u ON s.user_id = u.id
                WHERE s.product_id = ? ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }

    public function getActiveSubscriptionsCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->prefix}subscriptions WHERE product_id = ? AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetch()['count'];
    }
}
