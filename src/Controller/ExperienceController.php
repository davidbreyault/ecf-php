<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Experience;
use App\Form\UserType;
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

        return $this->render('experience/index.html.twig', [
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
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mission a bien été prise en compte.');
            return $this->redirectToRoute('experiences');
        }

        return $this->render('experience/add.html.twig', [
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
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mission a bien été modifiée.');
            return $this->redirectToRoute('experiences');
        }

        return $this->render('experience/add.html.twig', [
            'user'              => $user,
            'experience'        => $experience,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/experience/delete/{id}", name="delete_experience")
     */
    public function delete(Request $request, $id): Response
    {
        $user = $this->getUser();
        $user->setUpdatedAt(new \DateTimeImmutable());
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);

        $this->entityManager->persist($experience);
        $this->entityManager->remove($experience);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre mission a bien été suprimée.');
        return $this->redirectToRoute('experiences');
    }







    /**
     * @Route("/profile/member/{id}/experience/add", name="add_experience_member")
     */
    public function add_experience(Request $request, int $id): Response
    {
        $experience = new Experience;
        $profile = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();

            $profile->addExperience($experience);
            $profile->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mission a bien été ajoutée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/experience/add.html.twig', [
            'profile'           => $profile,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/experience/update", name="update_experience_member")
     */
    public function update_experience(Request $request, int $id): Response
    {
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);
        $profile_id = $experience->getUser()->getId();
        $profile = $this->entityManager->getRepository(User::class)->find($profile_id);

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();

            $profile->addExperience($experience);
            $profile->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mission a bien été modifiée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/experience/add.html.twig', [
            'experience'        => $experience,
            'profile'           => $profile,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/experience/delete", name="delete_experience_member")
     */
    public function delete_experience(Request $request, int $id): Response
    {
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);
        $profile_id = $experience->getUser()->getId();
        $profile = $this->entityManager->getRepository(User::class)->find($profile_id);

        $profile->removeExperience($experience);
        $profile->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($experience);
        $this->entityManager->remove($experience);
        $this->entityManager->flush();
        $this->addFlash('success', 'La mission a bien été supprimée des expériences de ' . $profile->getFirstname());
        return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
    }
}
