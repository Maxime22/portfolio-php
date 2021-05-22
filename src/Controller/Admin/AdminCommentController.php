<?php

namespace Controller\Admin;

use Controller\Controller;
use Exception;
use Model\Manager\Comment\CommentManager;

class AdminCommentController extends Controller
{

    public function index()
    {
        $request = $this->getRequest();
        $flashMessage = $this->flashMessage($request);
        $flashError = $this->flashError($request);

        $commentManager = $this->getDatabase()->getManager(CommentManager::class);

        $comments = $commentManager->getComments();
        return $this->render("admin/comment/index.html.twig", ['comments' => $comments, 'flashMessage' => $flashMessage, 'flashError' => $flashError, 'tokenCSRF' => $request->getSession('tokenCSRF')]);
    }

    public function validate($id)
    {
        $commentManager = $this->getDatabase()->getManager(CommentManager::class);
        $commentManager->validateComment($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    public function modify($id)
    {
        $request = $this->getRequest();
        $commentManager = $this->getDatabase()->getManager(CommentManager::class);
        $comment = $commentManager->getComment($id);
        $errors = [];
        try {
            if ($request->postTableData() && $this->isValidForm($request)) {
                $commentManager->updateComment(
                    [
                        'title' => $request->postData('title'),
                        'content' => $request->postData('content'),
                    ],
                    $id
                );
                $request->setSession('flashMessage', "Commentaire $id modifié");
                $this->redirect("admin_comments");
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        return $this->render("admin/comment/modify.html.twig", [
            'errors' => $errors,
            'postDatas' => $request->postTableData() ? $request->postTableData() : $comment,
            'commentId' => $id
        ]);
    }

    public function isValidForm($request): bool
    {
        $title = $request->postData('title');
        $content = $request->postData('content');
        $returnValue = true;
        if (!$title || strlen($title) < 4) {
            throw new Exception('Titre trop court');
            $returnValue = false;
        }
        if (!$content || strlen($content) < 10) {
            throw new Exception('Contenu trop court');
            $returnValue = false;
        }
        return $returnValue;
    }

    public function delete($id)
    {
        $request = $this->getRequest();
        $this->checkCSRF($request);
        $commentManager = $this->getDatabase()->getManager(CommentManager::class);
        $commentManager->deleteComment($id);
        // we redirect to the previous page after delete
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
