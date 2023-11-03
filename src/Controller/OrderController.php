<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\AddressRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/billing/{id}', name: 'app_order_billing')]
    #[Route('/order/billing/', name: 'app_order_select_billing')]
    public function billing(Address $billingAddress=null): Response
    {

        if($billingAddress){

            return $this->redirectToRoute('app_order_select_shipping', ['id'=>$billingAddress->getId()]);
        }


        return $this->render('order/billing.html.twig', [
        ]);
    }
    #[Route('/order/billing/{id}/shipping/{idShipping}', name: 'app_order_shipping')]
    #[Route('/order/billing/{id}/shipping/', name: 'app_order_select_shipping')]
    public function shipping(Address $billingAddress, AddressRepository $addressRepository, $idShipping=null): Response
    {
        if($idShipping){
            $shippingAddress = $addressRepository->find($idShipping);
            if($shippingAddress){

                return $this->redirectToRoute('app_payment', ['id'=>$billingAddress->getId(), 'idShipping'=>$shippingAddress->getId()]);
            }
        }



        return $this->render('order/shipping.html.twig', [ 'billingAddress'=>$billingAddress
        ]);
    }

    #[Route('/order/payment/billing{id}/shipping/{idShipping}', name:'app_payment')]
    public function payment(Address $billingAddress, AddressRepository $addressRepository, $idShipping)
    {
        $shippingAddress = $addressRepository->find($idShipping);


        return $this->render('order/payment.html.twig', [ 'billingAddress'=>$billingAddress, "shippingAddress"=> $shippingAddress
        ]);
    }

    #[Route('/order/makeorder/billing/{id}/shipping/{idShipping}', name:'app_makeorder')]
    public function makeOrder(Address $billingAddress, AddressRepository $addressRepository, $idShipping, CartService $cartService, EntityManagerInterface $manager)
    {
        $shippingAddress = $addressRepository->find($idShipping);

        $order = new Order();
        $order->setCreatedAt(new \DateTime());
        $order->setTotal($cartService->getTotal());
        $order->setFromUser($this->getUser()->getProfile());
        $order->setBillingAddress($billingAddress);
        $order->setShippingAddress($shippingAddress);
        $manager->persist($order);

        foreach($cartService->getCart() as $item)
        {
            $orderItem = new OrderItem();
            $orderItem->setArticle($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setOfOrder($order);
            $manager->persist($orderItem);
        }


        $manager->flush();


        return $this->redirectToRoute('app_profile');
    }
    #[Route('/test/{id_s}/{id_b}', name:'testroute')]
    public function test(
        #[MapEntity(class: Address::class, mapping: ['id'=>'id_s'])] $as,
        #[MapEntity(class: Address::class, mapping: ['id'=>'id_b'])] $ab,
    ):Response
    {
        dd($as);
        return $this->json("oui", 200);
    }

}
