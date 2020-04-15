<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use http\Env\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Cocur\Slugify\Slugify;


class AuthoredEntitySubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;
    /**
     * @var Slugify
     */
    private Slugify $slugify;

    public function __construct(TokenStorageInterface $tokenStorage, Slugify $slugify)
    {
        $this->tokenStorage = $tokenStorage;
        $this->slugify = $slugify;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(ViewEvent $event) {

        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        /** @var UserInterface $author */
        $author = $this->tokenStorage->getToken()->getUser();

        if (!$entity instanceof BlogPost || \Symfony\Component\HttpFoundation\Request::METHOD_POST !== $method) {
            return;
        }

        $entity->setAuthor($author);

        $title = $entity->getTitle();
        $slug = $this->slugify->slugify($title);
        $entity->setSlug($slug);

    }
}
