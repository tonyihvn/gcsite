<?php
/**
 * Page Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = ['title', 'slug', 'description', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'featured_image', 'status', 'visibility', 'parent_id', 'menu_order', 'page_header', 'page_subheader', 'header_bg_image', 'header_bg_color'];

    public function getPublished()
    {
        return $this->where('status', 'published');
    }

    /**
     * Get parent page
     */
    public function getParent()
    {
        if ($this['parent_id']) {
            return $this->find($this['parent_id']);
        }
        return null;
    }

    /**
     * Get all child pages
     */
    public function getChildren()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE parent_id = ? ORDER BY menu_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this['id']]);
        return $stmt->fetchAll();
    }

    /**
     * Get all top-level pages (no parent)
     */
    public function getTopLevel()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE parent_id IS NULL ORDER BY menu_order ASC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get published top-level pages with children
     */
    public function getPublishedMenu()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE status = 'published' AND parent_id IS NULL ORDER BY menu_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $pages = $stmt->fetchAll();

        // Load children for each top-level page
        foreach ($pages as &$page) {
            $page['children'] = $this->getPublishedChildren($page['id']);
        }

        return $pages;
    }

    /**
     * Get published children of a page
     */
    public function getPublishedChildren($parentId)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE parent_id = ? AND status = 'published' ORDER BY menu_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    /**
     * Build complete menu tree for display
     */
    public function getMenuTree()
    {
        $topLevel = $this->getTopLevel();
        
        foreach ($topLevel as &$page) {
            $page['children'] = $this->buildMenuLevel($page['id']);
        }

        return $topLevel;
    }

    /**
     * Recursively build menu levels
     */
    private function buildMenuLevel($parentId)
    {
        $children = [];
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE parent_id = ? ORDER BY menu_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$parentId]);
        $results = $stmt->fetchAll();

        foreach ($results as $child) {
            $child['children'] = $this->buildMenuLevel($child['id']);
            $children[] = $child;
        }

        return $children;
    }
}
