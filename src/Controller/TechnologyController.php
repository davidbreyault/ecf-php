<?php

namespace App\Controller;

use App\Entity\Technology;
use App\Entity\Category;
use App\Entity\Expertise;
use App\Form\TechnologyType;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TechnologyController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/technologies", name="technologies")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();

        return $this->render('technology/index.html.twig', [
            'user'              => $user,
            'technologies'      => $technologies
        ]);
    }

    /**
     * @Route("/profile/technology/add", name="add_technology")
     */
    public function add(Request $request): Response
    {
        $technology = new Technology;
        $form = $this->createForm(TechnologyType::class, $technology);
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        //dd($categories);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technology = $form->getData();
            // Ajout d'une catégorie par défaut 
            if (empty($categories)) {
                $category = new Category;
                $category->setName('Non-classée');
                $technology->setCategory($category);
                $this->entityManager->persist($category);
            }
            $this->entityManager->persist($technology);
            $this->entityManager->flush();
            $this->addFlash('success', 'La technologie \'' . $technology->getName() . '\' a bien été ajoutée à votre liste.');
            return $this->redirectToRoute('technologies');
        }

        return $this->render('technology/add.html.twig', [
            'technology_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/technology/{id}/update", name="update_technology")
     */
    public function update(Request $request, int $id): Response
    {
        $technology = $this->entityManager->getRepository(Technology::class)->find($id);
        $form = $this->createForm(TechnologyType::class, $technology);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technology = $form->getData();

            $this->entityManager->persist($technology);
            $this->entityManager->flush();
            $this->addFlash('success', 'La technologie \'' . $technology->getName() . '\' a bien été modifiée de votre liste.');
            return $this->redirectToRoute('technologies');
        }

        return $this->render('technology/add.html.twig', [
            'technology'           => $technology, 
            'technology_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/technology/{id}/delete/confirmation", name="delete_technology_confirmation")
     */
    public function delete_confirmation(int $id): Response
    {
        $technology = $this->entityManager->getRepository(Technology::class)->find($id);
        
        return $this->render('technology/delete.html.twig', [
            'technology'           => $technology
        ]);
    }

    /**
     * @Route("/profile/technology/{id}/delete", name="delete_technology")
     */
    public function delete(Request $request, int $id): Response
    {
        $technology = $this->entityManager->getRepository(Technology::class)->find($id);
        
        $expertises = $technology->getExpertise()->toArray();
        foreach($expertises as $expertise) {
            $technology->removeExpertise($expertise);
            $this->entityManager->remove($expertise);
        }
        $this->entityManager->persist($technology);
        $this->entityManager->remove($technology);
        $this->entityManager->flush();
        $this->addFlash('success', 'La technologie ' . $technology->getName() . ' a bien été supprimée.');
        return $this->redirectToRoute('technologies');
    }
}

