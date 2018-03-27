<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $message = 'Enculé de ta race !';
        return $this->render("home.html.twig",[
           'msg' => $message
        ]);
    }
}
