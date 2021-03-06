<?php

namespace Model\Manager\BlogPost;

use Model\Manager\Manager;
use Model\Entity\BlogPost\BlogPost;

class BlogPostManager extends Manager
{

    public function getPosts()
    {
        $posts = $this->queryFetchAll(
            "SELECT id, title, header_post as 'headerPost', user_id as 'author', content, creation_date as 'creationDate', last_modification_date as 'lastModificationDate' FROM blog_post ORDER BY id DESC",
            BlogPost::class
        );
        return $posts;
    }

    public function getPost(array $params)
    {
        $post = $this->queryFetch(
            "SELECT id, title, header_post as 'headerPost', user_id as 'author', content, creation_date as 'creationDate', last_modification_date as 'lastModificationDate' FROM blog_post WHERE id = :id",
            BlogPost::class,
            $params
        );
        return $post;
    }

    public function insertPost(array $params)
    {
        $this->prepare(
            "INSERT INTO blog_post (title, header_post, user_id, content, creation_date) VALUES (:title,:headerPost,:author, :content, :creationDate)",
            BlogPost::class,
            $params
        );
    }

    public function updatePost(array $params)
    {
        $this->prepare(
            "UPDATE blog_post SET title = :title, header_post = :headerPost, user_id = :author, content = :content, last_modification_date = :lastModificationDate WHERE id = :id",
            BlogPost::class,
            $params
        );
    }

    public function deletePost($id)
    {
        $this->delete("DELETE FROM blog_post WHERE id = :id", $id);
    }
}
