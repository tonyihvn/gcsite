<?php
/**
 * Blog Post Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class BlogPost extends Model
{
    protected $table = 'blog_posts';
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'featured_image', 'author_id', 'category', 'tags', 'status', 'published_at'];

    public function getPublished()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE status = 'published' ORDER BY published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBySlug($slug)
    {
        return $this->first('slug', $slug);
    }

    public function getByCategory($category)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE category = ? AND status = 'published' ORDER BY published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
}
