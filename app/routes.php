<?php

// LOGIN
$router->get('/login', 'AuthentificationController#login', 'login');
$router->post('/login', 'AuthentificationController#login', 'login_post');
$router->get('/subscription', 'AuthentificationController#subscription', 'subscription_get');
$router->post('/subscription', 'AuthentificationController#subscription', 'subscription_post');
$router->get('/subscriptionValidation/{token}', 'AuthentificationController#subscriptionValidation', 'subscriptionValidation_get');
$router->get('/logout', function () use ($httpRequest){
    $httpRequest->unsetSession('auth');
    session_destroy();
    header('Location: /login');
});

// ADMIN
$router->get('/admin/blogPosts/create','AdminBlogPostController#create', 'admin_blogPosts_create_get');
$router->post('/admin/blogPosts/create','AdminBlogPostController#create', 'admin_blogPosts_create_post');
$router->get('/admin/blogPosts/modify/{id}','AdminBlogPostController#modify', 'admin_blogPosts_modify_get');
$router->post('/admin/blogPosts/modify/{id}','AdminBlogPostController#modify', 'admin_blogPosts_modify_post');
$router->post('/admin/blogPosts/delete/{id}','AdminBlogPostController#delete', 'admin_blogPosts_delete_post');
$router->get('/admin/blogPosts','AdminBlogPostController#index', 'admin_blogPosts');

$router->get('/admin/users/create','AdminUserController#create', 'admin_users_create_get');
$router->post('/admin/users/create','AdminUserController#create', 'admin_users_create_post');
$router->get('/admin/users/modify/{id}','AdminUserController#modify', 'admin_users_modify_get');
$router->post('/admin/users/modify/{id}','AdminUserController#modify', 'admin_users_modify_post');
$router->post('/admin/users/delete/{id}','AdminUserController#delete', 'admin_users_delete_post');
$router->get('/admin/users','AdminUserController#index', 'admin_users');

$router->get('/admin/comments/modify/{id}','AdminCommentController#modify', 'admin_comments_modify_get');
$router->post('/admin/comments/modify/{id}','AdminCommentController#modify', 'admin_comments_modify_post');
$router->get('/admin/comments/validate/{id}','AdminCommentController#validate', 'admin_comments_validate');
$router->post('/admin/comments/delete/{id}','AdminCommentController#delete', 'admin_comments_delete_post');
$router->get('/admin/comments','AdminCommentController#index', 'admin_comments');

$router->get('/admin','AdminController#index', 'admin');

// PAGES
$router->get('/blogPosts', 'BlogPostController#index', 'blogPosts');
$router->get('/blogPosts/{id}', 'BlogPostController#show', 'blogPost_show_get')->with('id', '([0-9]+)')->with('slug', '([a-z\-0-9]+)');
$router->post('/blogPosts/{id}', 'BlogPostController#show', 'blogPost_show_post')->with('id', '([0-9]+)')->with('slug', '([a-z\-0-9]+)');

// HOMEPAGE
$router->get('/error404', 'HomeController#error404', 'error404');
$router->get('/', 'HomeController#index', 'home');
$router->post('/', 'HomeController#index', 'home_post');
