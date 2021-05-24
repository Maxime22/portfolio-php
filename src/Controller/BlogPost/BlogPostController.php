<?php

namespace Controller\BlogPost;

use App\Exception\FormException;
use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;
use Model\Manager\Comment\CommentManager;
use Model\Manager\User\UserManager;
use Exception;

class BlogPostController extends Controller
{

    public function index()
    {
        // repository in symfo == manager here
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $blogPosts = $blogPostManager->getPosts();
        return $this->render("blogPost/index.html.twig", ['blogPosts' => $blogPosts]);
    }

    public function show($id)
    {
        $request = $this->getRequest();

        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $commentManager = $this->getDatabase()->getManager(CommentManager::class);
        $userManager = $this->getDatabase()->getManager(UserManager::class);

        $currentUser = $this->getCurrentUser($request, $userManager);

        $blogPost = $blogPostManager->getPost(["id" => $id]);

        if ($blogPost === false) {
            $this->redirect404();
        }

        $blogPostAuthorName = $userManager->getUser(["id" => $blogPost->getAuthor()])->getUsername();
        // Get validated comments
        $comments = $commentManager->getCommentByBlogPost(["blogPostId" => $id]);

        // Comment form
        $errors = [];
        try {
            $this->insertComment($request, $commentManager, $id);
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }

        return $this->render(
            "blogPost/show.html.twig",
            [
                'blogPost' => $blogPost,
                'comments' => $comments,
                'currentUser' => $currentUser,
                'flashMessage' => $this->flashMessage($request),
                'blogPostAuthorName' => $blogPostAuthorName,
                'errors' => $errors,
                'postDatas' => $request->postTableData() ? $request->postTableData() : null
            ]
        );
    }

    private function getCurrentUser($request, $userManager){
        $currentUser = null;
        if ($request->getSession('auth')) {
            $currentUser = $userManager->getUser(["id" => $request->getSession('auth')]);
        }
        return $currentUser;
    }

    private function insertComment($request, $commentManager, $id)
    {
        if ($request->postTableData() && $this->isValidCommentForm($request)) {
            $commentManager->insertComment(
                [
                    'title' => $request->postData('title'),
                    'content' => $request->postData('content'),
                    'author' => $request->getSession('auth'),
                    'blogPostId' => $id,
                    'creationDate' => date('Y-m-d H:i:s')
                ]
            );
            // we don't want to fullfill the inputs if it is good
            unset($_POST);
            $request->setSession('flashMessage', "Commentaire ajouté, en attente de validation");
            $this->redirect("blogPost_show_get", ["id" => $id]);
        }
    }

    public function isValidCommentForm($request): bool
    {
        $returnValue = true;
        if (!$request->postData('title') || strlen($request->postData('title')) < 4) {
            throw new FormException('Titre trop court');
            $returnValue = false;
        }
        if (!$request->postData('content') || strlen($request->postData('content')) < 10) {
            throw new FormException('Contenu trop court');
            $returnValue = false;
        }
        return $returnValue;
    }
}
