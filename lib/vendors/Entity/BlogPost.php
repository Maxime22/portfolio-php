<?php
namespace Entity;

use \OCFram\Entity;

class BlogPost extends Entity
{
  protected $title,
            $headerPost,
            $author,
            $content,
            $creationDate,
            $lastModificationDate;

  const TITLE_INVALID = 1;
  const HEADERPOST_INVALID = 2;
  const AUTHOR_INVALID = 3;
  const CONTENT_INVALID = 4;

  public function isValid()
  {
    return !(empty($this->author) || empty($this->title) || empty($this->content) || empty($this->headerPost));
  }

  // SETTERS //

  public function setTitle($title)
  {
    if (!is_string($title) || empty($title))
    {
      $this->erreurs[] = self::TITLE_INVALID;
    }

    $this->title = $title;
  }

  public function setHeaderPost($headerPost)
  {
    if (!is_string($headerPost) || empty($headerPost))
    {
      $this->erreurs[] = self::HEADERPOST_INVALID;
    }

    $this->headerPost = $headerPost;
  }

  public function setAuthor($author)
  {
    if (!is_string($author) || empty($author))
    {
      $this->erreurs[] = self::AUTHOR_INVALID;
    }

    $this->author = $author;
  }

  public function setContent($content)
  {
    if (!is_string($content) || empty($content))
    {
      $this->erreurs[] = self::CONTENT_INVALID;
    }

    $this->content = $content;
  }

  public function setCreationDate(\DateTime $creationDate)
  {
    $this->creationDate = $creationDate;
  }

  public function setLastModificationDate(\DateTime $lastModificationDate)
  {
    $this->lastModificationDate = $lastModificationDate;
  }

  // GETTERS //

  public function title()
  {
    return $this->title;
  }
  
  public function headerPost()
  {
    return $this->headerPost;
  }

  public function author()
  {
    return $this->author;
  }

  public function content()
  {
    return $this->content;
  }

  public function creationDate()
  {
    return $this->creationDate;
  }

  public function lastModificationDate()
  {
    return $this->lastModificationDate;
  }
}