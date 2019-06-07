<?php
/**
 * Created by PhpStorm.
 * User: jean-le-grandbokassa
 * Date: 13/05/2019
 * Time: 22:03.
 */

namespace App\Service;

use App\Entity\ApiToken;
use App\Entity\User;
use Twig\Environment as Twig;

class TokenSender
{
    private $mailer;

    private $twig;

    public function __construct(\Swift_Mailer $mailer, Twig $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendToken(User $user, ApiToken $token)
    {
        $message = (new \Swift_Message('Please conform your registration'))
            ->setFrom('contact@snowtrick.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'registration/activation.html.twig', [
                        'token' => $token->getToken(),
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    'registration/activation.txt.twig', [
                        'token' => $token->getToken(),
                    ]
                ),
                'text/plain'
            )
            ;

        $this->mailer->send($message);
    }
}