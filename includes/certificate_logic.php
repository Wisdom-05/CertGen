<?php
/**
 * certificate_logic.php - Bridge to templates.php
 */

require_once 'templates.php';

if (!function_exists('getCertificateContent')) {
    /**
     * Map camelCase call to snake_case function in templates.php
     */
    function getCertificateContent($type, $data) {
        return get_certificate_content($type, $data);
    }
}
