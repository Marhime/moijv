<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(UserRepository $userRepository)
    {
        // $userRepo est passé automatiquement en parametre par Symfony -> injection de dépendance. On n'a donc pas à l'instancier nous-même
        // $userRepo effectuera ici un SELECT * FROM user ...
        $userList = $userRepository->findAll();

        return $this->render("admin/dashboard.html.twig", [
            'users' => $userList
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/admin/user/add", name="add_user")
     * @Route("/admin/user/edit/{id}", name="edit_user")
     */
    public function editUser(Request $request, ObjectManager $manager, User $user = null)
    {
        if ($user === null) {
            $user = new User();
        }
        $formUser = $this->createForm(UserType::class, $user)
            ->add('Envoyer', SubmitType::class);

        // ... todo : validation du formulaire
        $formUser->handleRequest($request); // déclenche la gestion de formulaire

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            // enregistrement de notre utilisateur
            $user->setRegisterDate(new \DateTime('now'));
            $user->setRoles('ROLE_USER');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $formUser->createView()

        ]);
    }

    /**
     * @Route("/admin/product/add", name="add_product")
     * @Route("/admin/product/edit/{id}", name="edit_product")
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new Product();
        }
        $formProduct = $this->createForm(UserType::class, $product)
            ->add('Envoyer', SubmitType::class);
        // ... todo : validation du formulaire
        $formProduct->handleRequest($request); // déclenche la gestion de formulaire

        if($formProduct->isSubmitted() && $formProduct->isValid()){
            // enregistrement de notre produit
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/add_product.html.twig', [
            'form' => $formProduct->createView()
        ]);
    }

    /**
     * @Route("/admin/product/list", name="product_list")
     */
    public function produit(ProductRepository $productRepository)
    {
        // $userRepo est passé automatiquement en parametre par Symfony -> injection de dépendance. On n'a donc pas à l'instancier nous-même
        // $userRepo effectuera ici un SELECT * FROM user ...
        $productList = $productRepository->findAll();

        return $this->render("product/liste_produit.html.twig", [
            'products' => $productList
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product_list');
    }
}
