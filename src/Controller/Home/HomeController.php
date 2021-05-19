<?php

namespace Controller\Home;

use Exception;
use App\Mailer\Mailer;
use Controller\Controller;

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
        } catch (Exception $e) {
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
            throw new Exception('Nom et prénom trop courts');
            $returnValue = false;
        }
        if ($mail && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Votre mail ne convient pas');
            $returnValue = false;
        }
        if (!$title || strlen($title) < 4) {
            throw new Exception('Titre trop court');
            $returnValue = false;
        }
        if (!$message || strlen($message) < 10) {
            throw new Exception('Message trop court');
            $returnValue = false;
        }

        return $returnValue;
    }
}
