<?php
namespace Model;

use \Entity\BlogPost;

class BlogPostManagerPDO extends BlogPostManager
{
  public function getList($start = -1, $limit = -1)
  {
    $sql = 'SELECT id, author, headerPost, title, contenu, creationDate, lastModificationDate FROM blogPost ORDER BY id DESC';
    
    if ($start != -1 || $limit != -1)
    {
      $sql .= ' LIMIT '.(int) $limit.' OFFSET '.(int) $start;
    }
    
    $request = $this->dao->query($sql);
    $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\BlogPost');
    
    $blogPostList = $request->fetchAll();
    
    foreach ($blogPostList as $blogPost)
    {
      $blogPost->setCreationDate(new \DateTime($blogPost->creationDate()));
      $blogPost->setLastModificationDate(new \DateTime($blogPost->lastModificationDate()));
    }
    
    $request->closeCursor();
    
    return $blogPostList;
  }
}