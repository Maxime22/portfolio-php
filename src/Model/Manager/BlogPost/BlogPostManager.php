<?php

namespace Model\Manager\BlogPost;

use Model\Manager\Manager;
use Model\Entity\BlogPost\BlogPost;

class BlogPostManager extends Manager
{

    public function getPosts()
    {
        $query = $this->pdo->query("SELECT id, title, header_post as 'headerPost', user_id as 'author', content, creation_date as 'creationDate', last_modification_date as 'lastModificationDate' FROM blog_post"); 
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, BlogPost::class);
        $posts = $query->fetchAll();
        return $posts;
    }
}
