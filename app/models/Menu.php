<?php
/**
 * Menu Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = ['label', 'url', 'icon', 'parent_id', 'menu_order', 'status'];

    /**
     * Get all menu items with their children
     */
    public static function getMenuHierarchy()
    {
        $model = new self();
        $allItems = $model->all();
        
        // Sort all items by menu_order
        usort($allItems, function($a, $b) {
            return ($a['menu_order'] ?? 0) - ($b['menu_order'] ?? 0);
        });
        
        // Build hierarchy with proper references
        $hierarchy = [];
        $itemsById = [];
        
        // Index items by ID with children array initialized
        foreach ($allItems as &$item) {
            $item['children'] = [];
            $itemsById[$item['id']] = &$item;
        }
        unset($item);
        
        // Build tree - assign items to their parents or root
        foreach ($allItems as &$item) {
            if (empty($item['parent_id'])) {
                // Root item
                $hierarchy[] = &$item;
            } else if (isset($itemsById[$item['parent_id']])) {
                // Add to parent's children
                $itemsById[$item['parent_id']]['children'][] = &$item;
            }
        }
        unset($item);
        
        // Sort children by menu_order
        foreach ($itemsById as &$item) {
            usort($item['children'], function($a, $b) {
                return ($a['menu_order'] ?? 0) - ($b['menu_order'] ?? 0);
            });
        }
        
        return $hierarchy;
    }

    /**
     * Get parent menu item
     */
    public function parent()
    {
        if (!empty($this->parent_id)) {
            return (new self())->find($this->parent_id);
        }
        return null;
    }

    /**
     * Get child menu items
     */
    public function children()
    {
        return $this->where('parent_id', $this->id ?? null);
    }

    /**
     * Reorder menu items
     */
    public static function reorder($items)
    {
        $model = new self();
        foreach ($items as $order => $item) {
            $model->update($item['id'], ['menu_order' => $order + 1]);
        }
    }
}
