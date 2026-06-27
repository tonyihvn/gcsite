<?php
/**
 * Base Model Class
 * GINTEC Solutions
 */

namespace Core;

use PDO;

class Model
{
    protected $db;
    protected $table;
    protected $prefix = 'gintec_';
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../config/database.php';
        $this->db = Database::getInstance($dbConfig)->getConnection();
        $this->prefix = $dbConfig['connections'][$dbConfig['default']]['prefix'] ?? 'gintec_';
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function where($column, $value)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE `$column` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first($column, $value)
    {
        $sql = "SELECT * FROM {$this->prefix}{$this->table} WHERE `$column` = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        $columns = implode(',', array_map(function($col) { return "`$col`"; }, array_keys($data)));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->prefix}{$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        $set = implode(', ', array_map(function($key) {
            return "`$key` = ?";
        }, array_keys($data)));
        
        $sql = "UPDATE {$this->prefix}{$this->table} SET {$set} WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->prefix}{$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->prefix}{$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function query($sql)
    {
        return $this->db->prepare($sql);
    }

    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function getTable()
    {
        return $this->table;
    }

    public function getFullTableName()
    {
        return $this->prefix . $this->table;
    }
}
