<?php
/**
 * Referral Model - leads manually entered by a referrer
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;
use PDO;

class Referral extends Model
{
    protected $table = 'referrals';
    protected $fillable = ['user_id', 'name', 'phone', 'email', 'interested_in', 'notes', 'status'];

    /**
     * All leads entered by a given referrer, newest first.
     */
    public function forUser($userId)
    {
        $sql = "SELECT * FROM {$this->prefix}referrals WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count leads for a referrer, optionally filtered by status.
     */
    public function countForUser($userId, $status = null)
    {
        if ($status) {
            $sql = "SELECT COUNT(*) AS c FROM {$this->prefix}referrals WHERE user_id = ? AND status = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $status]);
        } else {
            $sql = "SELECT COUNT(*) AS c FROM {$this->prefix}referrals WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
        }
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
    }

    /**
     * All leads across all referrers, joined with the referrer's details.
     */
    public function allWithReferrer()
    {
        $sql = "SELECT r.*, u.first_name, u.last_name, u.email AS referrer_email, u.referral_code
                FROM {$this->prefix}referrals r
                JOIN {$this->prefix}users u ON r.user_id = u.id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
