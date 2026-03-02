<?php

/**
 * All routes for the frontend.
 * Variables $router, $twig, and $wpApi are available from index.php.
 */

// Home
$router->get('/', function () use ($twig, $wpApi) {
    $posts = $wpApi->getPosts(['per_page' => 3]);
    $projects = $wpApi->getProjects(['per_page' => 3]);

    echo $twig->render('home.twig', [
        'page_title' => 'Home',
        'posts' => $posts,
        'projects' => $projects,
    ]);
});

// Blog listing
$router->get('/blog', function () use ($twig, $wpApi) {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $posts = $wpApi->getPosts(['per_page' => 10, 'page' => $page]);
    $totalPages = $wpApi->getLastTotalPages();

    echo $twig->render('blog/index.twig', [
        'page_title' => 'Blog',
        'posts' => $posts,
        'current_page' => $page,
        'total_pages' => $totalPages,
    ]);
});

// Single blog post
$router->get('/blog/{slug}', function ($slug) use ($twig, $wpApi) {
    $post = $wpApi->getPost($slug);

    if (!$post) {
        http_response_code(404);
        echo $twig->render('404.twig', ['page_title' => 'Not Found']);
        return;
    }

    echo $twig->render('blog/post.twig', [
        'page_title' => $post['title']['rendered'] ?? 'Blog Post',
        'post' => $post,
    ]);
});

// Projects listing
$router->get('/projects', function () use ($twig, $wpApi) {
    $projects = $wpApi->getProjects(['per_page' => 20]);

    echo $twig->render('projects/index.twig', [
        'page_title' => 'Projects',
        'projects' => $projects,
    ]);
});

// Single project
$router->get('/projects/{slug}', function ($slug) use ($twig, $wpApi) {
    $project = $wpApi->getProject($slug);

    if (!$project) {
        http_response_code(404);
        echo $twig->render('404.twig', ['page_title' => 'Not Found']);
        return;
    }

    echo $twig->render('projects/project.twig', [
        'page_title' => $project['title']['rendered'] ?? 'Project',
        'project' => $project,
    ]);
});

// About
$router->get('/about', function () use ($twig, $wpApi) {
    $page = $wpApi->getPage('about');

    echo $twig->render('about.twig', [
        'page_title' => 'About',
        'page' => $page,
    ]);
});

// Contact
$router->get('/contact', function () use ($twig, $wpApi) {
    $page = $wpApi->getPage('contact');

    echo $twig->render('contact.twig', [
        'page_title' => 'Contact',
        'page' => $page,
    ]);
});

// 404 fallback
$router->set404(function () use ($twig) {
    http_response_code(404);
    echo $twig->render('404.twig', ['page_title' => 'Not Found']);
});
