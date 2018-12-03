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
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{


    private $greeting;

    /**
     * BlogController constructor.
     */
    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;

    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request) {

        $name = $request->get('name');
        $message = $name !== null ? $this->greeting->greet($name) : '';

        return $this->render('base.html.twig', [ 'message' => $message  ]);
    }
}