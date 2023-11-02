<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/addaddress', name: 'add_address')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {

        $address = new Address();
        $address->setProfile($this->getUser()->getProfile());
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute('app_profile');

        }
    return $this->render('address/index.html.twig', ['form'=>$form]);

    }

    #[Route("address/delete/{id}", name:"delete_address")]
    public function delete(Address $address, EntityManagerInterface $manager)
    {
        $manager->remove($address);
        $manager->flush();
        return $this->redirectToRoute('app_profile');
    }
}
