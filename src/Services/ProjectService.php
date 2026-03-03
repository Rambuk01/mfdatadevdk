<?php

namespace App\Services;

use Parsedown;

class ProjectService
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

    public function getProjects(string $lang, int $perPage = 10, int $page = 1): array
    {
        $projects = $this->loadAllProjects($lang);

        usort($projects, fn($a, $b) => strcmp($b['date'], $a['date']));

        $total = count($projects);
        $this->lastTotalPages = max(1, (int)ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;

        return array_slice($projects, $offset, $perPage);
    }

    public function getProject(string $slug, string $lang): ?array
    {
        $projects = $this->loadAllProjects($lang);

        foreach ($projects as $project) {
            if ($project['slug'] === $slug) {
                return $project;
            }
        }

        return null;
    }

    public function getLastTotalPages(): int
    {
        return $this->lastTotalPages;
    }

    private function loadAllProjects(string $lang): array
    {
        $dir = $this->contentDir . '/' . $lang;
        $files = is_dir($dir) ? glob($dir . '/*.md') : [];

        // Fall back to English if no projects in requested language
        if (empty($files) && $lang !== 'en') {
            $dir = $this->contentDir . '/en';
            $files = is_dir($dir) ? glob($dir . '/*.md') : [];
        }

        $projects = [];

        foreach ($files as $file) {
            $project = $this->parseFile($file);
            if ($project) {
                $projects[] = $project;
            }
        }

        return $projects;
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
            'featured_image' => $frontmatter['featured_image'] ?? null,
            'meta' => [
                'tech_stack' => $frontmatter['tech_stack'] ?? '',
                'live_url' => $frontmatter['live_url'] ?? '',
                'github_url' => $frontmatter['github_url'] ?? '',
            ],
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
