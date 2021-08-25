<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/categories", name="categories")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'user'                => $user,
            'categories'          => $categories
        ]);
    }

    /**
     * @Route("/profile/category/add", name="add_category")
     */
    public function add(Request $request): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'La catégorie \'' . $category->getName() . '\' a bien été ajoutée à votre liste.');
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/add.html.twig', [
            'category_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/category/{id}/update", name="update_category")
     */
    public function update(Request $request, int $id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'La catégorie \'' . $category->getName() . '\' a bien été modifiée.');
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/add.html.twig', [
            'category'           => $category,
            'category_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/category/{id}/delete/confirmation", name="delete_category_confirmation")
     */
    public function delete_confirmation(int $id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        return $this->render('category/delete.html.twig', [
            'category'           => $category
        ]);
    }

    /**
     * @Route("/profile/category/{id}/delete", name="delete_category")
     */
    public function delete(Request $request, int $id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        $technologies = $category->getTechnology();
        // Attention ! Cette action supprimera également les technologies liées à cette catégorie !
        foreach($technologies as $technology) {
            $category->removeTechnology($technology);
            $this->entityManager->remove($technology);
            $expertises = $technology->getExpertise()->toArray();
            // La suppression d'une catégortie engendre également la suppression des compétences qui lui sont liées
            foreach($expertises as $expertise) {
                $technology->removeExpertise($expertise);
                $this->entityManager->remove($expertise);
            }
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->addFlash('success', 'La catégorie \'' . $category->getName() . '\' a bien été supprimée de votre liste.');
        return $this->redirectToRoute('categories');
    }
}
