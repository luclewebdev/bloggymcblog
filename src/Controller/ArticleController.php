<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article/')]

class ArticleController extends AbstractController
{
    #[Route('', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {


        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('{id}', name:'show_article', priority: -1 )]
    #[Route('{name}', name:'show_article_name', priority: -1 )]
    public function show(Article $article)
    {


        if(!$article){return $this->redirectToRoute('app_article');}

        return $this->render('article/show.html.twig', [
                'article'=>$article
        ]);
    }

    #[Route('create', name:"create_article")]
    #[Route('edit/{id}', name:"edit_article")]
    public function create(Article $article =null ,Request $request, EntityManagerInterface $manager)
        {
            $edit =true;
            if(!$article){
                $article = new Article();
                $edit=false;
            }

            $form = $this->createForm(ArticleType::class, $article);

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {


                $manager->persist($article);
                $manager->flush();
                return $this->redirectToRoute('show_article', [
                    'id'=>$article->getId()
                ]);
            }

            return $this->render('article/new.html.twig', [
                'form'=>$form->createView(),
                'edit' => $edit,
                'article'=>$article
            ]);
        }

        #[Route('delete/{id}', name:'delete_article')]
        public function delete(Article $article, EntityManagerInterface $manager)
        {

            if($article){
                $manager->remove($article);
                $manager->flush();
            }

            return $this->redirectToRoute('app_article');
        }
}
