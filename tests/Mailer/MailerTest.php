<?php


namespace App\Tests\Mailer;


use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{

    public function testConfirmationEmail() {

        $user = new User();
        $user->setEmail('john@doe.com');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $swiftMailer->expects($this->once())->method('send')
                            ->with($this->callback(function($subject){
                                  $messageStr = (string)$subject;

                                  return strpos($messageStr, "From: marko@gmail.com") !== false
                                         && strpos($messageStr, "To: john@doe.com") !== false
                                         && strpos($messageStr, "Subject: Thanks for registering") !== false
                                         && strpos($messageStr, "Content-Type: text/html; charset=utf-8") !== false
                                         && strpos($messageStr, "This is a message body.") !== false;
                            }));

        $twigMock = $this->getMockBuilder(\Twig_Environment::class)
                    ->disableOriginalConstructor()
                    ->getMock();

        $twigMock->expects($this->once())->method('render')
            ->with('email/registration.html.twig', [ 'user' => $user ])
            ->willReturn('This is a message body.');

        $mailer = new Mailer($swiftMailer, $twigMock);
        $mailer->sendConfirmationEmail($user);





    }

}
