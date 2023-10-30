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

#[Route('/image/')]
class ImageController extends AbstractController
{
    #[Route('addtoarticle/{id}', name: 'add_image_to_article')]
    public function addToArticle(Article $article, EntityManagerInterface $manager, Request $request): Response
    {
        if(!$article){return $this->redirectToRoute('app_article');}

        $image = new Image();
        $imageObject = $request->files->get('drop_image');
        $imageFile = $imageObject['imageDropFile'];
        $image->setImageFile($imageFile);


        $image->setArticle($article);
        $manager->persist($image);
        $manager->flush();




        return $this->redirectToRoute('show_article', ['id'=>$article->getId()]);
    }

#[Route('supprfromarticle/{id}', name:'image_article_suppr')]
    public function supprFromArticle(Image $image, EntityManagerInterface $manager):Response
    {
        if(!$image){return $this->redirectToRoute('app_article');}

        $article = $image->getArticle();


        $manager->remove($image);
        $manager->flush();
        return $this->redirectToRoute('show_article', ['id'=>$article->getId()]);

    }
}
