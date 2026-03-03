<?php

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use App\Services\WordPressApi;
use App\Services\BlogService;

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
$twig->addGlobal('site_name', 'MF Data & Development');
$twig->addGlobal('site_tagline', 'Data & Development');
$twig->addGlobal('current_year', date('Y'));

// Language detection
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$lang = 'da';

if (preg_match('#^/en(/.*)?$#', $requestUri, $matches)) {
    $lang = 'en';
    $_SERVER['REQUEST_URI'] = $matches[1] ?: '/';
} else {
    // Map Danish slugs to canonical route names
    $daSlugMap = [
        '/projekter' => '/projects',
        '/om' => '/about',
        '/kontakt' => '/contact',
    ];
    foreach ($daSlugMap as $daSlug => $enSlug) {
        if ($requestUri === $daSlug || strpos($requestUri, $daSlug . '/') === 0) {
            $_SERVER['REQUEST_URI'] = str_replace($daSlug, $enSlug, $_SERVER['REQUEST_URI']);
            break;
        }
    }
}

// Load translations
$translationFile = __DIR__ . "/translations/{$lang}.json";
$translations = file_exists($translationFile)
    ? json_decode(file_get_contents($translationFile), true) ?? []
    : [];

$twig->addGlobal('t', $translations);
$twig->addGlobal('lang', $lang);

// Compute alternate language URL for the language switcher
$altLang = $lang === 'da' ? 'en' : 'da';
$enSlugMap = ['/projects' => '/projekter', '/about' => '/om', '/contact' => '/kontakt'];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($lang === 'da') {
    $altUrl = '/en' . $currentPath;
} else {
    $altPath = $currentPath;
    foreach ($enSlugMap as $en => $da) {
        if ($altPath === $en || strpos($altPath, $en . '/') === 0) {
            $altPath = str_replace($en, $da, $altPath);
            break;
        }
    }
    $altUrl = $altPath;
}

$twig->addGlobal('alt_lang', $altLang);
$twig->addGlobal('alt_url', $altUrl);

$wpApi->setLanguage($lang);

// Blog service (markdown files)
$blogService = new BlogService(__DIR__ . '/content/blog');

// Router
$router = new \Bramus\Router\Router();

require_once __DIR__ . '/src/routes.php';

$router->run();
