<?php

$router->get('/admin/blogPosts','AdminBlogPostController#index', 'admin_blogPosts');
$router->get('/admin/users','AdminUserController#index', 'admin_users');
$router->get('/admin/comments','AdminCommentController#index', 'admin_comments');
$router->get('/admin','AdminController#index', 'admin');
$router->get('/blogPosts', 'BlogPostController#index', 'blogPosts');
$router->get('/blogPosts/{id}', 'BlogPostController#show')->with('id', '([0-9]+)')->with('slug', '([a-z\-0-9]+)');
$router->get('/', 'HomeController#index', 'home');
/* $router->get('/blogPosts/{id}/{slug}', 'BlogPostController#show')->with('id', '([0-9]+)')->with('slug', '([a-z\-0-9]+)');;
$router->post('/blogPosts/{id}/{slug}', function ($id, $slug) {
    echo 'Poster pour l\'article ' . $id . '<pre>' . print_r($_POST) . '</pre>';
}, 'blogPost_post'); */