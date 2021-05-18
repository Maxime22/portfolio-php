<?php

namespace Controller\Admin;

use Controller\Controller;
use Exception;
use Model\Manager\BlogPost\BlogPostManager;

class AdminBlogPostController extends Controller
{

    public function index()
    {
        $request = $this->getRequest();
        $flashMessage = $this->flashMessage($request);
        $flashError = $this->flashError($request);

        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $blogPosts = $blogPostManager->getPosts();
        return $this->render("admin/blogPost/index.html.twig", ['blogPosts' => $blogPosts, 'flashMessage' => $flashMessage, 'flashError' => $flashError]);
    }

    public function create()
    {
        $request = $this->getRequest();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                $author = $request->getSession('auth');
                $creationDate = date('Y-m-d H:i:s');
                $blogPostManager->insertPost(
                    [
                        'title' => $request->postData('title'),
                        'headerPost' => $request->postData('headerPost'),
                        'author' => $author,
                        'content' => $request->postData('content'),
                        'creationDate' => $creationDate
                    ]
                );
                $request->setSession('flashMessage', "Article ajouté");
                $this->redirect("admin_blogPosts");
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/blogPost/create.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : null
        ]);
    }

    public function modify($id)
    {
        $request = $this->getRequest();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $blogPost = $blogPostManager->getPost($id);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                $lastModificationDate = date('Y-m-d H:i:s');
                $blogPostManager->updatePost(
                    [
                        'title' => $request->postData('title'),
                        'headerPost' => $request->postData('headerPost'),
                        'content' => $request->postData('content'),
                        'lastModificationDate' => $lastModificationDate
                    ],
                    $id
                );
                $request->setSession('flashMessage', "Article $id modifié");
                $this->redirect("admin_blogPosts");
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/blogPost/modify.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : $blogPost,
            'articleId' => $id
        ]);
    }

    public function delete($id)
    {
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $blogPostManager->deletePost($id);
        // we redirect to the previous page after delete
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
        // if no one was connected we redirect to the homepage
        $this->redirect("homepage");
    }

    public function isValidForm($request): bool
    {
        $title = $request->postData('title');
        $headerPost = $request->postData('headerPost');
        $content = $request->postData('content');
        $returnValue = true;
        if (!$title || strlen($title) < 4) {
            throw new Exception('Titre trop court');
            $returnValue = false;
        }
        if (!$headerPost || strlen($headerPost) < 4) {
            throw new Exception('Chapo trop court');
            $returnValue = false;
        }
        if (!$content || strlen($content) < 10) {
            throw new Exception('Contenu trop court');
            $returnValue = false;
        }
        return $returnValue;
    }
}
