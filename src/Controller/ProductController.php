<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ProductController
 * @Route("/product")
 */

class ProductController extends Controller
{
    /**
     * @Route("/", name="product")
     * @Route("/{page}", name="product_paginated", requirements={"page"="\d+"})
     *
     */
    public function index(ProductRepository $productRepository, $page = 1)
    {
        $productList = $productRepository->findPaginatedByUser($this->getUser(), $page);
        return $this->render('product/index.html.twig', [
            'products' => $productList
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        if($product->getOwner()->getId() !== $this->getUser()->getId())
        {
            throw $this->createAccessDeniedException("Vous n'êtes pas autorisé à supprimer ce produit");
        }
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }


    /**
     * @Route("/add", name="add_product")
     * @Route("/edit/{id}", name="edit_product")
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new Product();
            $group = 'insertion';
        } else {
            $oldImage = $product->getImage();
            $product->setImage(new File($product->getImage()));
            $group = 'edition';
        }
        $formProduct = $this->createForm(ProductType::class, $product, ['validation_groups'=>[$group]])
            ->add('Envoyer', SubmitType::class);
        // ... todo : validation du formulaire
        $formProduct->handleRequest($request); // déclenche la gestion de formulaire

        if($formProduct->isSubmitted() && $formProduct->isValid()){
            $product->setOwner($this->getUser());
            $image = $product->getImage();
            if($image === null)
            {
                $product->setImage($oldImage);
            }
            else {
                $newFileName = md5(uniqid()). '.' . $image->guessExtension();
                $image->move('uploads', $newFileName);
                $product->setImage('uploads/'.$newFileName);
            }
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
