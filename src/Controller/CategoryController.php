<?php
namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_add")
     */
    public function add(Request $request, ObjectManager $objectManager) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager->persist($category);
            $objectManager->flush();
            return $this->redirectToRoute('app_index');
        }
        return $this->render(
            'category/add.html.twig',
            ['form' => $form->createView()]
        );
    }
}