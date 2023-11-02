<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like_article')]
    public function like(Article $article, LikeRepository $repository, EntityManagerInterface $manager): Response
    {

        $user = $this->getUser();

        if($article->isLikedBy($user))
        {
            $like = $repository->findOneBy([
                'author'=>$user,
                'article'=>$article
            ]);
            $manager->remove($like);
            $isLiked = false;

        }else{

            $like = new Like();
            $like->setArticle($article);
            $like->setAuthor($user);
            $manager->persist($like);
            $isLiked = true;

        }

        $manager->flush();


        $data = [
            'liked' => $isLiked,
            'count' => $repository->count(['article'=>$article])
        ];

        return $this->json($data, 201);
    }
}
