<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }
     /**
      * @Route("/article/single/{article}", name= "single_article")
      */
    public function single(Article $article)
    {


        return $this->render('article/single.html.twig',
        [
            'article'=>$article,
        ]);
    }

    /**
     * @Route("article/create", name="create_article")
     */
    public function create(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $article = $form->getData();
            $article->setCreatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('articles');
        }
            return $this->render('article/form.html.twig',[
                'form'=> $form->createView()
            ]);
        }
     /**
      * @Route("article/delete/{article}",name="article_delete")
      */
    public function delete(Article $article)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('articles');
        }


}
