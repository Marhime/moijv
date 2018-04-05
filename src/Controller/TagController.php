<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\ProductRepository;
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
     * @Route("/{slug}/product/{page}", name="tag_paginated")
     */
    public function product(Tag $tag, ProductRepository $productRepository, $page = 1)
    {
        $tagProductList = $productRepository->findPaginatedByTag($tag, $page);
        return $this->render('tag/product.html.twig', [
            'products' => $tagProductList,
            'tag'      => $tag
        ]);
    }
}
