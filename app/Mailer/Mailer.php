<?php

namespace App\Mailer;

class Mailer
{

    private $mailer;

    public function __construct()
    {
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername($_ENV['MAIL_USERNAME'])
            ->setPassword($_ENV['MAIL_PASSWORD']);
        $this->mailer = new \Swift_Mailer($transport);
    }

    public function send($message){
        $this->mailer->send($message);
    }
}
