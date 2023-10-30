<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {



        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/cart/add/{id}/{quantity}', name:'cart_add')]
    public function add(Article $article, $quantity, CartService $cartService)
    {
        if(!$article){return $this->redirectToRoute('app_cart');}

        $cartService->addProduct($article, $quantity);


        return $this->redirectToRoute('app_cart');
    }
}
