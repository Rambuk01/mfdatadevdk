<?php

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use App\Services\WordPressApi;

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/src/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/cache/twig',
    'auto_reload' => true,
]);

// WordPress API client
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$wpApiBase = getenv('WP_API_BASE') ?: "{$scheme}://{$host}/cms/wp-json/wp/v2";
$wpApi = new WordPressApi($wpApiBase);

// Custom Twig filters
$twig->addFilter(new TwigFilter('truncate', function (string $text, int $length = 100, string $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}));

// Global template variables
$twig->addGlobal('site_name', 'Mario Festersen');
$twig->addGlobal('site_tagline', 'Data & Development');
$twig->addGlobal('current_year', date('Y'));

// Router
$router = new \Bramus\Router\Router();

require_once __DIR__ . '/src/routes.php';

$router->run();
