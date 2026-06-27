<?php
/**
 * Partner Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Partner extends Model
{
    protected $table = 'partners';
    protected $fillable = ['name', 'category', 'description', 'logo', 'website', 'contact_email', 'contact_person', 'sort_order', 'featured', 'status'];

    public function getActive()
    {
        return $this->where('status', 'active')->all();
    }

    public function getFeatured()
    {
        return $this->where('featured', 1)->where('status', 'active')->all();
    }
}
