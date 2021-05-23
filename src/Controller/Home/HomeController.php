<?php

namespace Controller\Home;

use App\Mailer\Mailer;
use Controller\Controller;
use App\Exception\FormException;

class HomeController extends Controller
{

    public function index()
    {
        $request = $this->getRequest();

        $mailer = new Mailer();

        $errors=[];
        try {
            if ($request->postTableData() && $this->isValidMailForm($request)) {
                // Create a message
                $message = (new \Swift_Message($request->postData('title')))
                    ->setFrom([$request->postData('mail') => $request->postData('names')])
                    ->setTo(['sabouretmaxime@gmail.com'])
                    ->setBody($request->postData('message'));

                // Send the message
                $result = $mailer->send($message);
                $request->setSession('flashMessage', "Mail envoyé");
            }
        } catch (FormException $e) {
            $errors[] = $e->getMessage();
        }

        return $this->render("home/index.html.twig", ['errors'=>$errors]);
    }

    public function error404()
    {
        http_response_code(404);
        return $this->render("home/e404.html.twig", []);
    }

    public function isValidMailForm($request)
    {
        $returnValue = true;
        $mail = $request->postData('mail');
        $username = $request->postData('names');
        $title = $request->postData('title');
        $message = $request->postData('message');
        if (!$username || strlen($username) < 4) {
            throw new FormException('Nom et prénom trop courts');
            $returnValue = false;
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new FormException('Votre mail ne convient pas');
            $returnValue = false;
        }
        if (!$title || strlen($title) < 4) {
            throw new FormException('Titre trop court');
            $returnValue = false;
        }
        if (!$message || strlen($message) < 10) {
            throw new FormException('Message trop court');
            $returnValue = false;
        }

        return $returnValue;
    }
}
