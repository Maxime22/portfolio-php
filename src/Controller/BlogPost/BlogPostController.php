<?php

// when there is nothing before the name of the file, our autoload goes to src/ so we just have to specify the file we want
namespace Controller\BlogPost;

use Controller\Controller;

class BlogPostController extends Controller{

    public function index(){
        $this->render("Je suis la page d'accueil des articles");
    }

    public function show($id,$slug){
        echo "Je suis l'article numéro $id avec le slug $slug";
    }

}