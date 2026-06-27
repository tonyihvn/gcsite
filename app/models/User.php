<?php
/**
 * User Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'password', 'role', 'status', 'last_login', 'remember_token', 'reset_token', 'reset_token_expires_at'];
    protected $hidden = ['password', 'remember_token', 'reset_token'];

    public function create($data)
    {
        $data['password'] = \Core\Security::hashPassword($data['password']);
        return parent::create($data);
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function subscriptions()
    {
        // Get user subscriptions
        $sql = "SELECT s.*, p.name as product_name FROM {$this->prefix}subscriptions s 
                JOIN {$this->prefix}products p ON s.product_id = p.id 
                WHERE s.user_id = ? ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }

    public function invoices()
    {
        $sql = "SELECT * FROM {$this->prefix}invoices WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }

    public function payments()
    {
        $sql = "SELECT * FROM {$this->prefix}payments WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll();
    }
}
