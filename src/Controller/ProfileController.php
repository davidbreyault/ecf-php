<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user'          => $user
        ]);
    }

    /**
     * @Route("/profile/delete_confirmation", name="delete_profile_confirmation")
     */
    public function delete_profile_confirmation(): Response
    {
        return $this->render('profile/delete/confirmation.html.twig');
    }

    /**
     * @Route("/profile/delete", name="delete_profile")
     */
    public function delete_profile(): Response
    {
        $user = $this->getUser();

        $this->entityManager->persist($user);
        // Déconnexion en force de l'utilisateur avant suppression de ce dernier    
        $this->get('security.token_storage')->setToken(null);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre compte a bien été supprimé !');

        return $this->redirectToRoute('home');
    }
}
