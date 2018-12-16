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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
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
     * MicroPostController constructor.
     * @param MicroPostRepository $microPostRepository
     */
    public function __construct(MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index() {
        $microPosts = $this->microPostRepository->findAll();

        return $this->render('micro-post/index.html.twig', [ 'microposts' => $microPosts ]);
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
}