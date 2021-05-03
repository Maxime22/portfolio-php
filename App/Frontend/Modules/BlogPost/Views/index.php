<?php
foreach ($listeBlogPost as $blogPost)
{
?>
  <h2><a href="/"><?= $blogPost['title'] ?></a></h2>
  <p><?= nl2br($blogPost['content']) ?></p>
<?php
}