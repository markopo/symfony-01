<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-08
 * Time: 09:04
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController  extends AbstractController
{


    /**
     * HomeController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @Route("/", name="home_index")
     */
    public function index(Request $request) {


      return $this->render('base.html.twig', [ 'message' => 'Welcome to Home Page!' ]);
    }

    /**
     * @Route("/jsondate", name="home_jsondate")
     */
    public function jsonDate() {

        return new JsonResponse([
            'date' => date('Y-m-d H:i:s')
        ]);
    }
}
