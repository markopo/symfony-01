<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-16
 * Time: 09:10
 */

namespace App\Controller;


use App\Repository\MicroPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * MicroPostController constructor.
     * @param MicroPostRepository $microPostRepository
     */
    public function __construct(MicroPostRepository $microPostRepository)
    {
        $this->microPostRepository = $microPostRepository;

    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index() {
        $microPosts = $this->microPostRepository->findAll();

        return $this->render('micro-post/index.html.twig', [ 'microposts' => $microPosts ]);
    }
}