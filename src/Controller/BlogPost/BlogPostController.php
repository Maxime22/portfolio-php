<?php

namespace Controller\BlogPost;

use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;
use Model\Manager\Comment\CommentManager;
use Model\Manager\User\UserManager;

class BlogPostController extends Controller
{

    public function index()
    {
        // repository in symfo == manager here
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $blogPosts = $blogPostManager->getPosts();
        return $this->render("blogPost/index.html.twig", ['blogPosts' => $blogPosts]);
    }

    public function show($id)
    {
        $request = $this->getRequest();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $commentManager = $this->getDatabase()->getManager(CommentManager::class);
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        $blogPost = $blogPostManager->getPost($id);
        // get validated comments
        $comments = $commentManager->getCommentByBlogPost($id);

        // useless ?
        /* $userRoles = null;
        $auth = $request->getSession('auth');
        if ($auth) {
            $userRoles = $userManager->getRolesById($auth);
        } */

        return $this->render("blogPost/show.html.twig", ['blogPost' => $blogPost, 'comments' => $comments]);
    }
}
