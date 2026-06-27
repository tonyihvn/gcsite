<?php
/**
 * Theme Settings Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class ThemeSetting extends Model
{
    protected $table = 'theme_settings';
    protected $fillable = ['primary_color', 'secondary_color', 'accent_color', 'text_color', 'heading_font', 'body_font', 'heading_size', 'body_size', 'button_style', 'border_radius', 'background_image', 'bg_opacity', 'bg_overlay_color', 'bg_position', 'bg_size', 'bg_repeat', 'bg_attachment'];

    /**
     * Get current theme settings (usually just one record)
     */
    public static function getCurrent()
    {
        $model = new self();
        $settings = $model->first('id', 1);
        
        if (!$settings) {
            // Create default settings if none exist
            $model->create([
                'primary_color' => '#667eea',
                'secondary_color' => '#764ba2',
                'accent_color' => '#667eea',
                'text_color' => '#333333',
                'heading_font' => 'Segoe UI, Roboto, sans-serif',
                'body_font' => 'Segoe UI, Roboto, sans-serif',
                'heading_size' => 28,
                'body_size' => 14,
                'button_style' => 'rounded',
                'border_radius' => 5,
                'background_image' => '',
                'bg_opacity' => 1,
                'bg_overlay_color' => 'rgba(0, 0, 0, 0)',
                'bg_position' => 'center',
                'bg_size' => 'cover',
                'bg_repeat' => 'no-repeat',
                'bg_attachment' => 'fixed',
            ]);
            return $model->first('id', 1);
        }
        
        return $settings;
    }

    /**
     * Update theme settings
     */
    public static function updateTheme($data)
    {
        $current = self::getCurrent();
        $model = new self();
        
        if ($current) {
            $model->update($current['id'], $data);
        } else {
            $model->create($data);
        }
    }

    /**
     * Generate CSS variables for theme
     */
    public static function generateCSS()
    {
        $settings = self::getCurrent();
        
        $css = ":root {\n";
        $css .= "  --primary-color: {$settings['primary_color']};\n";
        $css .= "  --secondary-color: {$settings['secondary_color']};\n";
        $css .= "  --accent-color: {$settings['accent_color']};\n";
        $css .= "  --text-color: {$settings['text_color']};\n";
        $css .= "  --heading-font: {$settings['heading_font']};\n";
        $css .= "  --body-font: {$settings['body_font']};\n";
        $css .= "  --heading-size: {$settings['heading_size']}px;\n";
        $css .= "  --body-size: {$settings['body_size']}px;\n";
        $css .= "  --border-radius: {$settings['border_radius']}px;\n";
        $css .= "}\n\n";

        // Apply CSS variables to common elements
        $css .= "body {\n";
        $css .= "  font-family: var(--body-font);\n";
        $css .= "  font-size: var(--body-size);\n";
        $css .= "  color: var(--text-color);\n";
        $css .= "  margin: 0;\n";
        $css .= "  padding: 0;\n";
        
        // Add background image styling if background_image is set
        if (!empty($settings['background_image'])) {
            $bgUrl = \Core\FileUploader::getImageUrl($settings['background_image']);
            $opacity = (float)($settings['bg_opacity'] ?? 1);
            $bgColor = $settings['bg_overlay_color'] ?? 'rgba(0, 0, 0, 0)';
            $bgPosition = $settings['bg_position'] ?? 'center';
            $bgSize = $settings['bg_size'] ?? 'cover';
            $bgRepeat = $settings['bg_repeat'] ?? 'no-repeat';
            $bgAttachment = $settings['bg_attachment'] ?? 'fixed';
            
            $css .= "  background-image: url('{$bgUrl}');\n";
            $css .= "  background-attachment: {$bgAttachment};\n";
            $css .= "  background-size: {$bgSize};\n";
            $css .= "  background-position: {$bgPosition};\n";
            $css .= "  background-repeat: {$bgRepeat};\n";
            $css .= "  background-color: #ffffff;\n";
            $css .= "  min-height: 100vh;\n";
            $css .= "}\n\n";
            
            // Add overlay pseudo-element for opacity control
            $css .= "body::before {\n";
            $css .= "  content: '';\n";
            $css .= "  position: fixed;\n";
            $css .= "  top: 0;\n";
            $css .= "  left: 0;\n";
            $css .= "  width: 100%;\n";
            $css .= "  height: 100%;\n";
            $css .= "  background-color: {$bgColor};\n";
            $css .= "  opacity: " . (1 - $opacity) . ";\n";
            $css .= "  pointer-events: none;\n";
            $css .= "  z-index: 0;\n";
            $css .= "}\n\n";
            
            // Make all main content transparent to show background
            $css .= ".container, .container-fluid, main, section {\n";
            $css .= "  background-color: transparent !important;\n";
            $css .= "  position: relative;\n";
            $css .= "  z-index: 1;\n";
            $css .= "}\n\n";
            
            $css .= ".card {\n";
            $css .= "  background-color: rgba(255, 255, 255, 0.95) !important;\n";
            $css .= "}\n\n";
        } else {
            $css .= "}\n\n";
        }

        $css .= "h1, h2, h3, h4, h5, h6 {\n";
        $css .= "  font-family: var(--heading-font);\n";
        $css .= "  color: var(--text-color);\n";
        $css .= "}\n\n";

        $css .= ".btn-primary, .btn-primary:hover {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "  border-color: var(--primary-color);\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n\n";

        $css .= ".btn-secondary, .btn-secondary:hover {\n";
        $css .= "  background-color: var(--secondary-color);\n";
        $css .= "  border-color: var(--secondary-color);\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n\n";

        $css .= ".btn-outline-primary {\n";
        $css .= "  color: var(--primary-color);\n";
        $css .= "  border-color: var(--primary-color);\n";
        $css .= "}\n\n";

        $css .= ".btn-outline-primary:hover {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "  border-color: var(--primary-color);\n";
        $css .= "}\n\n";

        $css .= "a, .link {\n";
        $css .= "  color: var(--primary-color);\n";
        $css .= "}\n\n";

        $css .= "a:hover, .link:hover {\n";
        $css .= "  color: var(--secondary-color);\n";
        $css .= "}\n\n";

        $css .= ".badge-primary {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "}\n\n";

        $css .= ".badge-secondary {\n";
        $css .= "  background-color: var(--secondary-color);\n";
        $css .= "}\n\n";

        $css .= ".form-control:focus {\n";
        $css .= "  border-color: var(--primary-color);\n";
        $css .= "  box-shadow: 0 0 0 0.2rem rgba(" . self::hexToRgb($settings['primary_color']) . ", 0.25);\n";
        $css .= "}\n\n";

        $css .= ".nav-link.active {\n";
        $css .= "  color: var(--primary-color);\n";
        $css .= "  border-bottom-color: var(--primary-color);\n";
        $css .= "}\n\n";

        $css .= ".card {\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n\n";

        $css .= ".alert-primary {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "  color: white;\n";
        $css .= "}\n\n";

        return $css;
    }

    /**
     * Convert hex color to RGB
     */
    private static function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "{$r}, {$g}, {$b}";
    }
}
