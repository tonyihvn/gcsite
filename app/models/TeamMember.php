<?php
/**
 * Team Member Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';
    protected $fillable = ['name', 'title', 'department', 'bio', 'image', 'email', 'phone', 'linkedin_url', 'twitter_url', 'sort_order', 'status'];

    public function getActive()
    {
        return $this->where('status', 'active')->all();
    }
}
