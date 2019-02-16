<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class RegisterController extends AbstractController
{

    private $passwordEncoder;

    private $entityManager;

    private $formFactory;

    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $entityManager,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request) {

        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $userRegisterEvent = new UserRegisterEvent($user);
            $this->eventDispatcher->dispatch(UserRegisterEvent::NAME, $userRegisterEvent);

            $indexRoute = $this->router->generate('books');
            return new RedirectResponse($indexRoute);
        }

        $formView = $form->createView();
        return $this->render('register/register.html.twig', [ 'title' => 'Register a new User', 'form' => $formView ]);
    }

}