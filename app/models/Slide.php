<?php
/**
 * Slide Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Slide extends Model
{
    protected $table = 'slides';
    protected $fillable = ['title', 'description', 'image_url', 'link_url', 'button_text', 'sort_order', 'status'];

    public function getActive()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE status = 'active' ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
