<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/')]
class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    // bundle tokens JWT : lexik jwt authentication token
    //bundle cors : composer require cors


    #[Route('addtestmessage', name:'addtestmessage')]
    public function addtestmessage(Request $request, EntityManagerInterface $manager)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $message->setAuthor($this->getUser());
            $manager->persist($message);
            $manager->flush();
        }

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'form'=>$form
        ]);

    }

    #[Route('messages', name:'messages', methods: ['GET'])]
        public function messages(MessageRepository $messageRepository)
    {
        $messages = $messageRepository->findAll();

        return $this->json($messages ,200, [], ["groups"=>"view_messages"]);
    }
    #[Route('message/{id}', name:'message', methods: ['GET'])]
    public function message(Message $message)
    {

        return $this->json($message ,200, [], ["groups"=>"view_messages"]);
    }

#[Route('newmessage', name:'newmessage', methods: ['POST'])]

public function add(SerializerInterface $serializer, Request $request,EntityManagerInterface $manager, UserRepository $userRepository):Response
{
    $message = $serializer->deserialize($request->getContent(),Message::class, "json" );
    $message->setAuthor($this->getUser());
    $manager->persist($message);
    $manager->flush();

    return $this->json($message, 200, [], ["groups"=>"view_messages"]);
}

}
