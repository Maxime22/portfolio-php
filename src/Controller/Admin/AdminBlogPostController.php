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
        $request->sessionStart();
        $flashMessage = $this->flashMessage($request);

        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);

        /**
         * @var BlogPost[]
         */
        $posts = $blogPostManager->getPosts();
        return $this->render("admin/blogPost/index.html.twig", ['posts' => $posts, 'flashMessage' => $flashMessage]);
    }

    public function create()
    {
        $request = $this->getRequest();
        $request->sessionStart();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                // on insere en bdd en créant les bonnes fonctions dans le manager et on renvoie vers la page des articles
                // get the id of the user authentificated
                // TODO : delete 1 here when we have real authentification
                $author = $_SESSION['auth'] ?? "1";
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
                $this->redirect("/admin/blogPosts");
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/blogPost/create.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : null
        ]);
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
