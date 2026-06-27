<?php
/**
 * About Page Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class About extends Model
{
    protected $table = 'about';
    protected $fillable = ['section_name', 'title', 'content', 'image', 'image_url', 'sort_order'];

    public function getBySection($section_name)
    {
        return $this->first('section_name', $section_name);
    }

    public function getAllSorted()
    {
        return $this->all() ?? [];
    }
}
