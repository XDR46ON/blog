<?php
// src/Controller/BlogController.php
namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

        return $this->render(
                'blog/index.html.twig',
                ['articles' => $articles]
        );
    }

    /**
     * Getting an article with a formatted slug for title
     *
     * @param string $slug 
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
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
     * @param string $categoryName
     * @return Response
     * @Route("blog/category/{categoryName}", name="blog_category")
     */
    public function showByCategory(string $categoryName) :Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);
        if (!$category) {
            throw $this->createNotFoundException(
                'Category not found in category\'s table'
            );
        }
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['category'=>$category->getId()], ['id' => 'DESC'], 3);


        if (!$articles) {
            throw $this->createNotFoundException(
                'No articles found for ' . $categoryName . ' in article\'s table'
            );
        }
        return $this->render(
            'blog/category.html.twig',
            [
                'category' => $categoryName,
                'articles' => $articles
            ]
        );
    }
}