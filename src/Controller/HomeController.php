<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function index(UserRepository $userRepository)
    {
        // $userRepo est passé automatiquement en parametre par Symfony -> injection de dépendance. On n'a donc pas à l'instancier nous-même
        // $userRepo effectuera ici un SELECT * FROM user ...
        $userList = $userRepository->findAll();

        return $this->render("home.html.twig",[
           'users' => $userList
        ]);
    }
}
