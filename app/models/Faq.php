<?php
/**
 * FAQ Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Faq extends Model
{
    protected $table = 'faqs';
    protected $fillable = ['question', 'answer', 'category', 'sort_order', 'status'];

    public function getActive()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE status = 'active' ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory($category)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE category = ? AND status = 'active' ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    public function search($query)
    {
        $searchQuery = '%' . $query . '%';
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE status = 'active' AND (question LIKE ? OR answer LIKE ?) ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchQuery, $searchQuery]);
        return $stmt->fetchAll();
    }
}
