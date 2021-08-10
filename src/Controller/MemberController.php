<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Experience;
use App\Form\UserType;
use App\Form\ExperienceType;
use App\Form\ApplicationType;
use App\Form\SearchMemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/profile/members", name="members")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $profiles = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('member/index.html.twig', [
            'user'              => $user,
            'profiles'          => $profiles
        ]);
    }

    /**
     * @Route("/profile/members/search", name="search_members")
     */
    public function search(Request $request)
    {
        $searchMemberForm = $this->createForm(SearchMemberType::class);
        $profiles = null;
        $searchMemberForm->handleRequest($request);

        if ($searchMemberForm->isSubmitted() && $searchMemberForm->isValid()) {
            $settings = $searchMemberForm->getData();
            $profiles = $this->entityManager->getRepository(User::class)->searchProfile($settings);
        }

        return $this->render('member/search.html.twig', [
            'search_member_form'       => $searchMemberForm->createView(),
            'profiles'                 => $profiles
        ]);
    }

    /**
     * @Route("/profile/member/{id}", name="card_member")
     */
    public function card(Request $request, int $id)
    {
        $user = $this->getUser();
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        $skills = $profile->getSkill()->toArray();
        $experiences = $profile->getExperience()->toArray();

        return $this->render('member/card.html.twig', [
            'user'              => $user,
            'profile'           => $profile,
            'skills'            => $skills,
            'experiences'       => $experiences
        ]);
    }

    /**
     * @Route("/profile/members/add", name="add_member")
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $profile = new User;
        $addMemberForm = $this->createForm(UserType::class, $profile);
        $addMemberForm->handleRequest($request);

        if ($addMemberForm->isSubmitted() && $addMemberForm->isValid()) {
            $profile = $addMemberForm->getData();
            // Encodage du mot de passe
            $profile->setPassword($passwordEncoder->encodePassword($profile, $profile->getPassword()));
            // Transformation du nom de et de la ville de l'utilisateur en majuscule
            $profile->setLastname(strtoupper($profile->getLastname()));
            $profile->setTown(strtoupper($profile->getTown()));
            $profile->setCreatedAt(new \DateTimeImmutable());
            // Si le nouveau profil compte parmi l'effectif de l'entreprise
            if ($profile->getIsEmployed()) {
                // Attribution de son rôle
                $profile->setRoles(['ROLE_EMPLOYEE']);
            }

            // Transfert en base de donnéees
            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le nouvel utilisateur a bien été crée !');
            return $this->redirectToRoute('members');
        }

        return $this->render('member/add.html.twig', [
            'add_member_form'           => $addMemberForm->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/update", name="update_member")
     */
    public function update(Request $request, int $id): Response
    {
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        $user = $this->getUser();

        $form = $this->createForm(ApplicationType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile = $form->getData();

            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            $this->addFlash('success', 'Les données de ' . $profile->getFirstname() . ' ont bien été mises à jour.');
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/update.html.twig', [
            'user'                  => $user,
            'profile'               => $profile,
            'profile_form'          => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/delete", name="delete_member")
     */
    public function delete(Request $request, int $id): Response
    {
        // La suppression d'un utilisateur implique d'abord la suppression de ses expériences et de ses compétences pour éviter les erreurs relationnelles SQL.
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        
        // Suppression des expériences
        $experiences = $profile->getExperience();
        foreach($experiences as $experience) {
            $profile->removeExperience($experience);
            $this->entityManager->persist($experience);
            $this->entityManager->remove($experience);
            $this->entityManager->flush();
        }

        // Suppression des compétences
        $skills = $profile->getSkill();
        foreach($skills as $skill) {
            $profile->removeSkill($skill);
            $this->entityManager->persist($skill);
            $this->entityManager->remove($skill);
            $this->entityManager->flush();
        }

        // Enfin, suppression de l'utilisateur
        $this->entityManager->persist($profile);
        $this->entityManager->remove($profile);
        $this->entityManager->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');
        return $this->redirectToRoute('members');
    }

    /**
     * @Route("/profile/member/{id}/takeon", name="takeon_candidate")
     */
    public function takeOn(Request $request, int $id): Response
    {
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        //dd($profile->getIsEmployed());
        $profile->setIsEmployed(1);
        $profile->setRoles(['ROLE_EMPLOYEE']);
        $this->entityManager->persist($profile);
        $this->entityManager->flush();
        $this->addFlash('success', 'Félicitations ! Vous avez embauché ' . $profile->getFirstname() . ' ' . $profile->getLastname());
        return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
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
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mission a bien été modifiée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/experience/update.html.twig', [
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
        $this->entityManager->persist($experience);
        $this->entityManager->remove($experience);
        $this->entityManager->flush();
        $this->addFlash('success', 'La mission a bien été supprimée des expériences de ' . $profile->getFirstname());
        return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
    }
}
