<?php
if (!defined('DATALIFEENGINE')) { die('Access denied'); }

// Keep the supplied SAPE client intact; adapt its legacy methods for PHP 8.
if (!defined('_SAPE_USER')) { define('_SAPE_USER', '9583ec52b1cd1ba69f43e9c889dadfb6'); }
$sapeDirectory = ROOT_DIR . '/' . _SAPE_USER;
if (!is_file($sapeDirectory . '/sape.php')) { return; }
require_once $sapeDirectory . '/sape.php';

if (!class_exists('WottopSapeClient')) {
    class WottopSapeClient extends SAPE_client {
        function __construct($options, $localCache = null) {
            parent::SAPE_base($options);
            if ($localCache !== null) {
                $this->set_data(@unserialize($this->_read($localCache), ['allowed_classes' => false]));
            } else {
                $this->load_data();
            }
        }
        function _read($filename) {
            $fp = @fopen($filename, 'rb');
            if (!$fp) { return ''; }
            flock($fp, LOCK_SH);
            $data = stream_get_contents($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
            if (!is_string($data)) { return ''; }
            // Git on Windows may have converted line endings inside serialized data.
            if (@unserialize($data, ['allowed_classes' => false]) === false) {
                $normalized = str_replace("\r\n", "\n", $data);
                if (is_array(@unserialize($normalized, ['allowed_classes' => false]))) { return $normalized; }
            }
            return $data;
        }
        function _write($filename, $data) {
            return @file_put_contents($filename, $data, LOCK_EX) !== false;
        }
        function set_data($data) {
            parent::set_data(is_array($data) ? $data : []);
        }
    }
}
try {
    $sapeHost = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? 'localhost')[0]);
    $sapeLocal = $sapeHost === 'localhost' || filter_var($sapeHost, FILTER_VALIDATE_IP)
        || preg_match('/\.(loc|local|test)$/', $sapeHost);
    $sapeClient = new WottopSapeClient([
        'charset' => 'UTF-8', 'use_server_array' => true,
        'socket_timeout' => 2, 'verbose' => false
    ], $sapeLocal ? $sapeDirectory . '/links.db' : null);
    $sapeLinks = $sapeClient->return_links();
    if (trim($sapeLinks) !== '') {
        echo '<section class="sape-block"><h2>Партнёрские ссылки</h2><div class="sape-links">' . $sapeLinks . '</div></section>';
    }
} catch (Throwable $error) {
    error_log('WOTTOP: SAPE sidebar unavailable: ' . $error->getMessage());
}
