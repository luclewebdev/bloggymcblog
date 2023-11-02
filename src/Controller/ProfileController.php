<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile/update', name: 'update_profile')]

    public function update(EntityManagerInterface $manager, Request $request)
    {
            $profile = $this->getUser()->getProfile();
            $form = $this->createForm(ProfileType::class, $profile);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager->persist($profile);
                $manager->flush();
                return $this->redirectToRoute('app_profile');
            }
        return $this->render('profile/update.html.twig', [
            'controller_name' => 'ProfileController',
            'form'=>$form
        ]);
}
}
