<?php

namespace App\Services;

use Parsedown;

class BlogService
{
    private string $contentDir;
    private Parsedown $parsedown;
    private int $lastTotalPages = 1;

    public function __construct(string $contentDir)
    {
        $this->contentDir = $contentDir;
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(false);
    }

    public function getPosts(string $lang, int $perPage = 10, int $page = 1): array
    {
        $posts = $this->loadAllPosts($lang);

        usort($posts, fn($a, $b) => strcmp($b['date'], $a['date']));

        $total = count($posts);
        $this->lastTotalPages = max(1, (int)ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;

        return array_slice($posts, $offset, $perPage);
    }

    public function getPost(string $slug, string $lang): ?array
    {
        $posts = $this->loadAllPosts($lang);

        foreach ($posts as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    public function getLastTotalPages(): int
    {
        return $this->lastTotalPages;
    }

    private function loadAllPosts(string $lang): array
    {
        $dir = $this->contentDir . '/' . $lang;
        $files = is_dir($dir) ? glob($dir . '/*.md') : [];

        // Fall back to English if no posts in requested language
        if (empty($files) && $lang !== 'en') {
            $dir = $this->contentDir . '/en';
            $files = is_dir($dir) ? glob($dir . '/*.md') : [];
        }

        $posts = [];

        foreach ($files as $file) {
            $post = $this->parseFile($file);
            if ($post) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    private function parseFile(string $filePath): ?array
    {
        $raw = file_get_contents($filePath);

        if ($raw === false) {
            return null;
        }

        $frontmatter = [];
        $body = $raw;

        if (preg_match('/\A---\s*\n(.+?)\n---\s*\n(.*)\z/s', $raw, $matches)) {
            $frontmatter = $this->parseFrontmatter($matches[1]);
            $body = $matches[2];
        }

        $html = $this->parsedown->text($body);

        $title = $frontmatter['title'] ?? basename($filePath, '.md');
        $date = $frontmatter['date'] ?? '';
        $slug = $frontmatter['slug'] ?? $this->slugFromFilename(basename($filePath));
        $excerpt = $frontmatter['excerpt'] ?? $this->generateExcerpt($body);

        return [
            'title' => ['rendered' => htmlspecialchars($title)],
            'date' => $date,
            'slug' => $slug,
            'excerpt' => ['rendered' => '<p>' . htmlspecialchars($excerpt) . '</p>'],
            'content' => ['rendered' => $html],
        ];
    }

    private function parseFrontmatter(string $yaml): array
    {
        $data = [];

        foreach (explode("\n", $yaml) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, ':')) {
                continue;
            }
            $colonPos = strpos($line, ':');
            $key = trim(substr($line, 0, $colonPos));
            $value = trim(substr($line, $colonPos + 1));
            $value = trim($value, '"\'');
            $data[$key] = $value;
        }

        return $data;
    }

    private function slugFromFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        return preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $name);
    }

    private function generateExcerpt(string $markdown, int $length = 200): string
    {
        $text = strip_tags($this->parsedown->text($markdown));
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . '...';
    }
}
