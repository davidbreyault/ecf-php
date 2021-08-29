<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Expertise;
use App\Entity\Technology;
use App\Form\UserType;
use App\Form\ExpertiseType;
use App\Form\TechnologyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpertiseController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/expertises", name="expertises")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $expertises = $user->getExpertise()->toArray();

        return $this->render('expertise/index.html.twig', [
            'user'              => $user,
            'expertises'        => $expertises
        ]);
    }

    /**
     * @Route("/profile/expertise/add", name="add_expertise")
     * 
     * Ajout d'une compétence à l'utilisateur connecté
     */
    public function add(Request $request): Response
    {
        $expertise = new Expertise;
        $user = $this->getUser();

        $form = $this->createForm(ExpertiseType::class, $expertise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expertise = $form->getData();
            $user->setUpdatedAt(new \DateTimeImmutable());
            // Ajout de la compétence à l'utilisateur
            $user->addExpertise($expertise);
            $this->entityManager->persist($expertise);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compétence a bien été prise en compte.');
            return $this->redirectToRoute('expertises');
        }

        return $this->render('expertise/add.html.twig', [
            'user'                  => $user,
            'expertise_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("profile/expertise/update/{id}", name="update_expertise")
     * 
     * Mise à jour d'une compétence à l'utilisateur connecté
     */
    public function update(Request $request, $id): Response
    {
        $user = $this->getUser();
        $expertise = $this->entityManager->getRepository(Expertise::class)->find($id);

        $form = $this->createForm(ExpertiseType::class, $expertise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expertise = $form->getData();
            $user->setUpdatedAt(new \DateTimeImmutable());
            $user->addExpertise($expertise);
            
            $this->entityManager->persist($expertise);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compétence a bien été modifiée');
            return $this->redirectToRoute('expertises');
        }

        return $this->render('expertise/add.html.twig', [
            'user'                  => $user,
            'expertise'             => $expertise,
            'expertise_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("profile/expertise/delete/{id}", name="delete_expertise")
     * 
     * Suppression d'une compétence à l'utilisateur connecté
     */
    public function delete($id): Response
    {
        $user = $this->getUser();
        $user->setUpdatedAt(new \DateTimeImmutable());
        $expertise = $this->entityManager->getRepository(Expertise::class)->find($id);

        $this->entityManager->persist($expertise);
        $this->entityManager->remove($expertise);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre compétence a bien été suprimée.');
        return $this->redirectToRoute('expertises');
    }

    /**
     * @Route("/profile/member/{id}/expertise/add", name="add_expertise_member")
     * 
     * Ajout d'une compétence à un autre utilisateur
     */
    public function add_expertise_to_someone(Request $request, int $id): Response
    {
        $expertise = new Expertise;
        $profile = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(ExpertiseType::class, $expertise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expertise = $form->getData();
            $profile->setUpdatedAt(new \DateTimeImmutable());
            $profile->addExpertise($expertise);

            $this->entityManager->persist($expertise);
            $this->entityManager->flush();
            $this->addFlash('success', 'La compétence a bien été ajoutée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/expertise/add.html.twig', [
            'profile'               => $profile,
            'expertise_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/expertise/update", name="update_expertise_member")
     * 
     * Mise à jour d'une compétence à un autre utilisateur
     */
    public function update_expertise_to_someone(Request $request, int $id): Response
    {
        $expertise = $this->entityManager->getRepository(Expertise::class)->find($id);
        $profile = $this->entityManager->getRepository(User::class)->find($expertise->getUser()->getId());

        $form = $this->createForm(ExpertiseType::class, $expertise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expertise = $form->getData();
            $profile->setUpdatedAt(new \DateTimeImmutable());
            $profile->addExpertise($expertise);

            $this->entityManager->persist($expertise);
            $this->entityManager->flush();
            $this->addFlash('success', 'La compétence a bien été modifiée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/expertise/add.html.twig', [
            'profile'               => $profile,
            'expertise'             => $expertise,
            'expertise_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/expertise/delete", name="delete_expertise_member")
     * 
     * Suppression d'une compétence à un autre utilisateur
     */
    public function delete_expertise_to_someone(Request $request, int $id): Response
    {
        $expertise = $this->entityManager->getRepository(Expertise::class)->find($id);
        $profile = $this->entityManager->getRepository(User::class)->find($expertise->getUser()->getId());
        $profile->setUpdatedAt(new \DateTimeImmutable());
        $profile->removeExpertise($expertise);

        $this->entityManager->persist($expertise);
        $this->entityManager->remove($expertise);
        $this->entityManager->flush();
        $this->addFlash('success', 'La compétence de '. $profile->getFirstname() .' a bien été supprimée.');
        return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
    }
}
