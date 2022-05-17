<?php

namespace App\Notification;

use App\Entity\User;
use Twig\Environment;

class RegisterNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */


    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        // hors d'un controller, on ne peut faire d'injections de dépendances seulement dans un constructeur
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(User $user)
    {
        {
        $message = (new \Swift_Message())
        ->setFrom($user->getEmail())
        ->setTo('adminecommerce@gmail.com')
        ->setReplyTo($user->getEmail())
        ->setBody($this->renderer->render('emails/register.html.twig', [
        'user' => $user
                ]), 'text/html');   // il faut préciser que le corps du mail est un fichier html pour interpréter les balises
        $this->mailer->send($message);
        }
    }
}