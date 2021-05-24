<?php

namespace Model\Manager\Comment;

use Model\Manager\Manager;
use Model\Entity\Comment\Comment;

class CommentManager extends Manager
{

    public function getComments()
    {
        $comments = $this->queryFetchAll(
            "SELECT id, title, blog_post_id as 'blogPostId', user_id as 'userId', content, creation_date as 'creationDate', publication_validated as 'publicationValidated' FROM comment ORDER BY id DESC",
            Comment::class
        );
        return $comments;
    }

    public function getComment(array $params)
    {
        $comment = $this->queryFetch(
            "SELECT id, title, blog_post_id as 'blogPostId', user_id as 'userId', content, creation_date as 'creationDate', publication_validated as 'publicationValidated' FROM comment WHERE id= :id",
            Comment::class,
            $params
        );
        return $comment;
    }

    public function validateComment(array $params)
    {
        $this->prepare(
            "UPDATE comment SET publication_validated = 1 WHERE id = :id",
            Comment::class,
            $params
        );
    }

    public function updateComment(array $params)
    {
        $this->prepare(
            "UPDATE comment SET title = :title, content = :content WHERE id = :id",
            Comment::class,
            $params
        );
    }

    // $id is blogPostId here
    public function getCommentByBlogPost(array $params)
    {
        $comments = $this->queryFetchAll(
            "SELECT id, title, blog_post_id as 'blogPostId', user_id as 'userId', content, creation_date as 'creationDate', publication_validated as 'publicationValidated' FROM comment WHERE blog_post_id = :blogPostId AND publication_validated = 1 ORDER BY id DESC",
            Comment::class,
            $params
        );
        return $comments;
    }

    public function insertComment(array $params)
    {
        $this->prepare(
            "INSERT INTO comment (title, blog_post_id, user_id, content, creation_date, publication_validated) VALUES (:title,:blogPostId,:author, :content, :creationDate,0)",
            Comment::class,
            $params
        );
    }

    public function deleteComment($id)
    {
        $this->delete("DELETE FROM comment WHERE id = :id", $id);
    }
}
