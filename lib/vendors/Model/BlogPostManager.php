<?php
namespace Model;

use \OCFram\Manager;

abstract class BlogPostManager extends Manager
{
  /**
   * Return the number of blogPosts asked
   * @param $start int First blogPost to select
   * @param $limit int Max
   * @return array List of BlogPost
   */
  abstract public function getList($start = -1, $limit = -1);
}