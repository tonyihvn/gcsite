<?php
/**
 * Service Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['name', 'slug', 'description', 'detailed_content', 'icon', 'image_url', 'website', 'base_price', 'delivery_days', 'brochure_url', 'proposal_url', 'status', 'sort_order'];

    public function getActive()
    {
        return $this->where('status', 'active');
    }
}
