<?php
/**
 * functions.php - Common helper functions
 */

if (!function_exists('h')) {
    /**
     * Sanitize HTML output
     */
    function h($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}
