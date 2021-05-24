<?php

namespace Controller\Admin;

use App\Exception\FormException;
use Controller\Controller;
use Model\Manager\BlogPost\BlogPostManager;
use Model\Manager\User\UserManager;

class AdminBlogPostController extends Controller
{

    public function index()
    {
        $request = $this->getRequest();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        /**
         * @var BlogPost[]
         */
        $blogPosts = $blogPostManager->getPosts();
        return $this->render("admin/blogPost/index.html.twig", ['blogPosts' => $blogPosts, 'flashMessage' => $this->flashMessage($request), 'flashError' => $this->flashError($request), 'tokenCSRF' => $request->getSession('tokenCSRF')]);
    }

    public function create()
    {
        $request = $this->getRequest();
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                $blogPostManager->insertPost(
                    [
                        'title' => $request->postData('title'),
                        'headerPost' => $request->postData('headerPost'),
                        'author' => $request->getSession('auth'),
                        'content' => $request->postData('content'),
                        'creationDate' => date('Y-m-d H:i:s')
                    ]
                );
                $request->setSession('flashMessage', "Article ajouté");
                $this->redirect("admin_blogPosts");
            }
        } catch (FormException $e) {
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
        $blogPost = $blogPostManager->getPost(["id" => $id]);

        // HERE WE NEED TO GET ALL THE AUTHORS POSSIBLE AND PUT THEM IN A SELECT IN THE FORM
        $userManager = $this->getDatabase()->getManager(UserManager::class);
        $users = $userManager->getUsers();
        $authors = [];
        foreach ($users as $user) {
            if (in_array("admin", $user->getRoles())) {
                $authors[$user->getId()] = $user->getUsername();
            }
        }

        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                $lastModificationDate = date('Y-m-d H:i:s');
                $blogPostManager->updatePost(
                    [
                        'id' => $id,
                        'title' => $request->postData('title'),
                        'headerPost' => $request->postData('headerPost'),
                        'author' => $request->postData('author'),
                        'content' => $request->postData('content'),
                        'lastModificationDate' => $lastModificationDate
                    ]
                );
                $request->setSession('flashMessage', "Article $id modifié");
                $this->redirect("admin_blogPosts");
            }
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }

        return $this->render("admin/blogPost/modify.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : $blogPost,
            'articleId' => $id,
            'authors' => $authors
        ]);
    }

    public function delete($id)
    {
        $request = $this->getRequest();
        $this->checkCSRF($request);
        $blogPostManager = $this->getDatabase()->getManager(BlogPostManager::class);
        try {
            $blogPostManager->deletePost($id);
        } catch (\Exception $e) {
            $request->setSession('flashError', "Problème lors de la suppression, assurez vous de supprimer les commentaires de l'utilisateur avant de supprimer l'article");
        }
        // we redirect to the previous page after delete
        $this->redirect("admin_blogPosts");
    }

    public function isValidForm($request): bool
    {
        $returnValue = true;
        if (!$request->postData('title') || strlen($request->postData('title')) < 4) {
            throw new FormException('Titre trop court');
            $returnValue = false;
        }
        if (!$request->postData('headerPost') || strlen($request->postData('headerPost')) < 4) {
            throw new FormException('Chapo trop court');
            $returnValue = false;
        }
        if (!$request->postData('content') || strlen($request->postData('content')) < 10) {
            throw new FormException('Contenu trop court');
            $returnValue = false;
        }
        return $returnValue;
    }
}
