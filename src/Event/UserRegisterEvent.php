<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2019-02-16
 * Time: 10:24
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

class UserRegisterEvent extends Event
{
    const NAME = 'user.register';

    /**
     * @var User
     */
    private $registeredUser;

    public function __construct(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * @return User
     */
    public function getRegisteredUser(): User
    {
        return $this->registeredUser;
    }


}