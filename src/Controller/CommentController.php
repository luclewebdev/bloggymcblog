<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'app_comment')]
    public function comment(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!$article || !$user){
            $this->addFlash('info', 'connecte toi / pas trouve article');
            return $this->redirectToRoute('app_article');
        }

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid())
        {

            $comment->setAuthor($user);
            $comment->setArticle($article);



            $manager->persist($comment);
            $manager->flush();

        }

        $this->addFlash('info', "bien commenté");

        return $this->redirectToRoute('show_article', [
            'id'=>$article->getId()
        ]);

    }
}
