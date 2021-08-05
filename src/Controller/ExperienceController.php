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
        //dd($experiences);

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
}
