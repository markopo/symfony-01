<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2019-02-16
 * Time: 10:34
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\Mailer;

class UserSubscriber implements EventSubscriberInterface
{

    /**
     * @var Mailer
     */
    private $mailer;


    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [ UserRegisterEvent::NAME  => 'onUserRegister' ];
    }

    public function onUserRegister(UserRegisterEvent $event) {

       $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}