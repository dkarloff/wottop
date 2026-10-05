<?php
if (!defined('DATALIFEENGINE')) { die('Access denied'); }

// Apache supplies these parameters through .htaccess. A nginx try_files
// fallback may forward only REQUEST_URI, so recover the same public routes.
if (array_intersect(['newsid', 'do', 'subaction', 'category', 'cstart', 'mod', 'year', 'catalog'], array_keys($_GET))) {
    return;
}
$routePath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (!is_string($routePath)) { return; }
$routePath = ltrim($routePath, '/');
if ($routePath === '' || $routePath === 'index.php') { return; }

$routeRules = [
    '~^page/([0-9]+)/?$~' => ['cstart' => 1],
    '~^([^.]+)/page,([0-9]+),([0-9]+),([0-9]+)-(.*)\.html$~' => ['seocat' => 1, 'news_page' => 2, 'cstart' => 3, 'newsid' => 4, 'seourl' => 5],
    '~^([^.]+)/page,([0-9]+),([0-9]+)-(.*)\.html$~' => ['seocat' => 1, 'news_page' => 2, 'newsid' => 3, 'seourl' => 4],
    '~^([^.]+)/([0-9]+)-(.*)\.html$~' => ['seocat' => 1, 'newsid' => 2, 'seourl' => 3],
    '~^([0-9]+)-(.*)\.html$~' => ['newsid' => 1, 'seourl' => 2],
    '~^user/([^/]+)/?$~' => ['subaction' => 'userinfo', 'user' => 1],
    '~^([^.]+)/page/([0-9]+)/?$~' => ['do' => 'cat', 'category' => 1, 'cstart' => 2],
    '~^([a-zA-Z0-9_-]+(?:/[a-zA-Z0-9_-]+)*)/?$~' => ['do' => 'cat', 'category' => 1],
];
foreach ($routeRules as $pattern => $parameters) {
    if (!preg_match($pattern, $routePath, $matches)) { continue; }
    foreach ($parameters as $key => $value) {
        $_GET[$key] = is_int($value) ? rawurldecode($matches[$value]) : $value;
        // DLE reads do/subaction from REQUEST but article IDs from GET.
        if (!array_key_exists($key, $_POST)) { $_REQUEST[$key] = $_GET[$key]; }
    }
    break;
}
unset($routePath, $routeRules, $pattern, $parameters, $matches, $key, $value);
