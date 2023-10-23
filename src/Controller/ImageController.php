<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ImageController extends AbstractController
{
    #[Route('/image/article/{id}', name: 'app_image_article')]
    public function addToArticle(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$article || !$this->getUser()){return $this->redirectToRoute('app_article');}



        $image = new Image();
        $imageForm = $this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);
        if($imageForm->isSubmitted() && $imageForm->isValid())
        {
            $image->setArticle($article);
            $manager->persist($image);
            $manager->flush();

        }


        return $this->render('article/addImage.html.twig', [
            'article' => $article,
            'imageForm'=>$imageForm,
        ]);
    }

    #[Route('/image/article/delete/{id}', name:'suppr_image')]
public function supprFromArticle(Image $image, EntityManagerInterface $manager)
    {
            $article = $image->getArticle();
            $manager->remove($image);
            $manager->flush();

            return $this->redirectToRoute('app_image_article', [
                'id'=>$article->getId()
            ]);


    }
}
