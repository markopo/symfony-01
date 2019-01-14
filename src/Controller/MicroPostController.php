<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-16
 * Time: 09:10
 */

namespace App\Controller;


use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;


    /**
     * MicroPostController constructor.
     * @param MicroPostRepository $microPostRepository
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(MicroPostRepository $microPostRepository,
                                FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager,
                                RouterInterface $router,
                                FlashBagInterface $flashBag,
                                AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->authorizationChecker = $authorizationChecker;
    }

    private function authorizeEdit(MicroPost $microPost): void {

        if(!$this->authorizationChecker->isGranted('edit', $microPost)){
            throw new AccessDeniedException('ACCESS DENIED!');
        }
    }

    private function authorizeDelete(MicroPost $microPost) {
        if(!$this->authorizationChecker->isGranted('delete', $microPost)){
            throw new AccessDeniedException('ACCESS DENIED!');
        }
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index() {
        $microPosts = $this->microPostRepository->findBy([], [ 'time' => 'DESC' ]);

        return $this->render('micro-post/index.html.twig', [ 'microposts' => $microPosts ]);
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     */
    public function delete(MicroPost $microPost){

        $this->authorizeDelete($microPost);

        $this->entityManager->remove($microPost);
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'Micro post was deleted.');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     *
     */
    public function edit(MicroPost $microPost, Request $request){

        $this->authorizeEdit($microPost);

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        $microPost->setTime(new \DateTime());

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            $indexRoute = $this->router->generate('micro_post_index');
            return new RedirectResponse($indexRoute);
        }

        return $this->render('micro-post/add.html.twig', [ 'id' => $microPost->getId(), 'mp' => $microPost, 'form' => $form->createView()  ]);
    }

    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request) {

        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            $indexRoute = $this->router->generate('micro_post_index');
            return new RedirectResponse($indexRoute);
        }


        return $this->render('micro-post/add.html.twig', [ 'form' => $form->createView()  ]);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post($id){

       $mp = $this->microPostRepository->find($id);

       return $this->render('micro-post/post.html.twig', [ 'id' => $id, 'mp' => $mp ]);
    }



}