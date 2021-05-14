<?php

// when there is nothing before the name of the file, our autoload goes to src/ so we just have to specify the file we want
namespace Controller\BlogPost;

use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;

class BlogPostController extends Controller{

    public function index(){
        $manager = $this->getDatabase()->getManager(BlogPostManager::class);
        $posts = $manager->getPosts();
        return $this->render("blogPost/index.html.twig", []);
    }

    public function show($id,$slug){
        echo "Je suis l'article numéro $id avec le slug $slug";
    }

}