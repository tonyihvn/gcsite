<?php
/**
 * ReferralClick Model - tracks visits to referral links
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;
use PDO;

class ReferralClick extends Model
{
    protected $table = 'referral_clicks';
    protected $fillable = ['referral_code', 'user_id', 'link_type', 'item_slug', 'item_id', 'ip_address', 'user_agent', 'referrer_url'];

    /**
     * Total clicks for a referrer.
     */
    public function countForUser($userId)
    {
        $sql = "SELECT COUNT(*) AS c FROM {$this->prefix}referral_clicks WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
    }

    /**
     * Clicks for a referrer filtered by link type ('service' or 'product').
     */
    public function countForUserByType($userId, $type)
    {
        $sql = "SELECT COUNT(*) AS c FROM {$this->prefix}referral_clicks WHERE user_id = ? AND link_type = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $type]);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
    }

    /**
     * Most recent clicks for a referrer.
     */
    public function recentForUser($userId, $limit = 20)
    {
        $limit = (int)$limit;
        $sql = "SELECT * FROM {$this->prefix}referral_clicks WHERE user_id = ? ORDER BY created_at DESC LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Aggregated click totals grouped by referrer (for admin leaderboard).
     */
    public function totalsByUser()
    {
        $sql = "SELECT user_id, COUNT(*) AS clicks FROM {$this->prefix}referral_clicks GROUP BY user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[$row['user_id']] = (int)$row['clicks'];
        }
        return $out;
    }
}
