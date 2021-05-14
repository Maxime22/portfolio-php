<?php

namespace Model\Entity\BlogPost;

class BlogPost
{

    private $id;
    private $title;
    private $headerPost;
    private $author; // don't know how to manage the relation with PDO fetch class
    private $content;
    private $creationDate;
    private $lastModificationDate;

    public function __construct()
    {
    }

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getHeaderPost()
    {
        return $this->headerPost;
    }

    //not sure
    public function getAuthor()
    {
        return $this->author;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getLastModificationDate()
    {
        return $this->lastModificationDate;
    }
}
