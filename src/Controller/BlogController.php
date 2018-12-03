<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-01
 * Time: 08:45
 */

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController
{


    private $greeting;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * BlogController constructor.
     */
    public function __construct(Greeting $greeting, \Twig_Environment $twig)
    {
        $this->greeting = $greeting;

        $this->twig = $twig;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request) {

        $name = $request->get('name');
        $message = $name !== null ? $this->greeting->greet($name) : '';

        $html = $this->twig->render('base.html.twig', [ 'message' => $message  ]);
        return new Response($html);
    }
}