<?php

namespace App\Services;

class WordPressApi
{
    private string $baseUrl;
    private string $cacheDir;
    private int $cacheTtl;
    private int $lastTotalPages = 1;

    public function __construct(string $baseUrl, int $cacheTtlSeconds = 300)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->cacheDir = __DIR__ . '/../../cache/api';
        $this->cacheTtl = $cacheTtlSeconds;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Get blog posts.
     */
    public function getPosts(array $params = []): array
    {
        $defaults = ['per_page' => 10, 'page' => 1, '_embed' => 1];
        $params = array_merge($defaults, $params);

        $result = $this->get('/posts', $params);

        return $result ?: [];
    }

    /**
     * Get a single post by slug.
     */
    public function getPost(string $slug): ?array
    {
        $results = $this->get('/posts', ['slug' => $slug, '_embed' => 1]);

        return $results[0] ?? null;
    }

    /**
     * Get projects (custom post type).
     */
    public function getProjects(array $params = []): array
    {
        $defaults = ['per_page' => 20, '_embed' => 1];
        $params = array_merge($defaults, $params);

        $result = $this->get('/project', $params);

        return $result ?: [];
    }

    /**
     * Get a single project by slug.
     */
    public function getProject(string $slug): ?array
    {
        $results = $this->get('/project', ['slug' => $slug, '_embed' => 1]);

        return $results[0] ?? null;
    }

    /**
     * Get a page by slug.
     */
    public function getPage(string $slug): ?array
    {
        $results = $this->get('/pages', ['slug' => $slug, '_embed' => 1]);

        return $results[0] ?? null;
    }

    /**
     * Get the total number of pages from the last paginated request.
     */
    public function getLastTotalPages(): int
    {
        return $this->lastTotalPages;
    }

    /**
     * Make a GET request to the WP REST API with file-based caching.
     */
    private function get(string $endpoint, array $params = []): ?array
    {
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);
        $cacheKey = md5($url);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        // Check cache
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $this->cacheTtl) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if ($cached !== null) {
                $this->lastTotalPages = $cached['_total_pages'] ?? 1;
                return $cached['data'];
            }
        }

        // Make HTTP request
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            // API unreachable — return cached data if available (even if expired)
            if (file_exists($cacheFile)) {
                $cached = json_decode(file_get_contents($cacheFile), true);
                if ($cached !== null) {
                    $this->lastTotalPages = $cached['_total_pages'] ?? 1;
                    return $cached['data'];
                }
            }
            return null;
        }

        $data = json_decode($response, true);

        // Extract total pages from response headers
        $totalPages = 1;
        if (function_exists('http_get_last_response_headers')) {
            $responseHeaders = http_get_last_response_headers() ?? [];
        } else {
            $responseHeaders = $http_response_header ?? [];
        }
        foreach ($responseHeaders as $header) {
            if (stripos($header, 'X-WP-TotalPages:') === 0) {
                $totalPages = (int)trim(substr($header, 16));
                break;
            }
        }
        $this->lastTotalPages = $totalPages;

        // Cache the response
        $cacheData = json_encode(['data' => $data, '_total_pages' => $totalPages]);
        file_put_contents($cacheFile, $cacheData);

        return $data;
    }
}
