<?php

namespace Model\Manager\Comment;

use Model\Manager\Manager;
use Model\Entity\Comment\Comment;

class CommentManager extends Manager
{

    public function getComments()
    {
        $comments = $this->queryFetchAll(
            "SELECT id, title, blog_post_id as 'blogPostId', user_id as 'userId', content, creation_date as 'creationDate', publication_validated as 'publicationValidated' FROM comment",
            Comment::class
        );
        return $comments;
    }

    public function getComment(string $id)
    {
        $comment = $this->queryFetch(
            "SELECT id, title, blog_post_id as 'blogPostId', user_id as 'userId', content, creation_date as 'creationDate', publication_validated as 'publicationValidated' FROM comment WHERE id=" . $id,
            Comment::class
        );
        return $comment;
    }

    public function validateComment($id)
    {
        $this->prepare(
            "UPDATE comment SET publication_validated = 1 WHERE id = $id",
            Comment::class
        );
    }

    public function updateComment(array $params, $id)
    {
        $this->prepare(
            "UPDATE comment SET title = :title, content = :content WHERE id = $id",
            Comment::class,
            $params
        );
    }
}
