<?php

namespace Model\Manager\BlogPost;

use Model\Manager\Manager;
use Model\Entity\BlogPost\BlogPost;

class BlogPostManager extends Manager
{

    public function getPosts()
    {
        $posts = $this->queryFetchAll(
            "SELECT id, title, header_post as 'headerPost', user_id as 'author', content, creation_date as 'creationDate', last_modification_date as 'lastModificationDate' FROM blog_post",
            BlogPost::class
        );
        return $posts;
    }

    public function getPost(string $id)
    {
        $post = $this->queryFetch(
            "SELECT id, title, header_post as 'headerPost', user_id as 'author', content, creation_date as 'creationDate', last_modification_date as 'lastModificationDate' FROM blog_post WHERE id=" . $id,
            BlogPost::class
        );
        return $post;
    }

    public function insertPost(array $params)
    {
        $this->insert(
            "INSERT INTO blog_post (title, header_post, user_id, content, creation_date) VALUES (:title,:headerPost,:author, :content, :creationDate)",
            BlogPost::class,
            $params
        );
    }
}
