<?php
/**
 * Feedback Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = ['name', 'email', 'company', 'title', 'message', 'rating', 'type', 'status'];

    public function getNewFeedbacks()
    {
        return $this->where('status', 'new');
    }

    public function getByType($type)
    {
        return $this->where('type', $type);
    }
}
