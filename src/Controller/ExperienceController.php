<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExperienceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/experiences", name="experiences")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $experiences = $user->getExperience()->toArray();

        return $this->render('profile/experience/index.html.twig', [
            'user'          => $user,
            'experiences'   => $experiences    
        ]);
    }

    /**
     * @Route("/profile/experience/add", name="add_experience")
     */
    public function add(Request $request): Response
    {
        $experience = new Experience;
        $user = $this->getUser();

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();

            $user->addExperience($experience);
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mission a bien été prise en compte.');
            return $this->redirectToRoute('experiences');
        }

        return $this->render('profile/experience/add.html.twig', [
            'user'              => $user,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/experience/update/{id}", name="update_experience")
     */
    public function update(Request $request, $id): Response
    {
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);
        $user = $this->getUser();

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();
            $user->addExperience($experience);
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mission a bien été modifiée.');
            return $this->redirectToRoute('experiences');
        }

        return $this->render('profile/experience/update.html.twig', [
            'user'              => $user,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/experience/delete/{id}", name="delete_experience")
     */
    public function delete(Request $request, $id): Response
    {
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);

        $this->entityManager->persist($experience);
        $this->entityManager->remove($experience);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre mission a bien été suprimée.');
        return $this->redirectToRoute('experiences');
    }
}
