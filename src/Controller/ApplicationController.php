<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("profile/application", name="application")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ApplicationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // Transformation du nom de et de la ville de l'utilisateur en majuscule
            $user->setLastname(strtoupper($user->getLastname()));
            $user->setTown(strtoupper($user->getTown()));
            $user->setUpdatedAt(new \DateTimeImmutable());
            // Transfert en base de donnée
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Merci ! Votre candidature a bien été prise en compte !');
            return $this->redirectToRoute('profile');
        }

        return $this->render('application/index.html.twig', [
            'application_form' => $form->createView()
        ]);
    }
}
