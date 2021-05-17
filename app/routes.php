<?php

$router->get('/login', 'AuthentificationController#login', 'login');
$router->get('/logout', function (){
    session_destroy();
    header('Location: /login');
});
$router->get('/admin/blogPosts/create','AdminBlogPostController#create', 'admin_blogPosts_create_get');
$router->post('/admin/blogPosts/create','AdminBlogPostController#create', 'admin_blogPosts_create_post');
$router->post('/admin/blogPosts/delete/{id}','AdminBlogPostController#delete', 'admin_blogPosts_delete_post');
$router->get('/admin/blogPosts','AdminBlogPostController#index', 'admin_blogPosts');
// $router->get('/admin/users','AdminUserController#index', 'admin_users');
// $router->get('/admin/comments','AdminCommentController#index', 'admin_comments');
$router->get('/admin','AdminController#index', 'admin');
$router->get('/blogPosts', 'BlogPostController#index', 'blogPosts');
$router->get('/blogPosts/{id}', 'BlogPostController#show')->with('id', '([0-9]+)')->with('slug', '([a-z\-0-9]+)');
$router->get('/', 'HomeController#index', 'home');