<?php
/**
 * Setting Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key', 'value', 'type', 'group'];

    public function getByKey($key)
    {
        return $this->first('key', $key);
    }

    public static function get($key, $default = null)
    {
        $model = new self();
        $setting = $model->getByKey($key);
        
        if (!$setting) {
            return $default;
        }

        // Cast value based on type
        switch ($setting['type']) {
            case 'boolean':
                return (bool) $setting['value'];
            case 'number':
                return (int) $setting['value'];
            case 'json':
                return json_decode($setting['value'], true);
            default:
                return $setting['value'];
        }
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        $model = new self();
        $existing = $model->getByKey($key);

        if ($existing) {
            $model->update($existing['id'], [
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
            ]);
        } else {
            $model->create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
            ]);
        }
    }
}
