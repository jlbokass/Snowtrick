<?php


namespace App\Service;


use App\Entity\ApiToken;
use App\Entity\User;
use Twig\Environment as Twig;

class ResetPasswordSender
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
        $message = (new \Swift_Message('Snowtrick reset password'))
            ->setFrom('contact@snowtrick.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'password_admin/reset_email.html.twig', [
                        'token' => $token->getToken(),
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    'password_admin/reset_email.txt.twig', [
                        'token' => $token->getToken(),
                    ]
                ),
                'text/plain'
            )
        ;

        $this->mailer->send($message);
    }
}