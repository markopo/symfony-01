<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2019-02-17
 * Time: 19:08
 */

namespace App\Mailer;

use App\Entity\User;
use Twig\Environment;


class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    public function sendConfirmationEmail(User $user): void {


        $from = 'marko@gmail.com';
        $to = $user->getEmail();
        $user_fullname = $user->getFullName();
        $body = $this->twig->render('email/registration.html.twig', [ 'user' => $user ]);

        $message = (new \Swift_Message())
            ->setSubject('Thanks for registering ' . $user_fullname)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html');



        $this->mailer->send($message);

    }
}
