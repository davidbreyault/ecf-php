<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NewPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/new/password", name="new_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(NewPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // On encode le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            // Transfert en base de donnée
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Merci ! Votre nouveau de mot de passe a bien été pris en compte !');
            return $this->redirectToRoute('profile');
        }

        return $this->render('new_password/index.html.twig', [
            'user'                  => $user,
            'new_password_form'     => $form->createView()
        ]);
    }
}
