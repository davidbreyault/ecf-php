<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // On encode le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            // Transformation du nom de l'utilisateur en majuscule
            $user->setLastname(strtoupper($user->getLastname()));
            // Par défaut, l'utilisateur n'est pas membre de l'entreprise lors de l'inscription
            $user->setIsEmployed(0);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compte a été crée avec succès !');
            return $this->redirectToRoute('app_login');
        }


        return $this->render('register/index.html.twig', [
            'register_form' => $form->createView()
        ]);
    }
}
