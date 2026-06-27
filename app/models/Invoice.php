<?php
/**
 * Invoice Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = ['invoice_number', 'user_id', 'subscription_id', 'amount', 'status', 'due_date', 'paid_date', 'payment_method', 'notes'];

    public function generateInvoiceNumber()
    {
        $timestamp = date('YmdHis');
        $random = mt_rand(100, 999);
        return 'INV-' . $timestamp . '-' . $random;
    }

    public function user()
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        return $stmt->fetch();
    }

    public function getPendingInvoices()
    {
        $sql = "SELECT * FROM {$this->prefix}invoices WHERE status IN ('draft', 'sent', 'overdue') ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
