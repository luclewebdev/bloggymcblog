<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
private $articleRepo;
private $session;

public function __construct(ArticleRepository $articleRepo, RequestStack $requestStack)
{
    $this->articleRepo = $articleRepo;
    $this->session = $requestStack->getSession();
}

public function getCart():array
{

    $cart = $this->session->get('sessionCart', []);
    $objectsCart = [];

    foreach ($cart as $productId => $quantity)
    {
        $item = [
            'product'=>$this->articleRepo->find($productId),
            'quantity'=>$quantity
        ];
        $objectsCart[] = $item;
    }

    return $objectsCart;

}

public function addProduct(Article $article, $quantity):void
{
    $cart = $this->session->get('sessionCart', []);

    if(isset($cart[$article->getId()])){

        $cart[$article->getId()] = $cart[$article->getId()] + $quantity;
    }else{
        $cart[$article->getId()] = $quantity;
    }

    $this->session->set('sessionCart', $cart);


}

public function getTotal():int
{
    $total = 0;

    foreach ($this->getCart() as $item)
    {
        $total += $item['product']->getPrice() * $item['quantity'];
    }

    return $total;
}

public function count(): int
    {
        $count = 0;

        foreach ($this->session->get('sessionCart', []) as $quantity)
        {
            $count+=$quantity;
        }

        return $count;
    }

}