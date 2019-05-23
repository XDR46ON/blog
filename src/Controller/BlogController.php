<?php
// src/Controller/BlogController.php
namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
            'No article found in article\'s table.'
            );
        }

        $form = $this->createForm(
            ArticleSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
         );
         
         return $this->render(
            'blog/index.html.twig', [
                'articles' => $articles,
                'form' => $form->createView(),
             ]
         );
         
    }

    /**
     * Getting an article with a formatted slug for title
     *
     * @param string $slug 
     *
     * @Route("blog/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug"},
     *     name="blog_show")
     *  @return Response 
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
                throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
            }

        $slug = ucwords(preg_replace('/-/'," ", $slug));

        $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
            'No article with '.$slug.' title, found in article\'s table.'
        );
        }

        return $this->render(
        'blog/show.html.twig',
        [
                'article' => $article,
                'slug' => $slug,
        ]
        );
    }

    /**
     * @param Category
     * @return Response
     * @Route("blog/category/{name}", name="blog_category")
     */
    public function showByCategory(Category $category) :Response
    {
        if (!$category) {
            throw $this->createNotFoundException(
                'Category not found in category\'s table'
            );
        }
        $articles = $category->getArticles();


        if (!$articles) {
            throw $this->createNotFoundException(
                'No articles found for ' . $category->getName() . ' in article\'s table'
            );
        }
        return $this->render(
            'blog/category.html.twig',
            [
                'category' => $category,
                'articles' => $articles
            ]
        );
    }
}