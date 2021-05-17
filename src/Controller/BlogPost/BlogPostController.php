<?php

// when there is nothing before the name of the file, our autoload goes to src/ so we just have to specify the file we want
namespace Controller\BlogPost;

use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;

class BlogPostController extends Controller{

    public function index(){
        // repository in symfo == manager
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $posts = $blogPostManager->getPosts();
        return $this->render("blogPost/index.html.twig", ['posts'=>$posts]);
    }

    public function show($id){
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $post = $blogPostManager->getPost($id);
        return $this->render("blogPost/show.html.twig", ['post'=>$post]);

    }

}