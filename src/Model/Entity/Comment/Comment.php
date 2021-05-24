<?php

namespace Model\Entity\Comment;

class Comment
{
    private $id;
    private $blogPostId;
    private $userId;
    private $title;
    private $content;
    private $creationDate;
    private $publicationValidated;

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getBlogPostId()
    {
        return $this->blogPostId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getPublicationValidated()
    {
        return $this->publicationValidated;
    }
}
