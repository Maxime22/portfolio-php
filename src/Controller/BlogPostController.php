<?php

namespace MyBlog\Controller;

// TODO extends Abstract controller
class BlogPostController{

    public function show($id,$slug){
        echo "Je suis l'article $id $slug";
    }

}