<?php

namespace Controller\Admin;

use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;

class AdminBlogPostController extends Controller{

    public function index(){
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $posts = $blogPostManager->getPosts();
        return $this->render("admin/blogPost/index.html.twig", ['posts'=>$posts]);
    }

}