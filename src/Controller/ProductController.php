<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     * @Route("/product/{page}", name="product_paginated", requirements={"page"="\d+"})
     */
    public function index(ProductRepository $productRepository, $page = 1)
    {
        $productList = $productRepository->findPaginated($page);
        return $this->render('product/index.html.twig', [
            'products' => $productList
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }


    /**
     * @Route("/product/add", name="add_product")
     * @Route("/product/edit/{id}", name="edit_product")
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new Product();
        }
        $formProduct = $this->createForm(ProductType::class, $product)
            ->add('Envoyer', SubmitType::class);
        // ... todo : validation du formulaire
        $formProduct->handleRequest($request); // déclenche la gestion de formulaire

        if($formProduct->isSubmitted() && $formProduct->isValid()){
            // enregistrement de notre produit
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('product/add_product.html.twig', [
            'form' => $formProduct->createView()
        ]);
    }
}
