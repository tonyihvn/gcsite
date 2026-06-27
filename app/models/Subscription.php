<?php
/**
 * Subscription Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = ['user_id', 'product_id', 'plan_name', 'plan_price', 'billing_cycle', 'status', 'start_date', 'end_date', 'renewal_date', 'auto_renew', 'payment_method'];

    public function user()
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        return $stmt->fetch();
    }

    public function product()
    {
        $sql = "SELECT * FROM {$this->prefix}products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->product_id]);
        return $stmt->fetch();
    }

    public function getActiveSubscriptions()
    {
        $sql = "SELECT * FROM {$this->prefix}subscriptions WHERE status = 'active' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function pauseSubscription($id)
    {
        return $this->update($id, ['status' => 'paused']);
    }

    public function cancelSubscription($id)
    {
        return $this->update($id, ['status' => 'cancelled', 'end_date' => date('Y-m-d')]);
    }
}
