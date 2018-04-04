<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TagController
 * @Route ("/tag")
 */

class TagController extends Controller
{
    /**
     * @Route("/{slug}/product", name="tag")
     */
    public function product(Tag $tag)
    {
        return $this->render('tag/product.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}
