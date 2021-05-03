<?php
namespace App\Frontend\Modules\BlogPost;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class BlogPostController extends BackController
{
  public function executeIndex()
  {
    $blogPost_quantity = $this->app->config()->get('blogPost_quantity');
    
    // We add a title
    $this->page->addVar('title', 'Liste des '.$blogPost_quantity.' derniers blogPosts');
    
    // We get the manager of BlogPost to make SQL statements
    $manager = $this->managers->getManagerOf('BlogPost');
    
    $blogPostList = $manager->getList(0, $blogPost_quantity);
    
    // we add $listeBlogPost to the view
    $this->page->addVar('listeNews', $blogPostList);
  }
}